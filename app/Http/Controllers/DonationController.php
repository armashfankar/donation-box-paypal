<?php

namespace App\Http\Controllers;

use Config;
//paypal sdk
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use App\Donation;
use App\MerchantFee;

class DonationController extends Controller
{
    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function index () {
        $merchant_account = MerchantFee::first();

        return view('welcome',compact('merchant_account'));
    }

    public function checkout(Request $request) {
        $request->validate([
            'amount' => 'required|integer|min:1|max:100000'
        ]);
        $title = 'Donation';
        $total = $request->amount;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName($title)
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setPrice($total);

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')->setTotal($total);

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route("paypal.callback"))
        ->setCancelUrl(route('paypal.callback'));

        $payment = new Payment();

        $payment->setIntent('Sale')
        ->setPayer($payer)
        ->setRedirectUrls($redirect_urls)
        ->setTransactions(array($transaction));

        try {
            
            $payment->create($this->_api_context);
            $payment_init = $payment->toArray();

        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                return redirect(route("home"))->with("error", "Connection timeout");
            } else {
                return redirect(route("home"))->with("error", "Some error occured.");
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        if (isset($redirect_url)) {
            return redirect($redirect_url);
        }
        return redirect(route("home"))->with("error", "Unknown error");
    }

    public function callback(Request $request)
    {

        $payment_id = $request->paymentId;
        $payerID = $request->PayerID;
        $paypal_token = $request->token;

        if (empty($paypal_token) || empty($payerID)) {
            return redirect(route("home"))->with("error", "Payment failed");
        }

        try{
            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerID);
            $result = $payment->execute($execution, $this->_api_context);

            $my_response = [
                'paypal_id' => $payment_id,
                'payer_id' => $payerID,
                'transaction_state' => $result->getState(),
            ];

            if ($result->payer) {
                $my_response['payment_method'] = $result->payer->payment_method;
                $my_response['payment_status'] = $result->payer->status;
                $payer_info = [];

                if ($result->payer->payer_info) {
                    $my_response["email"] = $result->payer->payer_info->email;
                    $my_response["first_name"] = $result->payer->payer_info->first_name;
                    $my_response["last_name"] = $result->payer->payer_info->last_name;
                    $my_response["payer_id"] = $result->payer->payer_info->payer_id;
                    $my_response["country_code"] = $result->payer->payer_info->country_code;
                    if ($result->payer->payer_info->shipping_address) {
                        try {
                            foreach (json_decode($result->payer->payer_info->shipping_address) as $k => $v) {
                                $payer_info[$k] = $v;
                            }
                            $my_response["payer_info"] = \json_encode($payer_info);
                        }
                        catch(\Exception $ex) {
                            Log::info("An error occured during event booking payment success callback function json_decode($result->payer->payer_info->shipping_address)");
                            Log::info($ex);
                        }
                    }
                }
            }
            if ($result->transactions[0]) {

                if($result->transactions[0]->amount) {
                    $my_response["total"] = $result->transactions[0]->amount->total;
                    $my_response["currency"] = $result->transactions[0]->amount->currency;
                }
                if($result->transactions[0]->item_list) {
                    if($result->transactions[0]->item_list->items) {
                        if($result->transactions[0]->item_list->items[0]) {
                            $my_response["event_id"] = $result->transactions[0]->item_list->items[0]->name;
                        }
                    }
                }

                if ($result->transactions[0]->related_resources) {
                    if ($result->transactions[0]->related_resources[0]) {
                        if ($result->transactions[0]->related_resources[0]->sale) {
                            if ($result->transactions[0]->related_resources[0]->sale->transaction_fee) {
                                $my_response["transaction_fee"] = $result->transactions[0]->related_resources[0]->sale->transaction_fee->value;
                            }
                        }
                    }
                }

            }

            $my_response["paypal_created_time"] = $result->create_time;
            $my_response["paypal_updated_time"] = $result->update_time;
            $my_response["merchant_fees"] = (3 / 100) * $result->transactions[0]->amount->total;                              
            
            if ($result->getState() == 'approved') {
                
                //success
                $donation = Donation::create($my_response);
                
                //update merchant fee records
                $this->merchantFee($donation->merchant_fees);

                return redirect(route("thankyou", $donation->id))->with("success", "Thank you for donating USD " . $donation->total);
            }else{
                //failed
                return redirect(route("home"))->with("error", 'Transaction failed. Payment ID: '.$payment_id);
            }

            return redirect(route("home"))->with("error", 'Transaction failed');

        }catch(\Exception $ex){
            return redirect(route("home"))->with("error", 'Transaction failed. Payment ID: '.$payment_id);
        }
    }

    public function merchantFee($commission)
    {
        //fetch existing merchant account
        $merchant_account = MerchantFee::first();
        
        if($merchant_account){
            
            $merchant_account->update([
                'total_fee' => $merchant_account->total_fee + $commission,
                'transaction_counts' => $merchant_account->transaction_counts + 1
            ]);

        }else{

            MerchantFee::create([
                'total_fee' => $commission,
                'transaction_counts' => 1
            ]);
        }
    }

    public function thankyou(Request $request, $id) {
        $donation = Donation::where("id", $id)->first();
        if ($donation) {
            return view("thankyou")->with("donation", $donation);
        } else {
            return redirect(route("home"))->with("error", "Unable to fetch donation details.");
        }
    }
}
