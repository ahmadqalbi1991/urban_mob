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
                                        <div class="text-right mb-4">
                                            <a href="{{url('create/vendor/payment/'.$user->id.'/payment')}}"><button class="btn btn-primary">New Payment</button></a>
                                            
                                        </div>
                                        <div class="table-responsive">

                                            <table class="table table-border table-hover table-sm" >

                                                <thead>

                                                    <tr>

                                                        <th>S.N.</th>

                                                        <th>Transaction No.</th>

                                                        <th>Amount</th>

                                                        <th>Mode</th>

                                                        <th>Payment Date</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @if(!empty($data))

                                                    @foreach($data as $key=>$value)

                                                    <tr>

                                                        <td>{{ ++$key }}</td>

                                                        <td>{{ $value->transaction_id }}</td>

                                                        <td>AED {{ $value->amount }}</td>

                                                        <td>{{ $value->moad }}</td>

                                                        <td>{{ $value->transaction_date?date("d-m-Y", strtotime($value->transaction_date)):'' }}</td>

                                                    </tr>

                                                    @endforeach

                                                        @if ($data->count() == 0)

                                                        <tr class="text-center">

                                                            <td colspan="6">No data to display.</td>

                                                        </tr>

                                                        @endif

                                                    @endif

                                                </tbody>

                                                <tfoot>

                                                    <tr>

                                                        <th>S.N.</th>

                                                        <th>Transaction No.</th>

                                                        <th>Amount</th>

                                                        <th>Moad</th>

                                                        <th>Payment Date</th>

                                                    </tr>

                                                </tfoot>

                                            </table>

                                        </div>

                                        <div class="text-left float-left mt-1">

                                            <p>Displaying {{$data->count()}} of {{ $data->total() }} bookings.</p>

                                        </div>

                                        <div class="text-right float-right">{{ $data->appends(request()->all())->links() }}</div>

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



       