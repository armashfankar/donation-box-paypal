<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="/css/app.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            <div class="col-md-6 ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Merchant Account (Total Commissions)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="amount">Merchant Fee Received (3% of each transaction)</label>
                            <input type="text" name="amount" value="USD {{($merchant_account ? $merchant_account->total_fee : 0)}}" readonly disabled class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="amount">Total Donations</label>
                            <input type="text" name="amount" value="{{($merchant_account ? $merchant_account->transaction_counts : 0)}}" readonly disabled class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                           Donate Money
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="payment-form">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Amount*</label>
                                <input type="text" name="amount" id="amount" class="form-control" required/>
                                @if ($errors->has('amount'))
                                <div class="text-danger">
                                    {{ $errors->first('amount') }}
                                </div>
                                @endif
                            </div>
                            <button class="btn btn-block btn-primary">Pay with PayPal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>