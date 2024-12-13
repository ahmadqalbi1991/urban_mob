@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Edit Coupon</h3>

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

                                <form action="{{ url('update/coupon') }}" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="{{$coupon->id}}">
                                    <!-- Modal body -->
                                    <div class="row">
                                        @csrf

                                        <div class="form-group col-lg-6">

                                            <label>Code <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="code" onkeypress="clsAlphaNoOnly(event)" onpaste="return false;" value="{{$coupon->code}}" placeholder="Code" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Type <span class="text-danger">*</span></label>

                                            <select class="form-control" name="type" onchange="coupontype(this.value)">
                                                <option value="">Select Type</option>
                                                <option value="Amt" {{$coupon->type=='Amt'?'selected':''}}>Amount</option>
                                                <option value="Per" {{$coupon->type=='Per'?'selected':''}}>Percentage</option>
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">
                                            @if($coupon->type=='Amt')
                                            <label><span class="typetext">Amount</span> <span class="text-danger">*</span></label>
                                            @else
                                            <label><span class="typetext">Percentage</span> <span class="text-danger">*</span></label>
                                            @endif

                                            <input type="text" class="form-control" name="amount" value="{{$coupon->amount}}" placeholder="" >

                                        </div>
                                        
                                        <div class="form-group col-lg-6">

                                            <label><span>Coupon Min Amount</span> <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="min_amount" value="{{$coupon->min_amount}}" placeholder="" >

                                        </div>

                                        <div class="form-group col-lg-6 coupon_max_amt">

                                            <label><span >Coupon Max Amount</span> <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="max_amount" value="{{$coupon->max_amount}}" placeholder="Coupon Max Amount" >

                                        </div>


                                        <div class="form-group col-lg-6">

                                            <label>Start Date </label>

                                            <input type="date" class="form-control" name="start_date" value="{{$coupon->start_date}}" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>End Date </label>

                                            <input type="date" class="form-control" name="end_date" value="{{$coupon->end_date}}" >

                                        </div>

                                    </div>

                                    <div class="text-center">
                                        
                                        <button type="submit" class="btn btn-success bg-grad-4">Update</button>
                                    
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
        <input type="hidden" class="typeval" value="{{$coupon->type}}">
@endsection      



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 
<script type="text/javascript">
      
$(document).ready(function (e) {
    if($('.typeval').val()=='Per'){
        $('.coupon_max_amt').show();
    } else {
        $('.coupon_max_amt').hide();
    }
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
       