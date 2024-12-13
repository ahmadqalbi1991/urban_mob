@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">My Customers</h3>
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
                                <div class="table-responsive">
                                    <table class="display table">
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
                                                <a href="{{ route('shop.customer.detail',encrypt($value->customer_id)) }}" target="_blank"><button type="button" class="btn btn-outline-primary btn-ft">View</button></a>
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
        <!--**********************************
            Content body end
        ***********************************-->
      
@endsection      

       