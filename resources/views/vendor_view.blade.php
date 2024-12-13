@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">View Vendor</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ url('vendors') }}">

                            <button type="button" class="btn btn-rounded bg-grad-4 ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-arrow-left color-primary"></i> 

                                </span>Back

                            </button>

                        </a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="row">
                                        
                                    <div class="col-lg-2"><b>Vendor Name</b></div>
                                    
                                    <div class="col-lg-10">: {{ $value->name }}</div>

                                    <div class="col-lg-2"><b>Vendor Email</b></div>
                                    
                                    <div class="col-lg-10">: {{ $value->email }}</div>

                                    <div class="col-lg-2"><b>Vendor Phone</b></div>
                                    
                                    <div class="col-lg-10">: {{ $value->phone }}</div>

                                    <div class="col-lg-2"><b>City</b></div>
                                    
                                    <div class="col-lg-10">: @if($seller && $seller->city_info && isset($seller->city_info) && isset($seller->city_info)){{ $seller->city_info?$seller->city_info->name:'No City' }}@endif</div>

                                    <div class="col-lg-2"><b>Status</b></div>
                                    
                                    <div class="col-lg-10">: {{ $value->is_active=='1'?"Active":'Inactive' }}</div>

                                    <div class="col-lg-2"><b>Registered By</b></div>
                                    
                                    <div class="col-lg-10">: {{ $value->registered_by ?? 'App' }}</div>

                                    <div class="col-lg-2"><b>Company Name</b></div>
                                    
                                    <div class="col-lg-10">: {{ $seller?$seller->company_name:'NA' }}</div>

                                    <div class="col-lg-2"><b>Address</b></div>
                                    
                                    <div class="col-lg-10">: {{ $seller?$seller->address:'NA' }}</div>

                                    <div class="col-lg-2"><b>Bank Name</b></div>
                                    
                                    <div class="col-lg-10">: {{ $seller?$seller->bank_name:'NA' }}</div>

                                    <div class="col-lg-2"><b>AC Holder Name</b></div>
                                    
                                    <div class="col-lg-10">: {{ $seller?$seller->ac_holder_name:'NA' }}</div>

                                    <div class="col-lg-2"><b>AC Number</b></div>
                                    
                                    <div class="col-lg-10">: {{ $seller?$seller->ac_number:'NA' }}</div>

                                    <div class="col-lg-2"><b>Licence File</b></div>
                                    
                                    <div class="col-lg-10">: 
                                        @if($seller && $seller->licence_file))
                                            <a href="{{ url('uploads/vendor_document/'.$seller->licence_file) }}" target="_blank"><img src="{{ url('uploads/vendor_document/'.$seller->licence_file) }}" height="100px" width="100px"></a>
                                        @endif
                                    </div>

                                    <div class="col-lg-2"><b>Servicies</b></div>
                                    
                                    <div class="col-lg-10">: 
                                        @foreach($seller_service_id as $key => $serv)
                                            {{$serv}}, 
                                        @endforeach
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



       