@extends('layouts.dashboard')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Items</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <button type="button" class="btn btn-rounded btn-primary"  data-toggle="modal" data-target="#addModal"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i> </span>Add Items</button>
                    </div>
                </div>
            <div class="container-fluid">
                @include('flash_msg')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Icon</th>
                                                <th>Name</th>
                                                <!-- <th>Unit</th> -->
                                                <th>Brand</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($items))
                                            @foreach($items as $key=>$value)  
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td><img width="50" height="50" alt="{{ $value->name }}" class="mr-3" src="{{itemImagePath($value->icon)}}"></td>
                                                <td>{{ $value->name }}</td>
                                                <!-- <td>{{ $value->unit }}</td> -->
                                                <td>{{ $value->brand }}</td>
                                                <td>
                                                    @if($value->is_active==1)
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Active</span>
                                                    @else
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($value->is_active==1)
                                                    <a href="{{ route('item.status',['item' => $value->id, 'status' =>0]) }}" onclick="return confirm('Are you sure to de-activate this item？')"><button type="button" class="btn btn-outline-danger btn-ft">Inactive</button></a>
                                                    @else
                                                    <a href="{{ route('item.status',['item' => $value->id, 'status' =>1]) }}" onclick="return confirm('Are you sure to activate this item？')"><button type="button" class="btn btn-outline-success btn-ft">Active</button></a>
                                                    @endif
                                                <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editModal{{$key}}">Edit</button>
                                                 <!-- Edit Modal -->
                                                    <div class="modal fade" id="editModal{{$key}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Item</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <form action="{{ route('item.update',$value->id) }}" method="POST" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Name<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="name" placeholder="" value="{{ $value->name }}" required>
                                                                    </div>
                                                                    <!-- <div class="form-group">
                                                                        <label>Unit<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="unit" placeholder="" value="{{ $value->unit }}" required>
                                                                    </div> -->
                                                                    <div class="form-group">
                                                                        <label>Brand<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="brand" placeholder="" value="{{ $value->brand }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Icon Image</label>
                                                                        <input type="file" class="form-control" name="icon" placeholder="">
                                                                        <input type="hidden" class="form-control" name="icon_image" value="{{ $value->icon }}">
                                                                        @if(!empty($value->icon))
                                                                        <img width="50" height="50" alt="{{ $value->name }}" class="mr-3" src="{{itemImagePath($value->icon)}}">
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Save</button>
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
                                                <th>Icon</th>
                                                <th>Name</th>
                                                <th>Brand</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--Add Modal -->
      <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Item</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" id="item_register">
                <!-- Modal body -->
                <div class="modal-body">
                
                    @csrf
                    <div class="form-group">
                        <label>Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="">
                    </div>
                    <!-- <div class="form-group">
                        <label>Unit<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="unit" placeholder="">
                    </div> -->
                    <div class="form-group">
                        <label>Brand<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="brand" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Icon Image</label>
                        <input type="file" class="form-control" name="icon" placeholder="">
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
          </div>
        </div>
      </div>
@endsection      

       