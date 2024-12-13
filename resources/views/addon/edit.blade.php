@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Edit Addon</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ url()->previous() }}">

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
                                <div class="text-left">
                                    <button class="btn btn-rounded bg-grad-4 mb-4" onclick="addMore()">Add More</button>
                                </div>
                                <input type="hidden" value="{{$addon->category_id}}" class="cat_id">
                                <form action="{{ route('addon.update',$addon->id) }}" method="POST" enctype="multipart/form-data">

                                    <!-- Modal body -->
                                    <div class="row">
                                        @csrf
                                        <input type="hidden" name="ser_attr_val_item_id" value="{{$addon->ser_attr_val_item_id}}"> 
                                        <input type="hidden" name="attribute_item_id" class="attribute_item_id" value="{{$addon->attribute_item_id}}">
                                        <input type="hidden" name="attribute_id" value="{{$addon->attribute_id}}" class="attribute_id">
                                        
                                        <div class="form-group col-lg-6 category-section">

                                            <label>Category </label>

                                            <select class="form-control select2 category_id" name="category_id" onchange="subCategory(this.value)">

                                               <option value="">Select Category</option>
                                                @foreach($category as $item)
                                                <option value="{{$item->id}}" {{$addon->category_id==$item->id?'selected':''}}>{{$item->name}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6 category-section">

                                            <label>Sub Category </label>

                                            <select class="form-control select2 sub_category" name="sub_category_id" onchange="childCategory(this.value)">

                                                <option value="">Select Sub Category</option>
                                                @foreach($sub_category as $item)
                                                <option value="{{$item->id}}" {{$addon->sub_category_id==$item->id?'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6 category-section">

                                            <label>Child Category </label>

                                            <select class="form-control select2 child_category" name="child_category_id" onchange="getCateAttr(this.value, 'child')">

                                               <option value="">Select Child Category</option>
                                               @foreach($child_category as $item)
                                                <option value="{{$item->id}}" {{$addon->child_category_id==$item->id?'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                               
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Attribute Item <span class="text-danger">*</span></label>

                                            <select class="form-control select2 attr_list" onchange="getAttr(this.value)" required>

                                               <option value="">Select Attribute Item</option>
                                                @foreach($datas as $val)
                                                <option value="{{$val->id}}" {{$val->attributeItem->id==$addon->attribute_item_id?'selected':''}}>{{$val->attributeItem?$val->attributeItem->value:''}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Name <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="name" value="{{$addon->name}}" placeholder="Addon Name" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Percentage <span class="text-danger">*</span></label>

                                            <input type="number" name="percentage" value="{{$addon->percentage}}" class="form-control" onchange="getPercentage(this.value)" placeholder="Percentage">
                                        
                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Original Price </label>
                                           
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">

                                                    <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                </div>

                                                <input type="hidden" name="orignal_price" class="orignal_price" value="{{$addon->orignal_price}}">
                                                <input type="number" class="form-control" value="{{$addon->orignal_price}}" placeholder="Original Price" readonly>

                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Discount Price </label>
                                           
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">

                                                    <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                </div>

                                                <input type="hidden" name="orignal_price" class="orignal_price" value="{{$addon->orignal_price}}">
                                                <input type="number" class="form-control price" name="value" value="{{$addon->value}}" placeholder="Price" readonly>

                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Short Description  </label>

                                            <textarea name="short_description" class="form-control" placeholder="Short Description">{{$addon->short_description}}</textarea>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>icon</label>

                                            <input type="file" class="form-control" name="icon" placeholder="" id="image">

                                        </div>
                                        <div class="form-group col-lg-6"></div>
                                        <div class="form-group col-lg-6">
                                            @if($addon->icon)
                                            <img src="{{ asset('/uploads/addon/'.$addon->icon) }}" id="preview-image-before-upload" height="100"> 
                                            @else
                                            <img id="preview-image-before-upload" height="100">
                                            @endif
                                        
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
        <input type="hidden" class="servid" value="{{$addon?$addon->service_id:''}}"> 
@endsection     

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 
<script type="text/javascript">
      
$(document).ready(function (e) {
 
   if($('.cat_id').val()){
        $('.category-section').show();
   } else {
        $('.category-section').hide();
   }

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
                $('.attribute_id').val(data.attribute_id);   
           }
       });
 }

 function getPercentage(per) {
     // alert(per);
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



       