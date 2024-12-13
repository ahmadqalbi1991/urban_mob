@extends('layouts.dashboard')

@section('content')
<style>
    .max-h-w {
        max-height: 100px;
        max-width: 100px;
    }
</style>

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Edit Child Category</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{url('child-category')}}">

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

                                <form action="{{url('child-category/'.$category->id)}}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}


                                    <!-- Modal body -->
                                    <div class="row">

                                        <div class="form-group col-lg-6">

                                            <label>Name <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="name" placeholder="Category Name" value="{{$category->name}}">

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Sub Parent Category </label>

                                            <select class="form-control select2 select2-hidden-accessible" name="parent_id">
                                                <option value="">Select Category</option>
                                                @foreach($categorys as $cate)
                                                <option value="{{$cate->id}}" {{$cate->id==$category->sub_category_id?'selected':''}}>{{$cate->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Price <span class="text-danger">*</span></label>                                            

                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">

                                                    <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                </div>

                                                <input type="number" class="form-control" name="price" value="{{$category->price}}" placeholder="Price">

                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Icon <small>( 200x200 )</small></label>

                                            <input type="file" class="form-control" name="icon" id="image">
                                            @if($category->icon)
                                            <img src="{{ asset('/uploads/child-category/'.$category->icon) }}" id="preview-image-before-upload" class="mt-2" height="100"> 
                                            @else 
                                            <img id="preview-image-before-upload" height="100" class="mt-2"> 
                                            @endif
                                        </div>

                                        <div class="form-group col-lg-2">

                                            <label>Status</label>

                                        </div>

                                        <div class="form-group col-lg-4">

                                            <label class="switch">

                                              <input type="checkbox" name="status" {{$category->status=='1'?'checked':''}} >

                                              <span class="slider round"></span>

                                            </label>

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
   
   $('#image').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#preview-image-before-upload').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });
   
});
 
</script> 


       