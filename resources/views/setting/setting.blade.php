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
                                Home Setting
                            </div>

                            <div class="card-body">

                                <form action="{{ route('home.setting.update') }}" method="POST" enctype="multipart/form-data">

                                    <!-- Modal body -->
                                    <div class="row">
                                        @csrf

                                        <div class="form-group col-lg-6">

                                            <label>Header Logo <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="header_logo" placeholder="" id="header_logo">

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Footer Logo <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="footer_logo" placeholder="" id="footer_logo">

                                        </div>

                                        <div class="form-group col-lg-6">
                                            @if($setting && $setting->header_logo)
                                            <img src="{{ asset('/uploads/home/'.$setting->header_logo) }}" id="pre_header_logo" height="100"> <br>
                                            <a href="{{ route('remove.header_logo') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @else
                                            <img id="pre_header_logo" height="100">
                                            @endif   
                                                                                
                                        </div>

                                        <div class="form-group col-lg-6">
                                            @if($setting && $setting->footer_logo)
                                            <img src="{{ asset('/uploads/home/'.$setting->footer_logo) }}" id="pre_footer_logo" height="100"> <br>
                                            <a href="{{ route('remove.footer_logo') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @else
                                            <img id="pre_footer_logo" height="100">
                                            @endif                                        
                                        </div>

                                    </div>

                                    <div class="text-right">
                                        
                                        <button type="submit" class="btn btn-success">Update</button>
                                    
                                    </div>                                    
                                
                                </form>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">
                                Home Slider <b>(Max 3)</b>
                            </div>

                            <div class="card-body">

                                <form action="{{ route('slider.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                
                                    <div class="row">

                                        <div class="form-group col-lg-6">

                                            <label>Slider & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="first_slider" placeholder="" id="first_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->first_slider)
                                                <img src="{{ asset('/uploads/slider/'.$slider->first_slider) }}" id="pre_first_slider" height="100"> <br>
                                                <a href="{{ route('remove.first_slider','one') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="pre_first_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">
                                            
                                            <input type="text" class="form-control mt-29" name="first_title" value="{{$slider->first_title}}" placeholder="Title">
                                            
                                            <input type="text" class="form-control mt-2" name="first_description" value="{{$slider->first_description}}" placeholder="Sub Title">
                                            
                                            <input type="text" class="form-control mt-2" name="first_link" value="{{$slider->first_link}}" placeholder="Link">
                                        
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="form-group col-lg-6">

                                            <label>Slider & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="second_slider" placeholder="" id="second_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->second_slider)
                                                <img src="{{ asset('/uploads/slider/'.$slider->second_slider) }}" id="pre_second_slider" height="100"> <br>
                                                <a href="{{ route('remove.first_slider','two') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="pre_second_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="second_title" value="{{$slider->second_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="second_description" value="{{$slider->second_description}}" placeholder="Sub Title">

                                            <input type="text" class="form-control mt-2" name="second_link" value="{{$slider->second_link}}" placeholder="Link">

                                        </div>                                       

                                    </div>

                                    <div class="row">
                                        
                                        <div class="form-group col-lg-6">

                                            <label>Slider & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="third_slider" placeholder="" id="third_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->third_slider)
                                                <img src="{{ asset('/uploads/slider/'.$slider->third_slider) }}" id="pre_third_slider" height="100"> <br>
                                                <a href="{{ route('remove.first_slider','three') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="pre_third_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="third_title" value="{{$slider->third_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="third_description" value="{{$slider->third_description}}" placeholder="Sub Title">

                                            <input type="text" class="form-control mt-2" name="third_link" value="{{$slider->third_link}}" placeholder="Link">

                                        </div>

                                    </div>

                                    <div class="text-right">
                                        
                                        <button type="submit" class="btn btn-success">Update</button>
                                    
                                    </div>                                    
                                
                                </form>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">
                               App Home Slider <b>(Max 3)</b> (1250 px x 300 px)
                            </div>

                            <div class="card-body">

                                <form action="{{ route('app.slider.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                
                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Slider & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="app_first_slider" placeholder="" id="app_first_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->app_first_slider)
                                                <img src="{{ asset('/uploads/slider/'.$slider->first_slider) }}" id="app_pre_first_slider" height="100"> <br>
                                                <a href="{{ route('remove.app_slider','one') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="app_pre_first_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="app_first_title" value="{{$slider->app_first_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="app_first_description" value="{{$slider->app_first_description}}" placeholder="Sub Title">

                                            <input type="text" class="form-control mt-2" name="app_first_link" value="{{$slider->app_first_link}}" placeholder="Link">

                                        </div>
                                    </div>

                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Slider & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="app_second_slider" placeholder="" id="app_second_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->app_second_slider)
                                                <img src="{{ asset('/uploads/slider/'.$slider->app_second_slider) }}" id="app_pre_second_slider" height="100">  <br>
                                                <a href="{{ route('remove.app_slider','two') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="app_pre_second_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="app_second_title" value="{{$slider->app_second_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="app_second_description" value="{{$slider->app_second_description}}" placeholder="Sub Title">

                                            <input type="text" class="form-control mt-2" name="app_second_link" value="{{$slider->app_second_link}}" placeholder="Link">

                                        </div>

                                    </div>

                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Slider & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="app_third_slider" placeholder="" id="app_third_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->app_third_slider)
                                                <img src="{{ asset('/uploads/slider/'.$slider->app_third_slider) }}" id="pre_third_slider" height="100">  <br>
                                                <a href="{{ route('remove.app_slider','three') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="pre_third_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="app_third_title" value="{{$slider->app_third_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="app_third_description" value="{{$slider->app_third_description}}" placeholder="Sub Title">

                                            <input type="text" class="form-control mt-2" name="app_third_link" value="{{$slider->app_third_link}}" placeholder="Link">

                                        </div>

                                    </div>

                                    <div class="text-right">
                                        
                                        <button type="submit" class="btn btn-success">Update</button>
                                    
                                    </div>                                    
                                
                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">
                               Sign-up Customer Onboarding Image <b>(Max 4)</b> (326 px x 509 px)
                            </div>

                            <div class="card-body">

                                <form action="{{ route('app.signup.slider.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                
                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Banner & Title <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="app_sign_first_slider" placeholder="" id="app_sign_first_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->app_sign_first_slider)
                                                <img src="{{ asset('/uploads/signup-slider/'.$slider->app_sign_first_slider) }}" id="app_sign_pre_first_slider" height="100"> <br>
                                                <a href="{{ route('remove.app_sign','one') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="app_sign_pre_first_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="app_sign_first_title" value="{{$slider->app_sign_first_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="app_sign_first_link" value="{{$slider->app_sign_first_link}}" placeholder="Short Description">

                                        </div>
                                    </div>

                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Banner & Title <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="app_sign_second_slider" placeholder="" id="app_sign_second_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->app_sign_second_slider)
                                                <img src="{{ asset('/uploads/signup-slider/'.$slider->app_sign_second_slider) }}" id="app_sign_pre_second_slider" height="100">  <br>
                                                <a href="{{ route('remove.app_sign','second') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="app_sign_pre_second_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="app_sign_second_title" value="{{$slider->app_sign_second_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="app_sign_second_link" value="{{$slider->app_sign_second_link}}" placeholder="Short Description">

                                        </div>

                                    </div>

                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Banner & Title <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="app_sign_third_slider" placeholder="" id="app_sign_third_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->app_sign_third_slider)
                                                <img src="{{ asset('/uploads/signup-slider/'.$slider->app_sign_third_slider) }}" id="app_sign_pre_third_slider" height="100">  <br>
                                                <a href="{{ route('remove.app_sign','three') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="app_sign_pre_third_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="app_sign_third_title" value="{{$slider->app_sign_third_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="app_sign_third_link" value="{{$slider->app_sign_third_link}}" placeholder="Short Description">

                                        </div>

                                    </div>

                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Banner & Title <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="app_sign_for_slider" placeholder="" id="app_sign_for_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->app_sign_for_slider)
                                                <img src="{{ asset('/uploads/signup-slider/'.$slider->app_sign_for_slider) }}" id="app_sign_pre_for_slider" height="100">  <br>
                                                <a href="{{ route('remove.app_sign','for') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="app_sign_pre_for_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="app_sign_for_title" value="{{$slider->app_sign_for_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="app_sign_for_link" value="{{$slider->app_sign_for_link}}" placeholder="Short Description">

                                        </div>

                                    </div>

                                    <div class="text-right">
                                        
                                        <button type="submit" class="btn btn-success">Update</button>
                                    
                                    </div>                                    
                                
                                </form>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">
                               Sign-up Vendor Onboarding Image <b>(Max 4)</b>  (326 px x 509 px)
                            </div>

                            <div class="card-body">

                                <form action="{{ route('vendor.app.signup.slider.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                
                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Banner & Title <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="vendor_app_sign_first_slider" placeholder="" id="vendor_app_sign_first_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->vendor_app_sign_first_slider)
                                                <img src="{{ asset('/uploads/signup-slider/'.$slider->vendor_app_sign_first_slider) }}" id="app_sign_pre_first_slider" height="100"> <br>
                                                <a href="{{ route('vendor.remove.app_sign','one') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="vendor_app_sign_pre_first_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="vendor_app_sign_first_title" value="{{$slider->vendor_app_sign_first_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="vendor_app_sign_first_link" value="{{$slider->vendor_app_sign_first_link}}" placeholder="Short Description">

                                        </div>
                                    </div>

                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Banner & Title <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="vendor_app_sign_second_slider" placeholder="" id="vendor_app_sign_second_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->vendor_app_sign_second_slider)
                                                <img src="{{ asset('/uploads/signup-slider/'.$slider->vendor_app_sign_second_slider) }}" id="vendor_app_sign_pre_second_slider" height="100">  <br>
                                                <a href="{{ route('vendor.remove.app_sign','second') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="vendor_app_sign_pre_second_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="vendor_app_sign_second_title" value="{{$slider->vendor_app_sign_second_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="vendor_app_sign_second_link" value="{{$slider->vendor_app_sign_second_link}}" placeholder="Short Description">

                                        </div>

                                    </div>

                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Banner & Title <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="vendor_app_sign_third_slider" placeholder="" id="vendor_app_sign_third_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->vendor_app_sign_third_slider)
                                                <img src="{{ asset('/uploads/signup-slider/'.$slider->vendor_app_sign_third_slider) }}" id="vendor_app_sign_pre_third_slider" height="100">  <br>
                                                <a href="{{ route('vendor.remove.app_sign','three') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="vendor_app_sign_pre_third_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="vendor_app_sign_third_title" value="{{$slider->vendor_app_sign_third_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="vendor_app_sign_third_link" value="{{$slider->vendor_app_sign_third_link}}" placeholder="Short Description">

                                        </div>

                                    </div>

                                    <div class="row">
                                        

                                        <div class="form-group col-lg-6">

                                            <label>Banner & Title <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="vendor_app_sign_for_slider" placeholder="" id="vendor_app_sign_for_slider">

                                            <div class="mt-2">
                                                @if($slider && $slider->vendor_app_sign_for_slider)
                                                <img src="{{ asset('/uploads/signup-slider/'.$slider->vendor_app_sign_for_slider) }}" id="vendor_app_sign_pre_for_slider" height="100">  <br>
                                                <a href="{{ route('vendor.remove.app_sign','for') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <img id="vendor_app_sign_pre_for_slider" height="100">
                                                @endif                                        
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <input type="text" class="form-control mt-29" name="vendor_app_sign_for_title" value="{{$slider->vendor_app_sign_for_title}}" placeholder="Title">

                                            <input type="text" class="form-control mt-2" name="vendor_app_sign_for_link" value="{{$slider->vendor_app_sign_for_link}}" placeholder="Short Description">

                                        </div>

                                    </div>

                                    <div class="text-right">
                                        
                                        <button type="submit" class="btn btn-success">Update</button>
                                    
                                    </div>                                    
                                
                                </form>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">
                              Website Home Banner <b>(Max 3)</b> (1250 px x 300 px)
                            </div>

                            <div class="card-body">

                                <form action="{{ route('home.banner.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                
                                    <div class="row">                                        

                                        <div class="form-group col-lg-4">

                                            <label>Banner & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="home_banner" placeholder="" id="first_home_banner">

                                        </div>

                                        <div class="form-group col-lg-3 mt-2">

                                            <input type="text" class="form-control mt-10 mt-4" name="home_link" value="{{$slider->home_banner_link}}" placeholder="Link">

                                        </div>

                                        <div class="form-group col-lg-4">
                                            @if($slider && $slider->home_banner)
                                            <img src="{{ asset('/uploads/banner/'.$slider->home_banner) }}" id="pre_first_home_banner" height="100">
                                            @else
                                            <img id="pre_first_home_banner" height="100">
                                            @endif                                        
                                        </div>

                                        <div class="form-group col-lg-1">
                                            @if($slider && $slider->home_banner)
                                            <a href="{{ route('remove.home_slider','one') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="row">                                        

                                        <div class="form-group col-lg-4">

                                            <label>Banner & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="second_home_banner" placeholder="" id="second_home_banner">

                                        </div>

                                        <div class="form-group col-lg-3 mt-2">

                                            <input type="text" class="form-control mt-10" name="second_home_link" value="{{$slider->second_home_banner_link}}" placeholder="Link">

                                        </div>

                                        <div class="form-group col-lg-4">
                                            @if($slider && $slider->second_home_banner)
                                            <img src="{{ asset('/uploads/banner/'.$slider->second_home_banner) }}" id="pre_second_home_banner" height="100">                                             
                                            @else
                                            <img id="pre_second_home_banner" height="100">
                                            @endif                                        
                                        </div>

                                        <div class="form-group col-lg-1 mt-4">
                                            @if($slider && $slider->second_home_banner)
                                            <a href="{{ route('remove.home_slider','two') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @endif
                                        </div>

                                    </div>


                                    <div class="row">                                        

                                        <div class="form-group col-lg-4">

                                            <label>Banner & Links <span class="text-danger">*</span></label>

                                            <input type="file" class="form-control" name="third_home_banner" placeholder="" id="third_home_banner">

                                        </div>

                                        <div class="form-group col-lg-3 mt-2">

                                            <input type="text" class="form-control mt-10" name="third_home_link" value="{{$slider->third_home_banner_link}}" placeholder="Link">

                                        </div>

                                        <div class="form-group col-lg-4">
                                            @if($slider && $slider->third_home_banner)
                                            <img src="{{ asset('/uploads/banner/'.$slider->third_home_banner) }}" id="pre_third_home_banner" height="100"> 
                                            @else
                                            <img id="pre_third_home_banner" height="100">
                                            @endif                                        
                                        </div>

                                        <div class="form-group col-lg-1 mt-4">
                                            @if($slider && $slider->third_home_banner)
                                             <a href="{{ route('remove.home_slider','three') }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger mt-2"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
 
   
   $('#header_logo').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#pre_header_logo').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});

$(document).ready(function (e) {

   $('#footer_logo').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#pre_footer_logo').attr('src', e.target.result); 
    }
    
    reader.readAsDataURL(this.files[0]); 
   
   });

});

$(document).ready(function (e) {

   $('#first_slider').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#pre_first_slider').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});

$(document).ready(function (e) {

   $('#second_slider').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#pre_second_slider').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});

   $(document).ready(function (e) {

       $('#third_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#pre_third_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });


$(document).ready(function (e) {

   $('#app_first_slider').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#app_pre_first_slider').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});

$(document).ready(function (e) {

   $('#app_second_slider').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#app_pre_second_slider').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});

   $(document).ready(function (e) {

       $('#app_third_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#app_pre_third_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });


   $(document).ready(function (e) {

   $('#first_home_banner').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#pre_first_home_banner').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});

$(document).ready(function (e) {

   $('#second_home_banner').change(function(){
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#pre_second_home_banner').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});

   $(document).ready(function (e) {

       $('#third_home_banner').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#pre_third_home_banner').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });



    $(document).ready(function (e) {

       $('#app_sign_first_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#app_sign_pre_first_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });


    $(document).ready(function (e) {

       $('#app_sign_second_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#app_sign_pre_second_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });


    $(document).ready(function (e) {

       $('#app_sign_third_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#app_sign_pre_third_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });


    $(document).ready(function (e) {

       $('#app_sign_for_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#app_sign_pre_for_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });
 
</script>

<script>
    $(document).ready(function (e) {

       $('#vendor_app_sign_first_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#vendor_app_sign_pre_first_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });


    $(document).ready(function (e) {

       $('#vendor_app_sign_second_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#vendor_app_sign_pre_second_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });


    $(document).ready(function (e) {

       $('#vendor_app_sign_third_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#vendor_app_sign_pre_third_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });


    $(document).ready(function (e) {

       $('#vendor_app_sign_for_slider').change(function(){
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#vendor_app_sign_pre_for_slider').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

    });
</script> 



       