@extends('layouts.dashboard')

@section('content')

<style>
    #example_filter {
        display: none;
    }
</style>

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Revenue & Bookings</h3>

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

                                        <div class="col-md-4">

                                            <label class="text-left">Payment Method</label>

                                            <select class="form-control" name="method">
                                                <option value="">Select Payment Method</option>
                                                <option value="Card" {{$method=="Card"?"selected":""}}>Card</option>
                                                <option value="Cash" {{$method=="Cash"?'selected':''}}>Cash</option>
                                            </select>

                                        </div>

                                        <div class="col-md-4">

                                            <label class="text-left">Payment Status</label>

                                            <select class="form-control" name="status">
                                                <option value="">Select Payment Status</option>
                                                <option value="True" {{$status=="True"?"selected":""}}>True</option>
                                                <option value="False" {{$status=="False"?"selected":""}}>False</option>
                                            </select>

                                        </div>

                                        <div class="col-md-4">

                                            <label class="text-left">Company Name</label>

                                            <input type="text" class="form-control" name="company_name" value="{{$company_name}}" placeholder="Search By Company Name">

                                        </div>

                                        <div class="col-md-4 mt-4">

                                            <label class="text-left">Service</label>

                                            <select name="service" class="form-control select2">
                                               <option value="">Select Service</option>
                                               @foreach($service as $ser)
                                               <option value="{{$ser->id}}" {{$service_id==$ser->id?'selected':''}}>{{$ser->name}}</option>
                                               @endforeach
                                           </select>

                                        </div>

                                        
                                        <div class="col-md-4 mt-4">
                                            
                                            <label class="text-left">From Date</label>

                                            <input type="date" class="form-control" name="from_date" value="{{$from_date}}">

                                        </div>

                                        <div class="col-md-4 mt-4">
                                            
                                            <label class="">To Date</label>

                                            <input type="date" class="form-control" name="to_date" value="{{$to_date}}">

                                        </div>

                                        <div class="col-md-3">

                                            <div class="btn-group mt-14" role="group" aria-label="Basic example">
                                              <button type="submit" class="btn btn-outline-info btn-primary btn-ft">Search</button>
                                              <a href="{{ url('revenue/bookings/report') }}"><button type="button" class="btn btn-outline-info btn-primary btn-ft">Clear</button></a>
                                            </div>

                                        </div>

                                    </div>

                                </form>

                                <hr>

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped" style="text-align: left;" >

                                        <thead>

                                            <tr>

                                                <th>Customer ID</th>

                                                <th>Customer Name</th>

                                                <th>Customer Email</th>

                                                <th>Customer Mobile</th>

                                                <th>Booking ID</th>

                                                <th>Source</th>

                                                <th>Payment Method</th>

                                                <th>Booking Date</th>

                                                <th>Service Category</th>

                                                <th>Service</th>

                                                <th>Job Value</th>

                                                <th>Payment Status</th>

                                                <th>Partner ID</th>

                                                <th>Partner Company Name</th>

                                                <th>Tip</th>

                                                <th>Booking Instruction</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($bookings as $key=>$value)

                                            <tr>

                                                <td>{{ $value->user_id }}</td>

                                                <td>{{ $value->user?$value->user->name:'' }}</td>

                                                <td>{{ $value->user?$value->user->email:'' }}</td>

                                                <td>{{ $value->user?$value->user->phone:'' }}</td>

                                                <td>{{ $value->id }}</td>

                                                <td>{{ $value->booking_from }}</td>

                                                <td>{{ $value->payment_moad }}</td>
                                                                                               
                                                <td>{{ date("d-m-Y", strtotime($value->created_at)) }}</td>

                                                <td>{{ $value->category?$value->category->name:'' }}</td>

                                                <td>{{ $value->service?$value->service->name:'' }}</td>

                                                <td>{{ $value->g_total }}</td>

                                                <td>{{ $value->payment_status }}</td>

                                                <td>{{ $value->accept_user_id }}</td>

                                                <td>
                                                    {{App\Seller::where('user_id',$value->accept_user_id)->first()?App\Seller::where('user_id',$value->accept_user_id)->first()->company_name:''}}
                                                </td>

                                                <td>{{ $value->tip_id }}</td>

                                                <td>{{ $value->note }}</td>


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
                       title: 'Revenue & Bookings',
                       
                   }         
                ]
        } );
    } );
    
</script>  
@endsection 



       