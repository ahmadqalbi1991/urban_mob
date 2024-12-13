@extends('layouts.dashboard')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Subscription Package Detail</h3>
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
                                            <h3 class="mb-0">{{$package->vendor->name}}</h3>
                                            <p class="text-muted mb-0">Vendor</p>
                                        </div>
                                    </div>

                                    <h4>Address</h4>
                                    <p class="text-muted">{{$package->vendor->address}}</p>
                                    <p class="text-muted">{{$package->vendor->city}}</p>
                                    <ul class="card-profile__info">
                                        <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span>{{$package->vendor->phone}}</span></li>
                                        <li class="mb-1"><strong class="text-dark mr-4">Email</strong> <span>{{$package->vendor->email}}</span></li>
                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center mb-4">
                                        <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="{{asset('images/form-user.png')}}" width="80" height="80" alt="">
                                        <div class="media-body">
                                            <h3 class="mb-0">{{$package->customer->name}}</h3>
                                            <p class="text-muted mb-0">Customer</p>
                                        </div>
                                    </div>

                                    <h4>Address</h4>
                                    <p class="text-muted">{{$package->customer->address}}</p>
                                    <p class="text-muted">{{$package->customer->city}}</p>
                                    <ul class="card-profile__info">
                                        <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span>{{$package->customer->phone}}</span></li>
                                        <li class="mb-1"><strong class="text-dark mr-4">Email</strong> <span>{{$package->customer->email}}</span></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-7 col-xxl-8 col-xl-9">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                       <tr>
                                           <td>Subscription Type</td>
                                           <td>{{$package->package_type}}</td>
                                       </tr>
                                       <tr>
                                           <td>Subscription Start On</td>
                                           <td>{{ changeDateFormate($package->start_date) }}</td>
                                       </tr>
                                       @if($package->package_type=='Weekly')
                                       <tr>
                                           <td>Week Day</td>
                                           <td>{{ $package->week_day }}</td>
                                       </tr>
                                       @endif
                                       <tr>
                                           <td>Subscription Request</td>
                                           <td>
                                                @if($package->package_status=='Pending')
                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-warning">Pending</span>
                                                @elseif($package->package_status=='Accept')
                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Accepted</span>
                                                @else
                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $package->package_status }}</span>
                                                @endif
                                           </td>
                                       </tr>
                                       <tr>
                                           <td>Subscription Status</td>
                                           <td>
                                                @if($package->is_active==1)
                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Active</span>
                                                @else
                                                <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">Pause</span>
                                                @endif
                                           </td>
                                       </tr>
                                   </table>
                                   
                                   <h4>Subscription Package Items</h4>
                                   @if($package->items)
                                   <table  class="display table table-border table-striped table-hover">
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
                                            @foreach($package->items as $key=>$value)
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

                                  @if($package->leave)
                                   <h4>Package Leave</h4>
                                   <table  id="DT1" class="display table table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Leave Date</th>
                                                <th>Created On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($package->leave as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ changeDateFormate($value->leave_date) }}</td>
                                                <td>{{ changeDateTimeFormate($value->created_at) }}</td>
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

       