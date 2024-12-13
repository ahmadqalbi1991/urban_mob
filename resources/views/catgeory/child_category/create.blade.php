@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Add Child Category</h3>

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

                                <form action="{{ url('child-category') }}" method="POST" enctype="multipart/form-data">

                                    <!-- Modal body -->
                                    <div class="row">
                                        @csrf

                                        <div class="form-group col-lg-6">

                                            <label>Name <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Child Category Name" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Sub Parent Category </label>

                                            <select class="form-control select2 select2-hidden-accessible" name="parent_id">
                                                <option value="">Select Category</option>
                                                @foreach($categorys as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Price <span class="text-danger">*</span></label>

                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">

                                                    <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                </div>

                                                <input type="number" class="form-control" name="price" value="{{old('price')}}" placeholder="Price" >

                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Icon <small>( 200x200 )</small></label>

                                            <input type="file" class="form-control" name="icon" id="image">

                                            <img id="preview-image-before-upload" height="100" class="mt-2"> 

                                        </div>

                                        <div class="form-group col-lg-2">

                                            <label>Status</label>

                                        </div>

                                        <div class="form-group col-lg-4">

                                            <label class="switch">

                                              <input type="checkbox" name="status" checked >

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
       