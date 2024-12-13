@extends('layouts.dashboard')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">My Orders</h3>
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
                                <form method="GET" action="">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Start Date:</label>
                                            <input type="date" class="form-control" name="start_date" value="{{$request->start_date}}" required="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>End Date:</label>
                                            <input type="date" class="form-control" name="end_date" value="{{$request->end_date}}" required="">
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <label class="mt-2">&nbsp;</label>
                                            <button type="submit" class="btn btn-outline-info btn-ft mt-3">Filter</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                               <div class="table-responsive">
                                    <table class="display table table-border table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Order Info</th>
                                                <th>Item Info</th>
                                                <th>Customer</th>
                                                <th>Vendor</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($orders)
                                        @foreach($orders as $key=>$value) 
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <b>Order ID:</b> {{ $value->id }} <br>
                                                    <b>Total Amount:</b> {{ $value->total_amount }} {{CURRENCY}}  <br>
                                                    <b>Order Date:</b> {{ changeDateFormate($value->order_date) }} 
                                                </td>
                                                <td>
                                                   @if( $value->items )
                                                   <ol>
                                                   @foreach($value->items as $index=>$row)
                                                        <li>{{++$index}}.{{ $row->item_name }} ({{$row->item_brand}}) - {{ $row->item_unit }} - {{ $row->item_price }} {{CURRENCY}} - Qty:{{ $row->item_qty }}  
                                                         </li>
                                                   @endforeach 
                                                   </ol>
                                                   @endif
                                                </td>
                                                <td><a href="{{ route('customer.detail',encrypt($value->customer_id)) }}" target="_blank">{{ $value->customer->name }}</a></td>
                                                <td><a href="{{ route('vendor.detail',encrypt($value->vendor_id)) }}" target="_blank">{{ $value->vendor->name }}</a></td>
                                                <td>
                                                    @if($value->order_status=='Pending')
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-warning">Pending</span>
                                                    @elseif($value->order_status=='Delivered')
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Delivered</span>
                                                    @else
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $value->order_status }}</span>
                                                    @endif
                                                    <br>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($orders->count() == 0)
                                        <tr class="text-center">
                                            <td colspan="6">No order(s) to display.</td>
                                        </tr>
                                        @endif
                                        @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Order Info</th>
                                                <th>Item Info</th>
                                                <th>Customer</th>
                                                <th>Vendor</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-left float-left mt-1">
                                    <p>Displaying {{$orders->count()}} of {{ $orders->total() }} order(s).</p>
                                </div>
                                <div class="text-right float-right">
                                     {{ $orders->appends(request()->all())->links() }}
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

       