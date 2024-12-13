@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Edit Blog</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{url('blog')}}">

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

                                <form action="{{ route('blog.update',$blog->id) }}" method="POST" enctype="multipart/form-data">

                                    <!-- Modal body -->
                                    <div class="row">
                                        @csrf

                                        <div class="form-group col-lg-6">

                                            <label>Category<span class="text-danger">*</span></label>

                                            <select class="form-control select2" name="category_id">
                                                <option value="">Select Category</option>
                                                @foreach($blogcategory as $cat)
                                                <option value="{{$cat->id}}" {{$blog->category_id==$cat->id ? 'selected': ''}}>{{$cat->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Title <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="name" value="{{$blog->name}}" placeholder="Blog Name" >

                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label>Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control ckeditor" name="details" placeholder="Blog Details">{{$blog->details}}</textarea>
                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Image</label>

                                            <input type="file" class="form-control" name="image" placeholder="" id="image">

                                            <div class="mt-2">
                                                @if($blog->image)
                                                <img id="preview-image-before-upload" src="{{ asset('/uploads/blog/'.$blog->image) }}" height="100px">
                                                @else
                                                <img id="preview-image-before-upload" height="100px">
                                                @endif
                                            
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Banner</label>

                                            <input type="file" class="form-control" name="banner" placeholder="" id="image_second">

                                            <div class="mt-2">
                                                @if($blog->banner)
                                                <img id="preview-image-before-upload-second" src="{{ asset('/uploads/blog/'.$blog->banner) }}" height="100px">
                                                @else
                                                <img id="preview-image-before-upload-second" height="100px">
                                                @endif
                                            
                                            </div>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Status</label>

                                            <select class="form-control select2" name="status">
                                                <option value="">Select Status</option>
                                               <option value="1" {{$blog->status=='1'?'selected':''}}>Active</option>
                                               <option value="0" {{$blog->status=='0'?'selected':''}}>Inactive</option>
                                            </select>

                                        </div>

                                        <div class="form-group col-lg-6"></div>

                                        <div class="form-group col-lg-6">

                                            <label>Meta Title</label>

                                            <input type="text" class="form-control" name="meta_title" value="{{$blog->meta_title}}" placeholder="Meta Title" >

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label>Meta Keyword</label>

                                            <input type="text" class="form-control" name="meta_keyword" value="{{$blog->meta_keyword}}" placeholder="Meta Keyword" >

                                        </div>

                                        <div class="form-group col-lg-12">

                                            <label>Meta Description</label>

                                            <textarea class="form-control" name="meta_description" placeholder="Meta Description">{{$blog->meta_description}}</textarea>

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

<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>  

 




       