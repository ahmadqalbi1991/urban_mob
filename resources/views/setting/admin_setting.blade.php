@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Website Setting</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{route('brand')}}">

                            <button type="button" class="btn btn-rounded btn-primary ml-4">

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

                            <div class="card-header">
                                Admin Setting
                            </div>

                            <div class="card-body">

                                <form action="{{ route('home.setting.update') }}" method="POST" enctype="multipart/form-data">

                                    <!-- Modal body -->
                                    <div class="row">
                                        @csrf

                                        <div class="form-group col-lg-6">

                                            <label>Admin Logo <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="admin_logo" placeholder="" id="admin_logo">

                                        </div>

                                        <div class="form-group col-lg-4">

                                            @if($setting && $setting->admin_logo)
                                            <img src="{{ asset('/uploads/home/'.$setting->admin_logo) }}" id="pre_header_logo" height="100">                                            
                                            @else
                                            <img id="pre_header_logo" height="100">
                                            @endif   
                                                                                
                                        </div>

                                        <div class="form-group col-lg-2">
                                            
                                            @if($setting && $setting->admin_logo)
                                            <a href="{{ route('remove.admin_logo') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @endif   
                                                                                
                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Admin Side Logo <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="admin_side_logo" placeholder="" id="admin_side_logo">

                                        </div>

                                        <div class="form-group col-lg-4">

                                            @if($setting && $setting->admin_side_logo)
                                            <img src="{{ asset('/uploads/home/'.$setting->admin_side_logo) }}" id="pre_header_logo" height="100">                                            
                                            @else
                                            <img id="pre_header_logo" height="100">
                                            @endif   
                                                                                
                                        </div>

                                        <div class="form-group col-lg-2">
                                            
                                            @if($setting && $setting->admin_side_logo)
                                            <a href="{{ route('remove.admin_side_logo') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @endif   
                                                                                
                                        </div>

                                    </div>

                                    <div class="text-right">
                                        
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    
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
 
   
   $('#admin_logo').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#pre_header_logo').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});
 
</script> 



       