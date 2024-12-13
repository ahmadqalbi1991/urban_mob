<!DOCTYPE html>
<html>
<head>
    <title>How To Integrate Razorpay Payment Gateway In Laravel - Techsolutionstuff</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>    
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
             <section class="pt-20">
                <div class="container" style="padding-top: 60px;padding-bottom: 80px;" >
                    <a href="{{url('/')}}" class="btn-sm btn btn-secondary">Back</a>
                   <h4>Do not refresh the page while payment is being processed</h4>

                </div>
            </section>

            <form action="{!!route('payment.rozer')!!}" method="POST" id='rozer-pay' style="display: none;">
                <!-- Note that the amount is in paise = 50 INR -->
                <!--amount need to be in paisa-->
                <script src="https://checkout.razorpay.com/v1/checkout.js"
                        data-key="{{ env('RAZOR_KEY') }}"
                        data-amount=1000
                        data-buttontext="Pay Amount"
                        data-name="{{ env('APP_NAME') }}"
                        data-description="Payment"
                        data-image=""
                        data-prefill.name="{{ Auth::user()->name}}"
                        data-prefill.email="{{ Auth::user()->email}}"
                        data-theme.color="#ff7529">
                </script>
                <input type="hidden" name="_token" value="{!!csrf_token()!!}">
            </form>

        </div>
    </div>
</div>
<script type="text/javascript">
        $(document).ready(function(){
            $('#rozer-pay').submit()
        });
    </script>
</body>
</html>

