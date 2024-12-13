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

                            <h3 class="ml-1">Partner Settlement</h3>

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

                                            <label class="">Company Name</label>

                                            <input type="text" class="form-control" name="company_name" value="{{$company_name}}" placeholder="Search By Company Name">

                                        </div>

                                        <div class="col-md-3">

                                            <label class="">Service</label>

                                            <select name="service" class="form-control select2">
                                               <option value="">Select Service</option>
                                               @foreach($service as $ser)
                                               <option value="{{$ser->id}}" {{$service_id==$ser->id?'selected':''}}>{{$ser->name}}</option>
                                               @endforeach
                                           </select>

                                        </div>

                                        <div class="col-md-3">
                                            
                                            <label class="text-left">From Date</label>

                                            <input type="date" class="form-control" name="from_date" value="{{$from_date}}">

                                        </div>

                                        <div class="col-md-3">
                                            
                                            <label class="">To Date</label>

                                            <input type="date" class="form-control" name="to_date" value="{{$to_date}}">

                                        </div>

                                        <div class="col-md-3 mt-4">

                                            <label class="">&nbsp;</label>
                                            
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                              <button type="submit" class="btn btn-outline-info btn-primary btn-ft">Search</button>
                                              <a href="{{ url('partner/settlement/report') }}"><button type="button" class="btn btn-outline-info btn-primary btn-ft">Clear</button></a>
                                            </div>

                                        </div>

                                    </div>

                                </form>

                                <hr>

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped" style="text-align: center;" >

                                        <thead>

                                            <tr>

                                                <th>Partner ID</th>

                                                <th>Partner Company Name</th>

                                                <th>Last Payment Date</th>

                                                <th>Customer Name</th>

                                                <th>Booking Date</th>

                                                <th>Booking ID</th>

                                                <th>Source</th>

                                                <th>Job Status</th>

                                                <th>Payment Status</th>

                                                <th>Payment Method</th>

                                                <th>Service Category</th>

                                                <th>Service</th>

                                                <th>Job Value</th>

                                                <th>UM Comission</th>

                                                <th>PG Charges</th>

                                                <th>Coupon Name</th>

                                                <th>Adjustment</th>

                                                <th>Cash Surcharge</th>

                                                <th>Tip</th>

                                                <th>Service Schedule Date</th>

                                                <th>Service Schedule Time</th>

                                                <th>Service Start Time</th>

                                                <th>Service Finish Time</th>

                                                <th>Waiting Charges</th>

                                                <th>Final Amount</th>

                                                <th>Vat</th>

                                                <th>Payment Date</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($bookings as $key=>$value)

                                            <tr>

                                                <td>{{ $value->accept_user_id }}</td>

                                                <td>
                                                    {{App\Seller::where('user_id',$value->accept_user_id)->first()?App\Seller::where('user_id',$value->accept_user_id)->first()->company_name:''}}
                                                </td>

                                                <td>{{ date("d-m-Y", strtotime($value->created_at)) }}</td>

                                                <td>{{ $value->user?$value->user->name:'' }}</td>

                                                <td>{{ date("d-m-Y", strtotime($value->created_at)) }}</td>

                                                <td>{{ $value->id }}</td>

                                                <td>{{ $value->booking_from }}</td>
                                                
                                                <td>{{ $value->status }}</td>

                                                <td>{{ $value->payment_status }}</td>

                                                <td>{{ $value->payment_moad }}</td>

                                                <td>{{ $value->category?$value->category->name:'' }}</td>

                                                <td>{{ $value->service?$value->service->name:'' }}</td>

                                                <td>{{ $value->g_total }}</td>

                                                <td>{{ $value->service?$value->service->um_commission:'' }}</td>

                                                <td>{{ $setting?$setting->bank_charges:'' }}</td>

                                                <td>{{ $value->coupon?$value->coupon->code:'' }}</td>

                                                <td>
                                                    @if($value->coupon)

                                                        @if($value->coupon->type=='Per')
                                                            {{$value->coupon->amount}}%
                                                        @else
                                                            {{$value->coupon->amount}}
                                                        @endif

                                                    @endif
                                                </td>

                                                <td>{{ $value->cod_charge }}</td>

                                                <td>{{ $value->tip_id }}</td>

                                                <td>{{ date("d-m-Y", strtotime($value->date)) }}</td>

                                                <td>{{ $value->slot?$value->slot->name:'' }}</td>

                                                <td>{{ $value->service_start_datetime?date("d-m-Y", strtotime($value->service_start_datetime)):'' }}</td>

                                                <td>{{ $value->service_completed_date?date("d-m-Y", strtotime($value->service_completed_date)):'' }}</td>

                                                <td>0</td>

                                                <td>0</td>

                                                <td>0%</td>

                                                <td>
                                                    @if(App\Payment::where('vendor_id',$value->accept_user_id)->orderBy('id', 'DESC')->first())
                                                       {{ date("d-m-Y", strtotime(App\Payment::where('vendor_id',$value->accept_user_id)->orderBy('id', 'DESC')->first()->created_at)) }}
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
                       title: 'Partner Settlement Report',
                       
                   }         
                ]
        } );
    } );
    
</script>  
@endsection 



       