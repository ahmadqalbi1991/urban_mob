@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Create Coupon</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{url('coupons')}}" class="btn btn-rounded bg-grad-4 ml-4">

                            <span class="btn-icon-left text-primary">

                                <i class="fa fa-arrow-left color-primary"></i> 

                            </span>Back

                        </a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <form action="{{ url('store/coupon') }}" method="POST" enctype="multipart/form-data">

                                    <!-- Modal body -->
                                    <div class="row">
                                        @csrf

                                        <div class="form-group col-lg-6">

                                            <label>Code <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control code" onkeypress="clsAlphaNoOnly(event)" onpaste="return false;" name="code" value="{{old('code')}}" placeholder="Code" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>User Limit <span class="text-danger">*</span></label>

                                            <input type="number" class="form-control" name="user_used" value="{{old('user_used')}}" placeholder="User Limit" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Type <span class="text-danger">*</span></label>

                                            <select class="form-control" name="type" onchange="coupontype(this.value)">
                                                <option value="">Select Type</option>
                                                <option value="Amt">Amount</option>
                                                <option value="Per">Percentage</option>
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label><span class="typetext">Amount</span> <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="amount" value="{{old('amount')}}" placeholder="" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label><span >Coupon Min Amount</span> <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="min_amount" value="{{old('min_amount')}}" placeholder="Coupon Min Amount" >

                                        </div>

                                        <div class="form-group col-lg-6 coupon_max_amt">

                                            <label><span >Coupon Max Amount</span> <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="max_amount" value="{{old('max_amount')}}" placeholder="Coupon Max Amount" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Start Date </label>

                                            <input type="date" class="form-control" name="start_date" value="{{old('start_date')}}" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>End Date </label>

                                            <input type="date" class="form-control" name="end_date" value="{{old('end_date')}}" >

                                        </div>

                                    </div>

                                    <div class="text-center">
                                        
                                        <button type="submit" class="btn btn-success bg-grad-4">Submit</button>
                                    
                                    </div>                                    
                                
                                </form>

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



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 
<script type="text/javascript">
      
$(document).ready(function (e) {

    $('.coupon_max_amt').hide();
    
   $('#preview-image-before-upload').hide();

   $('#image').change(function(){

    $('#preview-image-before-upload').show();
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#preview-image-before-upload').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });
   
});


 
</script> 
<script>

    function coupontype(argument) {
       
        if(argument=='Amt'){
            $('.typetext').text('Amount');
            $('.coupon_max_amt').hide();
        } else {
            $('.typetext').text('Percentage');
            $('.coupon_max_amt').show();
        }
    }
</script>


       