@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Shop Items</h3>
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
                                    <table  class="display table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Packing Info</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($shop_items)
                                        @foreach($shop_items as $key=>$value) 
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td><img width="50" height="50" alt="{{ $value->name }}" class="mr-3" src="{{itemImagePath($value->icon)}}">{{ $value->name }} ({{ $value->brand }})</td>
                                                <td>
                                                   @if( $value->packings )
                                                   <ul>
                                                   @foreach($value->packings as $index=>$row)
                                                        <li>{{ $row->quantity }} {{ $row->unit }} - {{ $row->price }} {{CURRENCY}} 
                                                            @if($row->is_available==1)
                                                            <span class="badge mb-1 badge-pill badge-success">Available</span>
                                                            @else
                                                            <span class="badge mb-1 badge-pill badge-danger">Not Available</span>
                                                            @endif
                                                         </li>
                                                   @endforeach 
                                                   </ul>
                                                   @endif
                                                </td>
                                                <td>
                                                <a href="{{route('shop.item',$value->id)}}"><button type="button" class="btn btn-outline-info btn-ft">Edit</button></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Packing Info</th>
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
            <form action="{{ route('shop.item.process') }}" method="POST" enctype="multipart/form-data" id="item_proceed">
                <!-- Modal body -->
                <div class="modal-body">
                
                    @csrf
                    <div class="form-group">
                        <label>Items<span class="text-danger">*</span></label>
                        <select class="form-control" name="item" required>
                            <option value="">-select-</option>
                            @if($items)
                            @foreach($items as $key=>$value)
                                <option value="{{$value->id}}">{{$value->name}} ({{$value->brand}})</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="submit" class="btn btn-success">Proceed</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
          </div>
        </div>
      </div>
      
@endsection      

       