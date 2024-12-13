@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6 col-xxl-6 col-xl-6">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <h4 class="text-muted mb-3">Items</h4>
                                    </div>
                                    <div class="col-auto">
                                         <h2>{{$total_items}}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xxl-6 col-xl-6">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <h4 class="text-muted mb-3">Customers</h4>
                                    </div>
                                    <div class="col-auto">
                                         <h2>{{$total_customers}}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xxl-6 col-xl-6">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <h4 class="text-muted mb-3">Subscription</h4>
                                    </div>
                                    <div class="col-auto">
                                        <h2>{{$total_packages}}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xxl-6 col-xl-6">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <h4 class="text-muted mb-3">Payment</h4>
                                    </div>
                                    <div class="col-auto">
                                        <h2>
                                        @if($total_payment>0)
                                            <i class="text-danger">{{$total_payment}} {{CURRENCY}}</i>
                                        @elseif($total_payment<0)
                                            <i class="text-success">{{$total_payment}} {{CURRENCY}}</i>
                                        @else
                                            <i>{{$total_payment}} {{CURRENCY}}</i>
                                        @endif
                                        </h2>
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

       