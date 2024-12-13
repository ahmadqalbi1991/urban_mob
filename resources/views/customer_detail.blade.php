@extends('layouts.dashboard')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="breadcrumb-range-picker">
                        <h3 class="ml-1">Customer Detail</h3>
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
                                        <h3 class="mb-0">{{$user->name}}</h3>
                                        <p class="text-muted mb-0">Customer</p>
                                    </div>
                                </div>

                                <h4>Address</h4>
                                <p class="text-muted">{{$user->address}}</p>
                                <p class="text-muted">{{$user->city}}</p>
                                <ul class="card-profile__info">
                                    <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span>{{$user->phone}}</span></li>
                                    <li class="mb-1"><strong class="text-dark mr-4">Email</strong> <span>{{$user->email}}</span></li>
                                    <li class="mb-1"><strong class="text-dark mr-4">Registered</strong> <span>{{ changeDateFormate($user->created_at) }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-xxl-8 col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="custom-tab-2">
                                        <ul class="nav nav-tabs nav-justified">
                                            
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#vendor">My Vendors</a>
                                            </li>
                                            <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#package">My Packages</a>
                                            </li> -->
                                        </ul>
                                        <div class="tab-content tab-content-default">
                                            <div class="tab-pane fade show active" id="vendor" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table id="example" class="display">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Contact</th>
                                                                <th>Address</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if($myVendors)
                                                        @foreach($myVendors as $key=>$value) 
                                                            <tr>
                                                                <td>{{ ++$key }}</td>
                                                                <td><a href="{{ route('vendor.detail',encrypt($value->vendor_id)) }}" target="_blank">{{ $value->name }}</a></td>
                                                                <td>
                                                                  {{ $value->email }}<br>{{ $value->phone }}
                                                                </td>
                                                                <td>
                                                                  {{ $value->address }}<br>{{ $value->city }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @endif
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Contact</th>
                                                                <th>Address</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>       
                                            <!-- <div class="tab-pane fade" id="package" role="tabpanel">
                                                
                                            </div> -->
                                            
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

       