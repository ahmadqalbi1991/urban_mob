@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Booking Confirm</h3>

                        </div>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="custom-tab-2">
                                    <?php $data['active'] = 'Payment'; ?>
                                    @include('offline.menu',$data)

                                    <div class="row">                                       

                                        <div class="col-lg-3"></div>
                                        
                                        <div class="col-lg-6">
                                            
                                            <div class="card">

                                                <div class="card-header bg-grey">
                                                   <b> Booking ID : {{ $off_line_booking->tran_id }}</b>
                                                </div>

                                                <div class="card-body row">

                                                    <div class="col-lg-5 mt-2"><b>Customer Name : </b></div>

                                                    <div class="col-lg-7 mt-2">{{ $off_line_booking->user?$off_line_booking->user->name:'' }}</div>

                                                    <div class="col-lg-5 mt-2"><b>Customer Phone : </b></div>

                                                    <div class="col-lg-7 mt-2">{{ $off_line_booking->user?$off_line_booking->user->phone:'' }}</div>

                                                    <div class="col-lg-5 mt-2"><b>Address : </b></div>

                                                    <div class="col-lg-7 mt-2">
                                                        @if(is_numeric($off_line_booking->address_id))
                                            
                                                            <strong>Address Type - {{$off_line_booking->address?$off_line_booking->address->address_type:''}}</strong>
                                                            <br>
                                                            Flat No. {{$off_line_booking->address?$off_line_booking->address->flat_no:''}}, {{$off_line_booking->address?$off_line_booking->address->building:''}}, 
                                                            <br>
                                                            @if($off_line_booking->address && $off_line_booking->address->locality_info)
                                                            {{$off_line_booking->address->locality_info?$off_line_booking->address->locality_info->name:''}}, 
                                                            @endif

                                                            @if($off_line_booking->address && $off_line_booking->address->city)
                                                            {{$off_line_booking->address->city?$off_line_booking->address->city->name:''}}, 
                                                            @endif

                                                            
                                                            <br>
                                                            {{$off_line_booking->address?$off_line_booking->address->address:''}}

                                                        @elseif(json_decode($off_line_booking->address_id))
                                                            
                                                            <strong>
                                                                Address Type - {{json_decode($off_line_booking->address_id)->address_type}}</strong>
                                                            <br>
                                                            Flat No. {{json_decode($off_line_booking->address_id)->flat_no}}, {{json_decode($off_line_booking->address_id)->building}}, 
                                                            <br>
                                                            {{json_decode($off_line_booking->address_id)->locality}},

                                                            {{json_decode($off_line_booking->address_id)->city_name}},

                                                            
                                                            <br>
                                                            {{json_decode($off_line_booking->address_id)->address}}
                                                        @else

                                                        @endif
                                                    </div>

                                                    <div class="col-lg-5 mt-2"><b>Service : </b></div>

                                                    <div class="col-lg-7 mt-2">{{ $off_line_booking->service?$off_line_booking->service->name:'' }}</div>

                                                    <div class="col-lg-5 mt-2"><b>Slot Date : </b></div>

                                                    <div class="col-lg-7 mt-2">{{ date('d F Y', strtotime($off_line_booking->date)) }}</div>

                                                    <div class="col-lg-5 mt-2"><b>Slot Time : </b></div>

                                                    <div class="col-lg-7 mt-2">{{ $off_line_booking->slot?$off_line_booking->slot->name:'' }}</div>

                                                    <div class="col-lg-5 mt-2"><b>Booking Amount : </b></div>

                                                    <div class="col-lg-7 mt-2">AED {{ $off_line_booking->g_total }}</div>

                                                </div>

                                            </div>
                                        
                                        </div>
                                        
                                        <div class="col-lg-3"></div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-header bg-grey">
                                                   <b>Payment Link</b>
                                                </div>
                                                <form action="{{ route('send.payment.link') }}" method="post">
                                                    @csrf
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="">Payment Link</label>
                                                            <input type="text" name="payment_link" class="form-control" value="{{ $off_line_booking->payment_link }}" placeholder="Payment Link" required>
                                                        </div>
                                                        <button class="btn btn-primary" name="submit_btn" value="Mail" type="submit">Send Payment Link</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-3"></div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-lg-6">
                                            
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="text-right">
                                                <a href="{{ route('offline.booking') }}"><button class="btn btn-primary" type="button">New Booking</button></a>
                                                <a href="{{ route('offline.bookings') }}"><button class="btn btn-primary" type="button">Go To List</button></a>
                                            </div>
                                        </div>
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

@section('script')

@endsection



       