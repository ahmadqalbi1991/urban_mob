@extends('layouts.dashboard')
<style>
    .count-card {
        color: aliceblue;
        padding: 2%;
    }
    .count {
        float: right;
    }
</style>
@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Vendor Detail</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        

                    </div>

                

                <div class="container-fluid">

                    <div class="row">

                        <div class="col-lg-12">

                            @include('vendor.top')

                        </div>

                        <div class="col-lg-12">

                            <div class="card">

                                <div class="card-body">

                                    <div class="custom-tab-2">

                                        @include('vendor.menu')

                                        <div class="table-responsive">

                                            <table class="table table-border table-hover table-sm" >

                                                <thead>

                                                    <tr>

                                                        <th>S.N.</th>

                                                        <th>Booking ID</th>

                                                        <th>Customer</th>

                                                        <th>Service</th>

                                                        <th>Payment</th>

                                                        <th>Status</th>

                                                        <th>Action</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @if(!empty($in_progress_booking))

                                                    @foreach($in_progress_booking as $key=>$value)

                                                    <tr>

                                                        <td>{{ ++$key }}</td>

                                                        <td>{{ $value->tran_id }}</td>

                                                        <td>{{ $value->user?$value->user->name:'' }}</td>

                                                        <td>{{ $value->service?$value->service->name:'' }}</td>
                                                        
                                                        <td>{{ $value->payment_moad }}</td>

                                                        <td>{{ $value->status }}</td>

                                                        <td>
                                                            <a href="{{ route('booking.view',$value->id) }}"><button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Edit" alt="Edit"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                                        </td>

                                                    </tr>

                                                    @endforeach

                                                        @if ($in_progress_booking->count() == 0)

                                                        <tr class="text-center">

                                                            <td colspan="6">No booking to display.</td>

                                                        </tr>

                                                        @endif

                                                    @endif

                                                </tbody>

                                                <tfoot>

                                                    <tr>

                                                        <th>S.N.</th>

                                                        <th>Booking ID</th>

                                                        <th>Customer</th>

                                                        <th>Service</th>

                                                        <th>Payment</th>

                                                        <th>Status</th>

                                                        <th>Action</th>

                                                    </tr>

                                                </tfoot>

                                            </table>

                                        </div>

                                        <div class="text-left float-left mt-1">

                                            <p>Displaying {{$in_progress_booking->count()}} of {{ $in_progress_booking->total() }} bookings.</p>

                                        </div>

                                        <div class="text-right float-right">

                                            {{ $in_progress_booking->appends(request()->all())->links() }}

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



       