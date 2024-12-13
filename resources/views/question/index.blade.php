@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Question</h3>

                        </div>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="table-responsive">

                                    <table class="table table-border table-hover table-sm" >

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Mobile No.</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($data))

                                            @foreach($data as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->mobile_no }}</td>

                                            </tr>

                                            @endforeach

                                                @if ($data->count() == 0)

                                                <tr class="text-center">

                                                    <td colspan="6">No datas to display.</td>

                                                </tr>

                                                @endif

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Mobile No.</th>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                                <div class="text-left float-left mt-1">

                                    <p>Displaying {{$data->count()}} of {{ $data->total() }} datas.</p>

                                </div>

                                <div class="text-right float-right">

                                    {{ $data->appends(request()->all())->links() }}

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



       