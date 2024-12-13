@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Extra Oeder / Add-ons Detail</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 col-md-5 col-xxl-4 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center mb-4">
                                        <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="{{asset('images/form-user.png')}}" width="80" height="80" alt="">
                                        <div class="media-body">
                                            <h3 class="mb-0">{{$addon->customer->name}}</h3>
                                            <p class="text-muted mb-0">Customer</p>
                                        </div>
                                    </div>

                                    <h4>Address</h4>
                                    <p class="text-muted">{{$addon->customer->address}}</p>
                                    <p class="text-muted">{{$addon->customer->city}}</p>
                                    <ul class="card-profile__info">
                                        <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span>{{$addon->customer->phone}}</span></li>
                                        <li class="mb-1"><strong class="text-dark mr-4">Email</strong> <span>{{$addon->customer->email}}</span></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-7 col-xxl-8 col-xl-9">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                       <tr>
                                           <td>Order Date</td>
                                           <td>{{ changeDateFormate($addon->addon_date) }}</td>
                                       </tr>
                                       <tr>
                                           <td>Status</td>
                                           <td>
                                                @if($addon->status=='Pending')
                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-warning">Pending</span>
                                                @elseif($addon->status=='Accept')
                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Accepted</span>
                                                @else
                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $addon->status }}</span>
                                                @endif
                                           </td>
                                       </tr>
                                   </table>
                                   <h4>Subscription Package Items</h4>
                                   @if($addon->items)
                                   <table  class="display table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($addon->items as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td><img width="50" height="50" alt="{{ $value->shopItem['item']['name'] }}" class="mr-3" src="{{itemImagePath($value->shopItem['item']['icon'])}}">{{ $value->shopItem['item']['name'] }} ({{ $value->shopItem['item']['brand']}})/ {{ $value->shopItem['quantity'] }} {{ $value->shopItem['unit'] }}</td>
                                                <td>{{ $value->shopItem['price'] }}</td>
                                                <td>{{ $value->qty }}</td>
                                                <td>{{ $value->shopItem['price']*$value->qty }}</td>
                                            </tr>
                                            @endforeach;
                                        </tbody>
                                    </table>
                                   @endif
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

       