@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Customers Request</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        
                    </div>
                </div>
            <div class="container-fluid">
                @include('flash_msg')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="custom-tab-2">
                                        <ul class="nav nav-tabs nav-justified">
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#pending">Pending</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#decline">Cancel/Reject</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content tab-content-default">
                                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                                            <div class="table-responsive">
                                                <table  class="display table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Phone/Email</th>
                                                            <th>Address</th>
                                                            <th>Requested</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($new_list))
                                                        @foreach($new_list as $key=>$value)
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td>{{ $value->name }}</td>
                                                            <td>{{$value->email}}<br>{{$value->phone}}</td>
                                                            <td>{{$value->address}}<br>{{$value->city}}</td>
                                                            <td>{{ changeDateFormate($value->created_at) }}</td>
                                                            <td>
                                                                @if($value->request_status=='Pending')
                                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-warning">Pending</span>
                                                                @elseif($value->request_status=='Accept')
                                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Accepted</span>
                                                                @else
                                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $value->request_status }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($value->request_status=='Pending')
                                                                <a href="{{ route('request.status',['request' => $value->request_id, 'status' =>'Accept']) }}" onclick="return confirm('Are you sure to accept this request')"><button type="button" class="btn btn-outline-success btn-ft">Accept</button></a>
                                                                <a href="{{ route('request.status',['request' => $value->request_id, 'status' =>'Reject']) }}" onclick="return confirm('Are you sure to reject this request')"><button type="button" class="btn btn-outline-danger btn-ft">Reject</button></a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Phone/Email</th>
                                                            <th>Address</th>
                                                            <th>Requested</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            </div>
                                            <div class="tab-pane fade" id="decline" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table  class="display table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Phone/Email</th>
                                                                <th>Address</th>
                                                                <th>Requested</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(!empty($list))
                                                            @foreach($list as $key=>$value)
                                                            <tr>
                                                                <td>{{ ++$key }}</td>
                                                                <td>{{ $value->name }}</td>
                                                                <td>{{$value->email}}<br>{{$value->phone}}</td>
                                                                <td>{{$value->address}}<br>{{$value->city}}</td>
                                                                <td>{{ changeDateFormate($value->created_at) }}</td>
                                                                <td>
                                                                    @if($value->request_status=='Pending')
                                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-warning">Pending</span>
                                                                    @elseif($value->request_status=='Accept')
                                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Accept</span>
                                                                    @else
                                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $value->request_status }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                   
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @endif
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Phone/Email</th>
                                                                <th>Address</th>
                                                                <th>Requested</th>
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
                    </div>
                </div>
            
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
      
@endsection      

       