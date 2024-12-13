@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Web Setting</h3>

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

                                <form action="{{route('update.web.settings')}}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <!-- Modal body -->
                                    <div class="row">

                                        <div class="form-group col-lg-3">

                                            <label>Rewards (Points Per AED)</label>

                                            <input type="text" class="form-control" name="points" value="{{ optional($rewards)->value ?? 0 }}" placeholder="Minimum Cart Value">

                                        </div>

                                        <div class="form-group col-lg-3">

                                            <label>Minimum Cart Value</label>

                                            <input type="text" class="form-control" name="min_cart_value" value="{{$setting->min_cart_value}}" placeholder="Minimum Cart Value">

                                        </div>

                                        <div class="form-group col-lg-3">

                                            <label>Cash Surcharge</label>

                                            <input type="text" class="form-control" name="cash_surcharge" value="{{$setting->cash_surcharge}}" placeholder="Cash Surcharge">

                                        </div>

                                        <!-- <div class="form-group col-lg-3">

                                            <label>UM Commission</label>

                                            <input type="text" class="form-control" name="um_commission" value="{{$setting->um_commission}}" placeholder="UM Commission">

                                        </div> -->

                                        <div class="form-group col-lg-3">

                                            <label>Bank Percentage</label>

                                            <input type="text" class="form-control" name="bank_percentage" value="{{$setting->bank_percentage}}" placeholder="Bank Percentage">

                                        </div>

                                        <div class="form-group col-lg-3">

                                            <label>Bank Charges</label>

                                            <input type="text" class="form-control" name="bank_charges" value="{{$setting->bank_charges}}" placeholder="Bank Charges">

                                        </div>

                                        <div class="form-group col-lg-3">

                                            <label>Admin Mobile No.</label>

                                            <input type="number" class="form-control" name="admin_mobile" value="{{$setting->admin_mobile}}" placeholder="Admin Mobile Number">

                                        </div>

                                        <div class="form-group col-lg-3">

                                            <label>Payment QR</label>

                                            <input type="file" class="form-control" name="payment_barcode" value="{{$setting->payment_barcode}}">

                                            @if($setting && $setting->payment_barcode)
                                            <br><img src="{{ $setting->payment_barcode }}" alt="" height="100" width="100">
                                            @endif

                                        </div>

                                    </div>

                                    <div class="text-center">
                                        
                                        <button type="submit" class="btn btn-success bg-grad-4">Update</button>
                                    
                                    </div>                                    
                                
                                </form>

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




       