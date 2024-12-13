@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-8 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Add Addon -> {{$cat_name->value}}</h3>

                        </div>

                    </div>

                    <div class="col-sm-4 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{url()->previous()}}">

                            <button type="button" class="btn btn-rounded bg-grad-4 ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-arrow-left color-primary"></i> 

                                </span>Back

                            </button>

                        </a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">
                               
                                <form action="{{ route('addon.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Modal body -->
                                    <div class="row">
                                        <input type="hidden" name="service_id" class="service_id servid" value="{{$service_id}}"> 
                                        <input type="hidden" name="attribute_item_id" class="attribute_item_id" value="{{$attr_item}}">
                                        <input type="hidden" name="attribute_id" class="attribute_id" value="{{$attribute_id}}">
                                        <input type="hidden" name="belong_attr_item_id" value="{{$attr_item}}">

                                        <div class="form-group col-lg-6">

                                            <label>Category </label>

                                            <select class="form-control select2 category_id" name="category_id" onchange="subCategory(this.value)">

                                               <option value="">Select Category</option>
                                                @foreach($category as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Sub Category </label>

                                            <select class="form-control select2 sub_category" name="sub_category_id" onchange="childCategory(this.value)">

                                               <option value="">Select Sub Category</option>
                                               
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Child Category </label>

                                            <select class="form-control select2 child_category" name="child_category_id" onchange="getCateAttr(this.value, 'child')">

                                               <option value="">Select Child Category</option>
                                               
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Attribute Item <span class="text-danger">*</span></label>

                                            <select class="form-control select2 attr_list" onchange="getAttr(this.value)" required>

                                               <option value="">Select Attribute Item</option>
                                               
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Name <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Addon Name" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Percentage <span class="text-danger">*</span></label>

                                            <input type="number" name="percentage" class="form-control" onchange="getPercentage(this.value)" placeholder="Percentage">

                                        </div>                                       

                                        <div class="form-group col-lg-6">

                                            <label>Original Price <span class="text-danger">*</span></label>
                                            
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">

                                                    <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                </div>
                                                <input type="text" class="form-control orignal_price" value="{{old('value')}}" placeholder="Price" readonly>

                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Discount Price <span class="text-danger">*</span></label>
                                            
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">

                                                    <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                </div>
                                                <input type="hidden" name="orignal_price" class="orignal_price">
                                                <input type="number" class="form-control price" name="value" value="{{old('value')}}" placeholder="Price" readonly>

                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Short Description</label>

                                            <textarea name="short_description" class="form-control" placeholder="Short Description">{{old('short_description')}}</textarea>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Icon</label>

                                            <input type="file" class="form-control" name="icon" value="{{old('icon')}}" placeholder="" id="image">

                                        </div>
                                        <div class="form-group col-lg-6"></div>

                                        <div class="form-group col-lg-6">
                                            
                                            <img id="preview-image-before-upload" style="max-height: 100px;">
                                        
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
 
    $('.category-section').hide();

   $('#image').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#preview-image-before-upload').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });
   
});
 

 function getAttr(argument) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('get.service_atr') }}',
            data:{
               id: argument
            },
            success: function(data) {
                $('.attribute_item_id').val(data.attribute_item_id);
                $('.price').val(data.attribute_price);
                $('.orignal_price').val(data.attribute_price);
                $('.service_id').val(data.service_id);                
                $('.attribute_id').val(data.attribute_id);                
           }
       });
 }

 function getPercentage(per) {
    var per = parseInt(per);
    var price = parseInt($('.orignal_price').val());
    var per_amt = (price * per) / 100;
    $('.price').val(price-per_amt);
    $('.orignal_price').val(price);

 }

 function addMore(argument) {
     $('.category-section').show();
 }

 function getCateAttr(cat_id, posi) {
    $('.attr_list').text('');
     $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:"POST",
        url:'{{ route('get.cat_atr') }}',
        data:{
           cat_id: cat_id,
           posi: posi,
           pat_id: $('.category_id').val(),
           serv_id: $('.servid').val(),
        },
        success: function(data) {
          
            $('.attr_list').html(data); 
       }
   });
 }

 function subCategory(cat_id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('get.sub_category') }}',
            data:{
               category_id: cat_id
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('.sub_category').html(obj);
                getCateAttr(cat_id, 'main');
           }
       });
    }


    function childCategory(cat_id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('get.child_category') }}',
            data:{
               category_id: cat_id
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('.child_category').html(obj);
                getCateAttr(cat_id, 'sub');
           }
       });
    } 
</script> 



       