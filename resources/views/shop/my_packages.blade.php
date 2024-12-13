@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Customer Subscription Package</h3>
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
                                    <table  class="display table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <!-- <th>Package</th> -->
                                                <th>Created </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($packages))
                                            @foreach($packages as $key=>$value)  
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td><a href="{{ route('shop.customer.detail',encrypt($value->customer_id)) }}" target="_blank">{{ $value->customer->name }}</a></td>
                                                <td>{{ $value->package_type }}</td>
                                                <!-- <td>
                                                    @if($value->package_status=='Pending')
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-warning">Pending</span>
                                                    @elseif($value->package_status=='Accept')
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Accepted</span>
                                                    @else
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $value->package_status }}</span>
                                                    @endif
                                                </td> -->
                                                <td>
                                                    @if($value->is_active==1)
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Active</span>
                                                    @else
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">Pause</span>
                                                    @endif
                                                </td>
                                                <td>{{ changeDateFormate($value->created_at) }}</td>
                                                <td>
                                                <a href="{{ route('shop.package.items',encrypt($value->id)) }}" target="_blank"><button type="button" class="btn btn-outline-info btn-ft">View Items</button></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <!-- <th>Package</th> -->
                                                <th>Created </th>
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

       