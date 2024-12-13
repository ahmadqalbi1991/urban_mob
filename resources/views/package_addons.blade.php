@extends('layouts.dashboard')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Extra Order Request</h3>
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
                                    <table class="display table table-border table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Vendor</th>
                                                <th>Status</th>
                                                <th>Order Date</th>
                                                <th>Created On</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($addons))
                                            @foreach($addons as $key=>$value)  
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td><a href="{{ route('customer.detail',encrypt($value->customer_id)) }}" target="_blank">{{ $value->customer->name }}</a></td>
                                                <td><a href="{{ route('vendor.detail',encrypt($value->vendor_id)) }}" target="_blank">{{ $value->vendor->name }}</a></td>
                                                <td>
                                                    @if($value->status=='Pending')
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-warning">Pending</span>
                                                    @elseif($value->status=='Accept')
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Accepted</span>
                                                    @else
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $value->status }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ changeDateFormate($value->addon_date) }}</td>
                                                <td>{{ changeDateTimeFormate($value->created_at) }}</td>
                                                <td>
                                                    <a href="{{ route('addon.detail',encrypt($value->id)) }}"><button type="button" class="btn btn-outline-info btn-ft">View</button></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @if ($addons->count() == 0)
                                            <tr class="text-center">
                                                <td colspan="7">No data(s) to display.</td>
                                            </tr>
                                            @endif
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Vendor</th>
                                                <th>Status</th>
                                                <th>Order Date</th>
                                                <th>Created On</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-left float-left mt-1">
                                    <p>Displaying {{$addons->count()}} of {{ $addons->total() }} data(s).</p>
                                </div>
                                <div class="text-right float-right">
                                    {{ $addons->links() }}
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

       