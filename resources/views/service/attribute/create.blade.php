@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Add Service Attribute</h3>

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
                <form action="{{ url('store/service/attribute') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">                    

                        <div class="col-12">

                            <div class="card">

                                <div class="card-body">   

                                    <h5 class="card-title mb-4">Service Information</h5>                             

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Service Title <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-lg-6 form-group">

                                            <input type="text" class="form-control" value="{{$service->name}}" placeholder="Service Title" readonly>
                                            <input type="hidden" name="service_id" value="{{$service->id}}">

                                        </div>

                                    </div>                                  

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Service Category <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-lg-6 form-group">

                                            <select class="form-control select2 " name="parent_id" onchange="subCategory(this.value)" required>

                                                <option value="">Select Category</option>
                                                @foreach($categorys as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Sub Category </label>

                                        </div>

                                        <div class="col-lg-6 form-group">

                                            <select class="form-control select2 sub_category" name="sub_category_id" onchange="childCategory(this.value)">

                                                <option value="">Select Sub Category</option>
                                                
                                            </select>

                                        </div>

                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Child Category</label>

                                        </div>

                                        <div class="col-lg-6 form-group">

                                            <select class="form-control select2 child_category" name="child_category_id">

                                                <option value="">Select Child Category</option>
                                                
                                            </select>

                                        </div>

                                    </div> 

                                </div>

                            </div>

                            <div class="card">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">Service Variation</h5> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Attribute</label>

                                        </div>

                                        <div class="col-lg-6 form-group">

                                            <select class="form-control select2" onchange="selectAttrVal(this.value)">

                                                <option value="">Select Attribute</option>
                                                @foreach($attribute as $attr)
                                                <option value="{{$attr->id}}">{{$attr->name}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <span class="attr_list"></span>                                    

                                </div>

                            </div>

                        </div>                                                         
                    
                    </div> 

                    <div class="text-right mt-4">
                                        
                        <button type="submit" class="btn btn-warning">Submit</button>
                    
                    </div> 

                </form>

            </div>

        </div>


        <!--**********************************

            Content body end

        ***********************************-->

@endsection      



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
 
<script type="text/javascript">
      
    $(document).ready(function (e) {

       $('.attrvalue').hide();

       $('#preview-image-before-upload').hide();

       $('#image').change(function(){

        $('#preview-image-before-upload').show();
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#preview-image-before-upload').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

       $('#featured_banner-before-upload').hide();

       $('#featured_banner').change(function(){

        $('#featured_banner-before-upload').show();
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#featured_banner-before-upload').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });
       
    });
    

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
           }
       });
    } 

     function selectAttrVal(att_id) {
        var attrval_list_cls = '.attrval_list'+att_id;
         $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('get.attr_val') }}',
            data:{
               attribute_id: att_id
            },
            success: function(res) {
                 var obj = JSON.parse(res)
                
                $('.attr_list').append(`<div class="row mb-2">

                                        <div class="col-lg-3">

                                            <label><span>${obj.atrl}</span> Attributes <span class="text-danger">*</span></label>
                                            <input type="hidden" name="attribute_id[]" value="${att_id}">

                                        </div>

                                        <div class="col-lg-9 row attrval_list${att_id}">

                                        </div>                                            

                                    </div>`);
                $('.attrvalue').show();
               ;
                $(attrval_list_cls).append(obj.html);
           }
       });
     }
</script> 

<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
        
    });
</script>

<script>

    function featured() {
        
        if($('#featured_val').prop('checked')==true){
            $('.featured_section').css("display", "block");
        } else {
            $('.featured_section').css("display", "none");
        }
    }
</script>
       
