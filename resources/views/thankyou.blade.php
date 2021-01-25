<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Donation</title>

  <!-- Fonts -->
  <link href="/css/app.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="row mt-5">
      @if (session('success'))
      <div class="col-10 mx-auto">
        <div class="alert alert-success" role="alert">
          {{ session('success') }}
        </div>
      </div>
      @endif

      <div class="col-md-10 mx-auto">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Thank you
            </h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered">
              <tr>
                <td>Email</td>
                <td>{{$donation->email}}</td>
              </tr>
              <tr>
                <td>First Name</td>
                <td>{{$donation->first_name}}</td>
              </tr>
              <tr>
                <td>Last Name</td>
                <td>{{$donation->last_name}}</td>
              </tr>
              <tr>
                <td>Payer ID</td>
                <td>{{$donation->payer_id}}</td>
              </tr>
              <tr>
                <td>Country Code</td>
                <td>{{$donation->country_code}}</td>
              </tr>
              <tr class="table-primary">
                <td>Total</td>
                <td>{{$donation->currency}} {{$donation->total}}</td>
              </tr>
              <tr class="table-success">
                <td>Merchant Fees (3% Commision)</td>
                <td>{{$donation->currency}} {{$donation->merchant_fees}}</td>
              </tr>
              <tr>
                <td>Currency</td>
                <td>{{$donation->currency}}</td>
              </tr>
              <tr>
                <td>Transaction Fee</td>
                <td>{{$donation->transaction_fee}}</td>
              </tr>
              <tr>
                <td>Paypal ID</td>
                <td>{{$donation->paypal_id}}</td>
              </tr>
              <tr>
                <td>Payer ID</td>
                <td>{{$donation->payer_id}}</td>
              </tr>
              <tr>
                <td>Transaction State</td>
                <td class="text-capitalize">{{$donation->transaction_state}}</td>
              </tr>
            </table>
            <a href="/" class="btn btn-success">Go to Home</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>