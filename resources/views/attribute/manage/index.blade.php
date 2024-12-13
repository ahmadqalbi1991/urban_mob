@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Attribute Value</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{route('attribute')}}">

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

                    <div class="col-8">

                        <div class="card">

                            <div class="card-body">

                                <form method="GET" action="" class="d-none">

                                    <div class="row text-right">

                                        <div class="col-md-6">

                                            <input type="text" class="form-control" name="search" value="{{$request->search}}" placeholder="Search by attribute value">

                                        </div>

                                        <div class="col-md-3">

                                            <label class="">&nbsp;</label>

                                            <button type="submit" class="btn btn-outline-info bg-grad-4 btn-ft">Search</button>

                                        </div>

                                    </div>

                                </form>

                                <div class="table-responsive">

                                    <table  class="display table table-border table-hover table-sm">

                                        <thead>

                                            <tr>

                                                <th width="7%">#</th>

                                                <th width="60%">Value</th>

                                                <th>Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($attribute))

                                            @foreach($attribute as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->value }}</td>

                                                <td>

                                                <button type="button" class="btn btn-outline-info btn-ft btn-sm" data-toggle="modal" data-target="#editModal{{$key}}"title="Edit" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                                <a href="{{ route('attributevalue.delete',encrypt($value->id)) }}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                     <!-- The Modal -->

                                                        <div class="modal fade" id="editModal{{$key}}">

                                                            <div class="modal-dialog modal-dialog-centered modal-sm">

                                                            <div class="modal-content">

                                                            

                                                                <!-- Modal Header -->

                                                                <div class="modal-header">

                                                                <h4 class="modal-title">Attribute</h4>

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                </div>

                                                                

                                                                <form action="{{ route('attributevalue.update',$value->id) }}" method="POST" enctype="multipart/form-data">

                                                                    <!-- Modal body -->

                                                                    <div class="modal-body">

                                                                        @csrf

                                                                        <div class="form-group">

                                                                            <label>Attribute Value<span class="text-danger">*</span></label>

                                                                            <input type="text" class="form-control" name="name" value="{{ $value->value }}" placeholder="Attribute Value" required>

                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label>Icon <small>(100X100)</small></label>

                                                                            <input type="file" class="form-control" name="icon">

                                                                            @if($value->icon)
                                                                            <img src="{{url('uploads/attribute/'.$value->icon)}}" style="max-height: 100px;">
                                                                            @endif

                                                                        </div>

                                                                    </div>

                                                                    

                                                                    <!-- Modal footer -->

                                                                    <div class="modal-footer">

                                                                    <button type="submit" class="btn btn-success bg-grad-4">Update</button>

                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                                                                    </div>

                                                                </form>

                                                                

                                                            </div>

                                                            </div>

                                                        </div>

                                                </td>

                                            </tr>

                                            @endforeach

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Value</th>

                                                <th>Action</th>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-4">

                        <div class="card">
                            
                            <div class="card-header">
                                Add New Attribute Value
                            </div>
                            <hr>
                            <div class="card-body">
                                
                                <form action="{{ route('attributevalue.store') }}" method="POST" enctype="multipart/form-data">

                                    @csrf

                                    <div class="form-group">

                                        <label>Attribute Name</label>

                                        <input type="text" class="form-control" value="{{$attribute_info?$attribute_info->name:''}}" readonly placeholder="" >
                                        <input type="hidden" name="attribute_id" value="{{$attribute_info?$attribute_info->id:''}}">

                                    </div>

                                    <div class="form-group">

                                        <label>Attribute Value<span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" name="name" placeholder="Attribute Value" >

                                    </div>

                                    <div class="form-group">

                                        <label>Icon <small>(100X100)</small></label>

                                        <input type="file" class="form-control" name="icon" placeholder="" id="image">

                                    </div>

                                    <div class="form-group">
        
                                        <img id="preview-image-before-upload" style="max-height: 100px;">
                                    
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

@endsection    

<script>
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



       