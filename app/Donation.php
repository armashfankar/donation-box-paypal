<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $guarded = ["id"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email','first_name','last_name','country_code','total','merchant_fees','currency','payment_method','payment_status','payer_info','event_id','transaction_fee','paypal_created_time','paypal_updated_time','paypal_id','payer_id','transaction_state'
    ];
}
