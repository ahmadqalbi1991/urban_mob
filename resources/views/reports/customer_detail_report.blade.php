@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Customer Report</h3>

                        </div>

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
                                            
                                            <label class="text-left">From Date</label>

                                            <input type="date" class="form-control" name="from_date" value="{{$from_date}}">

                                        </div>

                                        <div class="col-md-3">
                                            
                                            <label class="">To Date</label>

                                            <input type="date" class="form-control" name="to_date" value="{{$to_date}}">

                                        </div>

                                        <div class="col-md-3">
                                            
                                            <label class="">Last Booking</label>

                                            <input type="date" class="form-control" name="last_booking" value="{{$last_booking}}">

                                        </div>

                                        <div class="col-md-3">

                                            <div class="btn-group mt-13" role="group" aria-label="Basic example">
                                              <button type="submit" class="btn btn-outline-info btn-primary btn-ft">Search</button>
                                              <a href="{{ url('customer/details/report') }}"><button type="button" class="btn btn-outline-info btn-primary btn-ft">Clear</button></a>
                                            </div>

                                        </div>

                                    </div>

                                </form>

                                <hr>

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped" style="text-align: center;" >

                                        <thead>

                                            <tr>

                                                <th>Signup Date</th>

                                                <th>Customer ID</th>

                                                <th>Customer Name</th>

                                                <th>Customer Email</th>

                                                <th>Customer Mobile</th>

                                                <th>Customer DOB</th>

                                                <th>Customer Address</th>

                                                <th>Status</th>

                                                <th>Total Booking</th>

                                                <th>Total Revenue</th>

                                                <th>First Booking Date</th>

                                                <th>First Booking ID</th>

                                                <th>First Booking Revenue</th>

                                                <th>Last Booking Date</th>

                                                <th>Last Booking ID</th>

                                                <th>Last Booking Revenue</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($users as $key=>$value)

                                            <tr>

                                                <td>{{ date("d-m-Y", strtotime($value->created_at)) }}</td>

                                                <td>{{ $value->id }}</td>

                                                <td>{{ $value->name }}</td>

                                                <td>{{ $value->email }}</td>

                                                <td>{{ $value->phone }}</td>

                                                <td>{{ $value->DOB?date("d-m-Y", strtotime($value->DOB)):'' }}</td>

                                                <td>
                                                    @if(count($value->address))
                                                        @foreach($value->address as $add)
                                                            <small><b>{{$add->address_type}}</b>, Flat No. {{$add->flat_no}}, {{$add->building}}, {{$add->address}}, {{$add->city?$add->city->name:''}}, {{$add->locality_info?$add->locality_info->name:''}}</small><br>
                                                        @endforeach
                                                    @endif
                                                </td>

                                                <td>{{$value->is_active==1?'Active':'Inactive'}}</td>

                                                <td>
                                                    <!-- Booking Count -->
                                                    @if(count($value->bookings))                                                        
                                                        @if($last_booking)
                                                            {{ App\Card::whereDate('created_at', '=', $last_booking)->count() }}
                                                        @else
                                                            @if($from_date && $to_date)
                                                                {{ App\Card::whereBetween('created_at',[$from_date, $to_date])->count() }}
                                                            @else
                                                                {{$value->bookings->count()}}  
                                                            @endif
                                                        @endif                                              
                                                    @endif
                                                </td>

                                                <td>
                                                    <!-- Total Revenue -->
                                                    @if(count($value->bookings))
                                                        @if($last_booking)
                                                            {{ App\Card::whereDate('created_at', '=', $last_booking)->sum('g_total') }}
                                                        @else
                                                            @if($from_date && $to_date)
                                                                {{ App\Card::whereBetween('created_at',[$from_date, $to_date])->sum('g_total') }}
                                                            @else
                                                                {{$value->bookings->sum('g_total')}}
                                                            @endif                                                            
                                                        @endif                                                    
                                                    @endif
                                                    
                                                </td>

                                                <td>
                                                    <!-- First Booking Date -->
                                                    @if(count($value->bookings))
                                                        {{ date("d-m-Y", strtotime($value->bookings->first()->created_at)) }}                                                    
                                                    @endif
                                                </td>

                                                <td>
                                                    <!-- First Booking id -->
                                                    @if(count($value->bookings))
                                                        {{ $value->bookings->first()->id }}                                                
                                                    @endif
                                                </td>

                                                <td>
                                                    <!-- First Booking Revenue -->
                                                    @if(count($value->bookings))
                                                        {{ $value->bookings->first()->g_total }}
                                                    @endif
                                                </td>

                                                <td>
                                                    <!-- Last Booking Date -->
                                                   
                                                    @if(count($value->bookings))
                                                        @if($last_booking)
                                                            {{ date("d-m-Y", strtotime($last_booking)) }}
                                                        @else
                                                            {{ date("d-m-Y", strtotime($value->created_at)) }}
                                                        @endif                                                    
                                                    @endif
                                                </td>

                                                <td>
                                                    <!-- Last Booking id -->
                                                    @if(count($value->bookings))
                                                        @if($last_booking)
                                                            {{ App\Card::whereDate('created_at', '=', $last_booking)->latest()->first()->id }}
                                                        @else
                                                            @if($from_date && $to_date)
                                                                {{ App\Card::whereBetween('created_at',[$from_date, $to_date])->latest()->first()->id }}
                                                            @else
                                                                {{ App\Card::latest()->first()->id }}
                                                            @endif
                                                        @endif                                                    
                                                    @endif
                                                </td>

                                                <td>
                                                    <!-- Last Booking Revenue -->
                                                    @if(count($value->bookings))
                                                        @if($last_booking)
                                                            {{ App\Card::whereDate('created_at', '=', $last_booking)->latest()->first()->g_total }}
                                                        @else
                                                            @if($from_date && $to_date)
                                                                {{ App\Card::whereBetween('created_at',[$from_date, $to_date])->latest()->first()->g_total }}
                                                            @else
                                                                {{ App\Card::latest()->first()->g_total }}
                                                            @endif
                                                        @endif                                                    
                                                    @endif
                                                </td>

                                            </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>   
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>   
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>   

<script>
    $(document).ready(function() {
        $('#example').DataTable( {
             dom: 'Bfrtip',
                buttons: [
                   {
                       extend: 'excel',
                       footer: false,
                       title: 'Customer Details',
                       
                   }         
                ],
                "oLanguage": {

                    "sSearch": "Customer Mobile:"

                    }
        } );
    } );
    
</script>  
@endsection 



       