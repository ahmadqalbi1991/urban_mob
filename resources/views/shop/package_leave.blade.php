@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Package Leave</h3>
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
                                                <th>Customer</th>
                                                <th>Leave Date</th>
                                                <th>Created On</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($list))
                                            @foreach($list as $key=>$value)  
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td><a href="{{ route('shop.customer.detail',encrypt($value->package->customer_id)) }}" target="_blank">{{ $value->package->customer->name }}</a></td>
                                                <td>{{ changeDateFormate($value->leave_date) }}</td>
                                                <td>{{ changeDateTimeFormate($value->created_at) }}</td>
                                                <td>
                                                <a href="{{ route('shop.package.items',encrypt($value->package_id)) }}" target="_blank"><button type="button" class="btn btn-outline-info btn-ft">View Package</button></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Leave Date</th>
                                                <th>Created On</th>
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

       