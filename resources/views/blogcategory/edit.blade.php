@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Edit Blog Category</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{url('blog-category')}}">

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

                                <form action="{{ route('blog-category.update',$blogcategory->id) }}" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Modal body -->
                                    <div class="row">
                                        @csrf

                                        <div class="form-group col-lg-6">

                                            <label>Name <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="name" value="{{$blogcategory->name}}" placeholder="Blog Category Name" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Logo</label>

                                            <input type="file" class="form-control" name="logo" placeholder="" id="image">

                                        </div>
                                        <div class="form-group col-lg-6"></div>
                                        <div class="form-group col-lg-6">
                                            @if($blogcategory->logo)
                                            <img id="preview-image-before-upload" src="{{ asset('/uploads/blog-category/'.$blogcategory->logo) }}" height="100">
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

@endsection     

 




       