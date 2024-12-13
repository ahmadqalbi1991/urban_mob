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

                        <a href="{{ url()->previous() }}" class="btn btn-rounded bg-grad-4 ml-4">

                            <span class="btn-icon-left text-primary">

                                <i class="fa fa-arrow-left color-primary"></i> 

                            </span>Back

                        </a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')
                <form action="{{url('update/service/attribute/'.$ser_atr->id)}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

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

                                            <input type="text" class="form-control" value="{{$ser_atr->service->name}}" placeholder="Service Title" readonly>
                                            <input type="hidden" value="{{$ser_atr->service_id}}" name="service_id" class="service_id">
                                            <input type="hidden" value="{{$ser_atr->id}}" name="id">
                                        </div>

                                    </div>                                  

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Service Category <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-lg-6 form-group">
                                            <input type="hidden" name="old_parent_id" value="{{$ser_atr->category_id}}">
                                            <select class="form-control select2 " name="parent_id" onchange="subCategory(this.value)" required>

                                               <option value="">Select Category</option>
                                                @foreach($categorys as $cate)
                                                <option value="{{$cate->id}}" {{$ser_atr->category_id==$cate->id?'selected':''}}>{{$cate->name}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Sub Category</label>

                                        </div>

                                        <div class="col-lg-6 form-group">
                                            <input type="hidden" name="old_sub_category_id" value="{{$ser_atr->sub_category_id}}">
                                            <select class="form-control select2 sub_category" name="sub_category_id" onchange="childCategory(this.value)">
                                                <option value="">Select Sub Category</option>
                                                @foreach($categorys->where('parent_id',$ser_atr->category_id) as $sub_category)
                                                <option value="{{$sub_category->id}}" {{$ser_atr->sub_category_id==$sub_category->id?'selected':''}}>{{$sub_category->name}}</option>
                                                @endforeach
                                                
                                            </select>

                                        </div>

                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label>Child Category </label>

                                        </div>

                                        <div class="col-lg-6 form-group">
                                            <input type="hidden" name="old_child_category_id" value="{{$ser_atr->child_category_id}}">
                                            <select class="form-control select2 child_category" name="child_category_id" required>
                                                <option>Select Child Category</option>
                                                <option value="">Select Child Category</option>
                                                @foreach($child_category as $child_cat)
                                                <option value="{{$child_cat->id}}" {{$ser_atr->child_category_id==$child_cat->id?'selected':''}}>{{$child_cat->name}}</option>
                                                @endforeach
                                                
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

                                            <div id="list1" class="dropdown-check-list" tabindex="100">
                                              <span class="anchor">Select Attribute</span>
                                              <ul class="items">
                                                @foreach($attribute as $attr)
                                                    @if(in_array($attr->id, $attr_ids))
                                                    <li><input type="checkbox" id="atrid{{$attr->id}}" value="{{$attr->id}}" name="attribute_id[]" onclick="selectAttrVal({{$attr->id}})" checked />&nbsp;&nbsp;{{$attr->name}} </li>
                                                    @else
                                                    <li><input type="checkbox" id="atrid{{$attr->id}}" value="{{$attr->id}}" name="attribute_id[]" onclick="selectAttrVal({{$attr->id}})" />&nbsp;&nbsp;{{$attr->name}}</li>
                                                    @endif
                                                @endforeach
                                              </ul>
                                            </div>
                                            <input type="hidden" name="remove_atr_id" class="remove_atr_id">

                                        </div>

                                    </div>

                                    @if(count($attr_ids)>0)
                                    @foreach($attr_ids as $key => $attr_id)
                                    <?php $attrValue = App\Attribute::find($attr_id) ?>
                                    <div class="row mb-2 attri{{$attr_id}}">

                                        <div class="col-lg-3">

                                            <label>{{$attrValue?$attrValue->name:''}} Attributes <span class="text-danger">*</span></label>
                                            <!-- <input type="hidden" name="attribute_id[]" value="{{$attr_id}}"> -->

                                        </div>

                                        <div class="col-lg-9 row">
                                            @foreach($attr_items->where('attribute_id',$attr_id) as $key => $attr_item)
                                            <div class="col-lg-5 form-group {{$attr_item->id}}">

                                                <input type="text" value="{{App\AttributeValue::whereId($attr_item->attribute_item_id)->value('value')}}" readonly class="form-control">
                                                <input type="hidden" readonly name="attribute_val_id_{{$attr_id}}[]" value="{{$attr_item->attribute_item_id}}" class="form-control">

                                            </div>

                                            <div class="col-lg-5 form-group {{$attr_item->id}}">

                                                <div class="input-group mb-3">

                                                    <div class="input-group-prepend">

                                                        <span class="input-group-text" id="basic-addon1">{{ Session::get('currencies') }}</span>

                                                    </div>

                                                    <input type="number" class="form-control" name="attribute_val_price_{{$attr_id}}[]" value="{{$attr_item->attribute_price}}" placeholder="Price">

                                                </div>                                                

                                            </div>

                                            <div class="col-lg-2 form-group {{$attr_item->id}}">
                                                <button type="button" class="btn btn-outline-danger" onclick="deleteAtrItem({{$attr_item->id}})"><i class="fa fa-times" aria-hidden="true"></i></button>
                                            </div>
                                            @endforeach

                                        </div>                                            

                                    </div>
                                    @endforeach
                                    @endif

                                    <span class="attr_list"></span>                                    

                                </div>

                            </div>

                        </div>                                                         
                    
                    </div> 

                    <div class="text-right mt-4">
                                        
                        <button type="submit" class="btn btn-warning">Update</button>
                    
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
            $('.remove_atr_id').val(att_id);
            $(attri).remove();
           //  $.ajax({
           //      headers: {
           //          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           //      },
           //      type:"POST",
           //      url:'{{ route('get.remove.service.attr') }}',
           //      data:{
           //         attribute_id: att_id,
           //         service_id: service_id
           //      },
           //      success: function(res) {
                     
           //     }
           // });
        }
     

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

       
