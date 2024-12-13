@extends('layouts.dashboard')

@section('content')
<style>
    .dropdown-check-list {
  display: inline-block;
  width: 100%;
}

.dropdown-check-list .anchor {
  position: relative;
  cursor: pointer;
  display: inline-block;
  padding: 5px 50px 5px 10px;
  border: 1px solid #ccc;
  width: 100%;
}

.dropdown-check-list .anchor:after {
  position: absolute;
  content: "";
  border-left: 2px solid black;
  border-top: 2px solid black;
  padding: 5px;
  right: 10px;
  top: 20%;
  -moz-transform: rotate(-135deg);
  -ms-transform: rotate(-135deg);
  -o-transform: rotate(-135deg);
  -webkit-transform: rotate(-135deg);
  transform: rotate(-135deg);
}

.dropdown-check-list .anchor:active:after {
  right: 8px;
  top: 21%;
}

.dropdown-check-list ul.items {
  padding: 12px;
  display: none;
  margin: 0;
  border: 1px solid #ccc;
  border-top: none;
}

.dropdown-check-list ul.items li {
  list-style: none;
}

.dropdown-check-list.visible .anchor {
  color: #0094ff;
}

.dropdown-check-list.visible .items {
  display: block;
}

.items {
    height: 150px;
    overflow: scroll;
}
</style>
<style>
    .submit-btn {
        width: 12%;
    }
</style>

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Edit Service</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{url('service')}}" class="btn btn-rounded bg-grad-4 ml-4">

                            <span class="btn-icon-left text-primary">

                                <i class="fa fa-arrow-left color-primary"></i> 

                            </span>Back

                        </a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')
                <form action="{{url('service/'.$service->id)}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

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

                                            <input type="text" class="form-control" name="name" value="{{$service->name}}" placeholder="Service Title" required>
                                            <input type="hidden" value="{{$service->id}}" class="service_id">
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
                                                <option value="{{$cate->id}}" {{$service->parent_id==$cate->id?'selected':''}}>{{$cate->name}}</option>
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

                                                <input type="number" class="form-control" name="price" value="{{$service->price}}" placeholder="Service Price">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Material Status </label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                              <input type="hidden" class="m_status" value="{{$service->material_status}}">
                                            <label class="switch" onchange="material_status()">

                                              <input type="checkbox" name="material_status" class="material_status" {{$service->material_status=='True'?'checked':''}}>
                                              <span class="slider round"></span>

                                            </label>

                                        </div>

                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>UM Commission <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="text" class="form-control" name="um_commission" value="{{$service->um_commission}}" placeholder="UM Commissions" required>

                                        </div>

                                    </div>  

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>Info</label>
                                        </div>

                                        <div class="col-lg-9 form-group">
                                            <textarea class="form-control ckeditor" name="info" placeholder="Info">{{$service->info}}</textarea>
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

                                                <input type="number" class="form-control" name="material_price" value="{{$service->material_price}}" placeholder="Material Price">

                                            </div>

                                        </div>

                                    </div>  

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Recommended Msg</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="text" class="form-control" name="recommended" value="{{$service->recommended}}" placeholder="Recommended Message">

                                        </div>

                                    </div>   

                                </div>

                            </div>

                            <div class="card">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">Service Images</h5>     

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Thumbnail Image </label>
                                             <label>(390 px x 260 px)</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="file" class="form-control" name="image" id="image">

                                            @if($service->thumbnail_img)
                                            <img id="preview-image-before-upload" src="{{url('/uploads/service/'.$service->thumbnail_img)}}" height="100" class="mt-2"> 
                                            @else
                                            <img id="preview-image-before-upload" height="100" class="mt-2"> 
                                            @endif

                                        </div>

                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Website header banner (1200X800)</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <input type="file" class="form-control" name="gallery">
                                             

                                            <div class="row">
                                            @foreach($gallery as $glly)
                                            
                                                <div class="col-lg-3 gallery{{$glly->id}} mt-2 position-relative">
                                                    <a href="javascript:" onclick="removeGallery({{$glly->id}})" class="text-danger" style="margin: 0; padding: 8px 9px; position: absolute; right: 17px; top: 10px; font-size: 12px; background: red; color: #fff !important; border-radius: 5px;"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    <img src="{{url('/uploads/service/gallery/'.$glly->photos)}}" height="120" class="mt-2 w-100"> 
                                                </div>
                                             
                                            @endforeach
                                            </div>

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

                                            <textarea class="form-control ckeditor" name="short_description" placeholder="Description">{{$service->short_description}}</textarea>

                                        </div>

                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-lg-3">

                                            <label>Description</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <textarea class="form-control ckeditor" name="description" placeholder="Description">{{$service->description}}</textarea>

                                        </div>

                                    </div>


                                </div>

                            </div>


                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Video</h5> 

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>Video</label>
                                            <label>(MP4, Max Size: 50MB)</label>
                                        </div>

                                        <div class="col-lg-9 form-group">
                                            <input type="file" class="form-control" name="video" id="video" accept="video/mp4" />

                                            @if($service->video)
                                            <div class="mt-2 position-relative">
                                                <video width="320" height="240" style="width:100%; height: auto" controls>
                                                    <source src="{{ $service->video }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <button type="button" class="btn btn-danger" style="margin: 0; padding: 10px 12px; position: absolute; right: 10px; top: 10px; font-size: 18px;" onclick="deleteVideo()"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                <!--<button type="button" class="btn btn-danger mt-2" onclick="deleteVideo()">Delete Video</button>-->
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>Video Title</label>
                                        </div>

                                        <div class="col-lg-9 form-group">
                                            <input type="text" class="form-control" name="video_title" value="{{$service->video_title}}" placeholder="Video Title">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>Video Description</label>
                                        </div>

                                        <div class="col-lg-9 form-group">
                                            <textarea class="form-control ckeditor" name="video_description" placeholder="Video Description">{{$service->video_description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function deleteVideo() {
                                    if (confirm('Are you sure you want to delete this video?')) {
                                        $.ajax({
                                            url: "{{ route('service.deleteVideo', $service->id) }}",
                                            type: 'DELETE',
                                            data: {
                                                _token: '{{ csrf_token() }}'
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    alert('Video deleted successfully!');
                                                    location.reload(); // Reload page to reflect changes
                                                } else {
                                                    alert('Failed to delete video. Please try again.');
                                                }
                                            },
                                            error: function() {
                                                alert('An error occurred. Please try again.');
                                            }
                                        });
                                    }
                                }
                            </script>



                            <div class="card">

                                <div class="card-body">

                                    <h5 class="card-title mb-4">SEO Meta Tags</h5> 

                                    <div class="row">
                                        
                                        <div class="col-lg-3">

                                            <label>Meta Title</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                           <input type="text" name="meta_title" class="form-control" value="{{$service->meta_title}}" placeholder="Meta Title">

                                        </div>

                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-lg-3">

                                            <label>Description</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                            <textarea class="form-control" name="meta_description" placeholder="Description">{{$service->meta_description}}</textarea>

                                        </div>

                                    </div>

                                    <div class="row d-none">
                                        
                                        <div class="col-lg-3">

                                            <label>Canonical</label>

                                        </div>

                                        <div class="col-lg-9 form-group">

                                           <input type="text" name="canonical" class="form-control" value="{{$service->canonical}}" placeholder="Canonical">

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

                                              <input type="checkbox" name="status" {{$service->status=='1'?'checked':''}} >

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

                                              <input type="checkbox" name="featured" id="featured_val" {{$service->featured=='1'?'checked':''}} >

                                              <span class="slider round"></span>

                                            </label>
                                            
                                        </div>
                                            
                                        <div class="col-lg-12 featured_section" {{$service->featured=='1'?'':'style="display: none;'}} ">
                                            <div class="form-group">
                                                <label>Featured Banner (828px x 315px)</label>
                                                <input type="file" class="form-control" name="featured_banner" id="featured_banner">

                                                <img id="featured_banner-before-upload" src="{{url('/uploads/service/featured_banner/'.$service->featured_banner)}}" height="100" class="mt-2"> 

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>                                                          
                    
                    </div> 

                    <div class="text-right mt-4">
                                        
                        <!-- <button type="submit" class="btn btn-warning">Save As Draft</button>
                        <button type="submit" class="btn btn-danger">Save & Unpublish</button>
                        <button type="submit" class="btn btn-success bg-grad-4">Save & Publish</button> -->
                        <button type="submit" class="btn btn-success bg-grad-4 submit-btn">Update</button>
                    
                    </div> 

                </form>

            </div>

        </div>


        <!--**********************************

            Content body end

        ***********************************-->
        <script>
            var checkList = document.getElementById('list1');
            
            checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
              if (checkList.classList.contains('visible'))
                checkList.classList.remove('visible');
              else
                checkList.classList.add('visible');
            }
        </script>

@endsection      



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>

<!-- <script src="//cdn.ckeditor.com/4.25.0-lts/full/ckeditor.js"></script> -->
 
<script type="text/javascript">
      
    $(document).ready(function (e) {

       $('.attrvalue').hide();

       // $('#preview-image-before-upload').hide();

       $('#image').change(function(){

        $('#preview-image-before-upload').show();
                
        let reader = new FileReader();
     
        reader.onload = (e) => { 
     
          $('#preview-image-before-upload').attr('src', e.target.result); 
        }
     
        reader.readAsDataURL(this.files[0]); 
       
       });

       // $('#featured_banner-before-upload').hide();

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
       
        var a_id = $('#attrVal').val();
        // var att_id = a_id.slice(-1)[0]
        // var att_id = a_id[0]
        var attrval_list_cls = '.attrval_list'+att_id;
        // let newAtrId = '';
        // var atrIds = <?php echo json_encode($attr_ids); ?>;
        // $('#attrVal option:selected').each(function() {
         
        //     atrIds.forEach((element, index) => {
        //       if($(this).val()==element){

        //       } else {
        //         newAtrId = $(this).val();
        //       }
        //     });
        // });
        var atrid = '#atrid'+att_id;

        if ($(atrid).prop('checked')==true){ 
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
                        
                        $('.attr_list').append(`<div class="row mb-2 attri${att_id}">

                                                <div class="col-lg-3">

                                                    <label><span>${obj.atrl}</span> Attributes <span class="text-danger">*</span></label>
                                                    

                                                </div>

                                                <div class="col-lg-9 row attrval_list${att_id}">

                                                </div>                                            

                                            </div>`);
                        $('.attrvalue').show();
                       
                        $(attrval_list_cls).append(obj.html);
                   }
               });
        } else {
            var service_id = $('.service_id').val();
            var attri = '.attri'+att_id;
            $(attri).remove();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"POST",
                url:'{{ route('get.remove.service.attr') }}',
                data:{
                   attribute_id: att_id,
                   service_id: service_id
                },
                success: function(res) {
                     
               }
           });
        }
     
     }
</script> 

<script type="text/javascript">
    $(document).ready(function () {
       
        if ($('.m_status').val()=='True'){ 
            $('.material_status').show();
        } else {
            $('.material_status').hide();
        }
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
    let GLobalAtrItemID = '';
    function deleteAtrItem(argument) {
        GLobalAtrItemID = argument;
        var cls = "."+argument;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('get.delete.atr.item') }}',
            data:{
               atr_id: argument
            },
            success: function(data) {
                if(data=='1'){
                    $(cls).remove();
                } else {
                    alert('Try Again');
                }
           }
       });
    }

    function deleteMoreAtrItem(argument) {
        GLobalAtrItemID = argument;
        var cls = ".more"+argument;
        $(cls).remove();
    }
</script>

<script>
    function removeGallery(id) {
        var gcls = ".gallery"+id;
        if (confirm("Are You Sure Delete This Image?") == true){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"POST",
                url:'{{ route('get.delete.gallery') }}',
                data:{
                   id: id
                },
                success: function(data) {
                    if(data=='1'){
                        $(gcls).remove();
                    } else {
                        alert('Try Again');
                    }
               }
           });
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

       
