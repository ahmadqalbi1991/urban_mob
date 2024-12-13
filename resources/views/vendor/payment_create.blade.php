@extends('layouts.dashboard')
<style>
    .count-card {
        color: aliceblue;
        padding: 2%;
    }
    .count {
        float: right;
    }
    .pay-btn {
        width: 10%;
    }
</style>
@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Vendor Detail</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        

                    </div>

                

                <div class="container-fluid">

                    <div class="row">

                        <div class="col-lg-12">

                            @include('vendor.top')

                        </div>

                        <div class="col-lg-12">

                            <div class="card">

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-sm-8">
                                            
                                            <h5 class="card-title">Vendor Payment</h5>
                                            
                                        </div>

                                        <div class="col-sm-4">

                                            <div class="text-right mb-4">

                                                <a href="{{url('vendor/payment/'.$user->id.'/payment')}}"><button class="btn btn-primary">Back</button></a>
                                                
                                            </div>

                                        </div>

                                    </div>

                                    <div class="custom-tab-2">
                                        
                                        <form action="{{ url('store/vendor/payment') }}" method="POST" class="payment-form">
                                            @csrf
                                            <input type="hidden" name="vendor_id" value="{{$user?$user->id:''}}">

                                            <div class="row">
                                                
                                                <div class="col-sm-4">

                                                    <div class="form-group">

                                                        <label>Transaction No. :</label>
                                                        <input type="text" class="form-control" name="transaction_no" value="{{old('transaction_no')}}" placeholder="Enter Transaction No.">

                                                    </div>

                                                </div>

                                                <div class="col-sm-4">

                                                    <div class="form-group">

                                                        <label>Amount<span class="text-danger">*</span> :</label>
                                                        <input type="text" class="form-control amount" name="amount" value="{{$user?$user->wallet_balance:''}}" placeholder="Enter Amount" required>
                                                        <input type="hidden" value="{{$user?$user->wallet_balance:''}}" class="remaning_amt" name="remaning_amt"> 
                                                         <span class="error-msg-amt text-danger"></span>
                                                    </div>

                                                </div>

                                                <div class="col-sm-4">

                                                    <div class="form-group">

                                                        <label>Payment Mode<span class="text-danger">*</span> :</label>

                                                        <select class="form-control moad_val" name="moad" required>
                                                            <option value="">Select Payment Mode</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Online">Online</option>
                                                            <option value="Cheque">Cheque</option>
                                                        </select>
                                                        <span class="error-msg-moad text-danger"></span>

                                                    </div>

                                                </div>

                                                <div class="col-sm-4">

                                                    <div class="form-group">

                                                        <label>Date :</label>
                                                        <input type="date" class="form-control" name="transaction_date" value="{{old('transaction_date')}}">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="text-right mt-4">

                                                <button type="button" onclick="pay()" class="btn btn-primary pay-btn">Pay</button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!--**********************************

            Content body end

        ***********************************-->



@endsection    

@section('script')
    
<script>
    function pay() {
        var remaning_amt    = $('.remaning_amt').val();
        var amount          = $('.amount').val();
        var moad            = $('.moad_val').val();
        
        if(amount){
            $('.error-msg-amt').text('');
            if(Number(amount)>Number(remaning_amt)){
                $('.error-msg-amt').text('Enter Less Then Amount of Payout Balance');
            } else {
                if(moad){
                    $('.payment-form').submit();
                } else {
                    $('.error-msg-moad').text('Select Payment Moad');
                }
            }
        } else {
            $('.error-msg-amt').text('Enter Amount');
        }
    }
</script>

@endsection  


       