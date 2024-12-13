@extends('layouts.dashboard')

@section('content')
<style>
    .total-w {
        width: 15%;
    }
    .btn-w {
        width: 170px !important;
    }
</style>
        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Amount Details</h3>

                        </div>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="custom-tab-2">
                                    <?php $data['active'] = 'Amount'; ?>
                                    @include('offline.menu',$data)
                                    <form action="{{ route('confirm.booking') }}" method="post">
                                    @csrf
                                        <div class="row attribute_list">
                                            @include('offline.amount_details.attribute_list')
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-lg-6">
                                                <div class="text-left">
                                                    <a href="{{ route('step3') }}"><button class="btn btn-primary" type="button">Back</button></a>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="text-right">
                                                    <button class="btn btn-primary" type="submit">Continue</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

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
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    function pay_tip(){
        var tip = $('.tip_value').val();
        if(tip){
            $('.tip_value_error').text('');
            $.ajax({
                type: 'Post',
                url: "{{ route('pay.tip.value') }}",
                data: {
                        _token: CSRF_TOKEN,
                        tip: tip,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.status){
                        $('.attribute_list').html(data.modal_view);  
                    }                                     
                },
                error: function (data) {
                    console.log(data);
                }
            });
        } else {
            $('.tip_value_error').text('Enter Tip Amount');
        }
    }
</script>
<script>    
    function pay_charge(){
        var charge = $('.charge_value').val();
        if(charge){
            $('.charge_value_error').text('');
            $.ajax({
                type: 'Post',
                url: "{{ route('pay.charge.value') }}",
                data: {
                        _token: CSRF_TOKEN,
                        charge: charge,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.status){
                        $('.attribute_list').html(data.modal_view);  
                    }                                     
                },
                error: function (data) {
                    console.log(data);
                }
            });
        } else {
            $('.charge_value_error').text('Enter Charge Amount');
        }
    }
</script>
<script>    
    function pay_discount(){
        var discount = $('.discount_value').val();
        if(discount){
            $('.discount_value_error').text('');
            $.ajax({
                type: 'Post',
                url: "{{ route('pay.discount.value') }}",
                data: {
                        _token: CSRF_TOKEN,
                        discount: discount,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.status){
                        $('.attribute_list').html(data.modal_view);  
                    }                                     
                },
                error: function (data) {
                    console.log(data);
                }
            });
        } else {
            $('.discount_value_error').text('Enter Discount Amount');
        }
    }
</script>
<script>
    $(document).ready(function() {
       
        $('.pay_amount').on('keypress', function(e) {
          
            var phone = jQuery('.pay_amount').val();
            console.log(phone);
            var regex = new RegExp("^[0-9\b]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            // for 10 digit number only
            if (phone.length > 5) {
                e.preventDefault();
                return false;
            }
            
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });

   });
</script>
<script>
    function pay_Coupon() {
        var coupon_code = $('.coupon_value').val();
    
        if(coupon_code){
            $.ajax({
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },    
                    type: 'Post',
                    url: "{{ url('off-apply-coupon') }}",
                    data: {
                        coupon_code : coupon_code,
                    },
                    success: function (data) {
                            console.log(data);
                            if(data.status){
                                // $('.text-danger').text('');
                          
                                $('.attribute_list').html(data.modal_view);                               
                                $('.coupon_value_success').text(data.msg);
                            } else {
                                $('.coupon_value_error').text(data.msg);
                            }		                      
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
        }
    }
    </script>

    
@endsection



       