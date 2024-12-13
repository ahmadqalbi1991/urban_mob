@extends('layouts.dashboard')
<style>
    .submit-btn {
        width: 12%;
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

                            <h3 class="ml-1">Add New Service</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{url('service')}}">

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
                <form action="{{ url('service') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">                    

                        <div class="col-8">

                            <div class="card">

                                <div class="card-body">   

                                    <h5 class="card-title mb-4">Service Information</h5>                             

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Service Title <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Service Title" required>

                                        </div>

                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Service Category <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-lg-9 form-group">
                                           
                                            <select class="form-control select2 " name="parent_id" required>

                                               <option value="">Select Category</option>
                                                @foreach($categorys as $cate)
                                                <option value="{{$cate->id}}">{{$cate->name}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                    </div>                                 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Service Price</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">

                                                    <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                </div>

                                                <input type="number" class="form-control" name="price" value="{{old('price')}}" placeholder="Service Price">

                                            </div>

                                        </div>

                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Material Status </label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <label class="switch" onchange="material_status()">

                                              <input type="checkbox" name="material_status" class="material_status" checked>

                                              <span class="slider round"></span>

                                            </label>

                                        </div>

                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>UM Commission <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="text" class="form-control" name="um_commission" value="{{old('um_commission')}}" placeholder="UM Commissions" required>

                                        </div>

                                    </div> 

                                </div>

                            </div>

                            <div class="card material_status">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">Material Info</h5>     

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Material Price</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">

                                                    <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                </div>

                                                    <input type="number" class="form-control" name="material_price" value="{{old('price')}}" placeholder="Material Price">

                                            </div>

                                        </div>

                                    </div>  

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Recommended Msg</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="text" class="form-control" name="recommended" value="{{old('recommended')}}" placeholder="Recommended Message">

                                        </div>

                                    </div>  

                                </div>

                            </div>

                            <div class="card">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">Service Images</h5>     

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Thumbnail Image</label>
                                            <label>(390 px x 260 px)</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="file" class="form-control" name="image" id="image">

                                            <img id="preview-image-before-upload" height="100" class="mt-2"> 

                                        </div>

                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Banner</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="file" class="form-control" name="gallery" id="banner">
                                            <small>Web site header banner</small><br>
                                             <img id="preview-banner" height="100" class="mt-2"> 
                                        </div>

                                    </div> 

                                </div>

                            </div>

                            <div class="card">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">Service Description</h5> 

                                    <div class="row">
                                        
                                        <div class="col-lg-3">

                                            <label>Short Description</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <textarea class="form-control ckeditor" name="short_description" placeholder="Description"></textarea>

                                        </div>

                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-lg-3">

                                            <label>Description</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <textarea class="form-control ckeditor" name="description" placeholder="Description"></textarea>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="card">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">SEO Meta Tags</h5> 

                                    <div class="row">
                                        
                                        <div class="col-lg-3">

                                            <label>Meta Title</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                           <input type="text" name="meta_title" class="form-control" placeholder="Meta Title">

                                        </div>

                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-lg-3">

                                            <label>Description</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <textarea class="form-control" name="meta_description" placeholder="Description"></textarea>

                                        </div>

                                    </div>

                                    <div class="row d-none">
                                        
                                        <div class="col-lg-3">

                                            <label>Canonical</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                           <input type="text" name="canonical" class="form-control" placeholder="Canonical">

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>                    

                        <div class="col-4">

                            <div class="card">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">Active & Inactive</h5> 

                                    <div class="row">

                                        <div class="col-lg-6">

                                            <label>Status </label>

                                        </div>

                                        <div class="col-lg-6 form-group">

                                            <label class="switch">

                                              <input type="checkbox" name="status" checked>

                                              <span class="slider round"></span>

                                            </label>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="card">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">Featured</h5> 

                                    <div class="row">

                                        <div class="col-lg-6">

                                            <label>Featured </label>

                                        </div>

                                        <div class="col-lg-6 form-group">

                                            <label class="switch" onchange="featured()">

                                              <input type="checkbox" name="featured" id="featured_val">

                                              <span class="slider round"></span>

                                            </label>
                                            
                                        </div>
                                            
                                        <div class="col-lg-12 featured_section" style="display: none;">
                                            <div class="form-group">
                                                <label>Featured Banner (828px x 315px)</label>
                                                <input type="file" class="form-control" name="featured_banner" id="featured_banner">

                                                <img id="featured_banner-before-upload" height="100" class="mt-2"> 

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>                                                          
                    
                    </div> 

                    <div class="text-right mt-4">
                                        
                       <!--  <button type="submit" class="btn btn-warning">Save As Draft</button>
                        <button type="submit" class="btn btn-danger">Save & Unpublish</button>
                        <button type="submit" class="btn btn-success bg-grad-4">Save & Publish</button> -->
                        <button type="submit" class="btn btn-success bg-grad-4 submit-btn">Save</button>
                    
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

       $('#preview-banner').hide();

       $('#banner').change(function(){

        $('#preview-banner').show();
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#preview-banner').attr('src', e.target.result); 
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

<script>
    function material_status() {
        if ($('.material_status').prop('checked')==true){ 
            $('.material_status').show();
        } else {
            $('.material_status').hide();
        }
    }
</script>
       
