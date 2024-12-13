@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Today Orders</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                            @if($orders->isEmpty() && $order_items->isEmpty())
                                @if($todayOrders>0)
                                    <form action="{{ route('generate.today.orders') }}" method="post" id="admin_profile_setting">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Generate Today Orders</button>
                                    </form>
                                @endif
                            @endif
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
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#orders">Orders </a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#order-items">Orders Item-Wise</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content tab-content-default">
                                        <div class="tab-pane fade show active" id="orders" role="tabpanel">

                                             <div class="table-responsive">
                                                <table class="display table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Customer</th>
                                                            <th>Item Info</th>
                                                            <th>Order</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($orders)
                                                    @foreach($orders as $key=>$value) 
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td><a href="{{ route('shop.customer.detail',encrypt($value->customer_id)) }}" target="_blank">{{ $value->customer->name }}</a></td>
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
                                                            <td>
                                                                @if($value->order_status=='Pending')
                                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-warning">Pending</span>
                                                                @elseif($value->order_status=='Delivered')
                                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Delivered</span>
                                                                @else
                                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $value->order_status }}</span>
                                                                @endif
                                                                <br>
                                                                {{ changeDateFormate($value->order_date) }}
                                                            </td>
                                                            <td>
                                                                @if($value->order_status=='Pending')
                                                                <a href="{{ route('order.status',['request' => $value->id, 'status' =>'Delivered']) }}" onclick="return confirm('Are you sure to delivered this order')"><button type="button" class="btn btn-outline-success btn-ft">Delivered</button></a>
                                                                <a href="{{ route('order.status',['request' => $value->id, 'status' =>'Cancelled']) }}" onclick="return confirm('Are you sure to cancelled this order')"><button type="button" class="btn btn-outline-danger btn-ft">Cancelled</button></a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Customer</th>
                                                            <th>Item Info</th>
                                                            <th>Order Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            
                                        </div>
                                        <div class="tab-pane fade" id="order-items">

                                            <div class="table-responsive">
                                                <table class="display table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Item</th>
                                                            <th>Packing Info</th>
                                                            <th>Qty</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($order_items)
                                                    @foreach($order_items as $key=>$value) 
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td><img width="50" height="50" alt="{{ $value->item_name }}" class="mr-3" src="{{itemImagePath($value->item_icon)}}">{{ $value->item_name }} ({{ $value->item_brand }})</td>
                                                            <td>
                                                               {{ $value->item_unit }} - {{ $value->item_price }} {{CURRENCY}} 
                                                            </td>
                                                            <td>{{ $value->total_qty }}</td>
                                                            <td>
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
                                                            <th>Qty</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('order.delivered',['today' => 'Yes']) }}" onclick="return confirm('Are you sure to delivered today all pending order')"><button type="button" class="btn btn-outline-success btn-ft">All Delivered</button></a>
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

       