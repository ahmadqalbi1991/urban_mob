@extends('layouts.dashboard')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Order Invoice</h3>
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
                                <form method="GET" action="">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                              <label>Select Month:</label>
                                              <select class="form-control" name="month">
                                                <option value="">-select-</option>
                                                @if($months)
                                                    @foreach($months as $key=>$value)
                                                        <option value="{{$value}}" @if ($request->month == $value) {{ 'selected' }}  @endif >{{$value}}</option>
                                                    @endforeach
                                                @endif
                                              </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                              <label>Select Year:</label>
                                              <select class="form-control" name="year">
                                                <option value="">-select-</option>
                                                @if($years)
                                                    @foreach($years as $key=>$value)
                                                        <option value="{{$value}}" @if ($request->year == $value) {{ 'selected' }}  @endif >{{$value}}</option>
                                                    @endforeach
                                                @endif
                                              </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <label class="mt-2">&nbsp;</label>
                                            <button type="submit" class="btn btn-outline-info btn-ft mt-3">Filter</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                               <div class="table-responsive">
                                    <table  class="display table table-border table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice Info</th>
                                                <th>Invoice Month</th>
                                                <th>Customer</th>
                                                <th>Vendor</th>
                                                <th>Generated</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($invoices)
                                        @foreach($invoices as $key=>$value) 
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <b>Invoice ID:</b> {{ $value->id }} <br>
                                                    <b>Invoice Amount:</b> {{ $value->amount }} {{CURRENCY}}  <br>
                                                </td>
                                                <td>  {{ $value->month }} -  {{ $value->year }}</td>
                                                <td><a href="{{ route('customer.detail',encrypt($value->customer_id)) }}" target="_blank">{{ $value->customer->name }}</a></td>
                                                <td><a href="{{ route('vendor.detail',encrypt($value->vendor_id)) }}" target="_blank">{{ $value->vendor->name }}</a></td>
                                                <td>{{ changeDateFormate($value->created_at) }}</td>
                                                <td><a href="{{ route('invoice.download',['id'=>$value->id,'download'=>'pdf']) }}"><button type="button" class="btn btn-outline-success btn-ft">Download Invoice</button></a></td>
                                            </tr>
                                        @endforeach
                                        @if ($invoices->count() == 0)
                                        <tr class="text-center">
                                            <td colspan="7">No invoice(s) to display.</td>
                                        </tr>
                                        @endif
                                        @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice Info</th>
                                                <th>Invoice Month</th>
                                                <th>Customer</th>
                                                <th>Vendor</th>
                                                <th>Generated</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-left float-left mt-1">
                                    <p>Displaying {{$invoices->count()}} of {{ $invoices->total() }} invoice(s).</p>
                                </div>
                                <div class="text-right float-right">
                                    {{ $invoices->appends(request()->all())->links() }}
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

       