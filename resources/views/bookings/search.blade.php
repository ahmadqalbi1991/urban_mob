@extends('layouts.dashboard')
<!-- <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet"> -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->
@section('content')
<style>
    .dt-buttons {
            display: none;
    }
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }

    .card-card-body {
    padding: 0rem .25rem !important;
        }

        table.dataTable tbody td {
    padding: 5px !important;
    line-height: 24px !important;
    font-size: 13px !important;
        }

    #action-buttons-booking .btn{
        margin: 2px 4px !important;
    }

    .f-left {
        flex: left !important;
    }

</style>

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Bookings</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                      
                        <a href="{{ route('draft.bookings') }}">

                            <button type="button" class="btn btn-rounded bg-grad-4 ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-list color-primary"></i> 

                                </span>
                                Draft Bookings

                            </button>

                        </a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">
                            
                            <div class="card-body">

                                <form action="" method="Get">
                                    
                                    <div class="row">
                                    
                                        <div class="col-lg-3 form-group">

                                            <label>Booking ID</label>
                                            
                                            <input type="text" name="booking_id" class="form-control" value="{{ $request->booking_id }}" placeholder="Search By Booking ID">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Slot Date</label>
                                            
                                            <input type="date" name="slot_date" class="form-control" value="{{ $request->slot_date }}" placeholder="Search By Slot Date">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Vendor Company</label>
                                            
                                            <input type="text" name="vendor_name" class="form-control" value="{{ $request->vendor_name }}" placeholder="Search By Vendor Company">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Customer Name</label>
                                            
                                            <input type="text" name="customer_name" class="form-control" value="{{ $request->customer_name }}" placeholder="Search By Customer Name">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Customer Mobile No.</label>
                                            
                                            <input type="number" name="customer_number" class="form-control" value="{{ $request->customer_number }}" placeholder="Search By Customer Mobile Number">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Service Name</label>
                                            
                                            <input type="text" name="service_name" class="form-control" value="{{ $request->service_name }}" placeholder="Search By Service Name">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Service Status</label>
                                            
                                            <select class="form-control" name="service_status">
                                                <option value="">Select Service Status</option>
                                                <option value="Pending" {{ $request->service_status=='Pending'?'selected':'' }}>Pending</option>
                                                <option value="Accept" {{ $request->service_status=='Accept'?'selected':'' }}>Accept</option>
                                                <option value="In Progress" {{ $request->service_status=='In Progress'?'selected':'' }}>In Progress</option>
                                                <option value="Mark As Arrived" {{ $request->service_status=='Mark As Arrived'?'selected':'' }}>Mark As Arrived</option>
                                                <option value="Completed" {{ $request->service_status=='Completed'?'selected':'' }}>Completed</option>
                                                <option value="Canceled" {{ $request->service_status=='Canceled'?'selected':'' }}>Canceled</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Payment Mode</label>
                                            
                                            <select class="form-control" name="payment_mode">
                                                <option value="">Select Payment Mode</option>
                                                <option value="Cash" {{ $request->payment_mode=='Cash'?'selected':'' }}>Cash</option>
                                                <option value="Card" {{ $request->payment_mode=='Card'?'selected':'' }}>Card</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Payment Status</label>
                                            
                                            <select class="form-control" name="payment_status">
                                                <option value="">Select Payment Status</option>
                                                <option value="True" {{ $request->payment_status=='True'?'selected':'' }}>True</option>
                                                <option value="False" {{ $request->payment_status=='False'?'selected':'' }}>False</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Pending Approval</label>
                                            
                                            <select class="form-control" name="pending_approval">
                                                <option value="">Select Pending Approval</option>
                                                <option value="Not Approved" {{ $request->pending_approval=='Not Approved'?'selected':'' }}>Approved</option>
                                                <option value="Approved" {{ $request->pending_approval=='Approved'?'selected':'' }}>Not Approved</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Payment ID</label>
                                            
                                            <input type="text" name="payment_id" class="form-control" value="{{ $request->payment_id }}" placeholder="Search By Payment ID">

                                        </div>

                                        <div class="col-lg-4 form-group mt-4">
                                            <button class="btn btn-primary mt-1" type="submit">Search</button>
                                            <a href="{{ route('bookings') }}"><button class="btn btn-danger mt-1" type="button">Clear</button></a>
                                        </div>

                                    </div>

                                </form>
                                
                            </div>

                        </div>

                        <div class="card">

                            <div class="card-body card-card-body">                                

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped" >

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Transaction ID</th>

                                                <th>Slot Date & Time </th>
                                                
                                                <th>Company<br> Name</th>

                                                <th style="width: 65px;">Customer<br> Name</th>

                                                <th>Service<br> Name</th>

                                                <th>Service <br>Status</th>

                                                <th>Payment <br>Type / Collect</th>

                                                <th>Payment <br>Status</th>

                                                <th class="text-center">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($bookings))

                                            @foreach($bookings as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td> <strong> Source - {{ $value->booking_from }} </strong>
                                                <br> {{ $value->tran_id }}
                                                <br> {{ date('d F Y', strtotime($value->created_at)) }}
                                                </td>

                                                <td>
                                                	<!-- {{ date('d F Y', strtotime($value->created_at)) }} -->

												<strong> <u> {{ $value->date }}  </u></strong>

                                                <br> 
                                                
                                                <p style=" font-size: 14px !important;  margin-bottom: 0rem !important;  line-height: 18px; "> {{ $value->slot->name ?? '' }} </p>

                                             	</td>

                                                <td>
                                                    @if($value->seller && $value->seller->company_name)
                                                    {{  $value->seller->company_name ?? 'No Company' }}
                                                    @else
                                                    <?php
                                                        $seller  = App\Seller::where('user_id',$value->accept_user_id)->first();
                                                    ?>
                                                    {{ $seller?$seller->company_name:'No Company' }}
                                                    @endif
                                                </td>

                                                <td>
                                                	{{ $value->user->name ?? '' }}
                                                </td>



                                                <td>
                                                    
                                                    @if(isset($value->service_name))
                                                        {{ $value->service_name }}
                                                    @else
                                                        <?= App\Service::whereId($value->service_id)->first()->name; ?>
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if($value->status=='Accept')
                                                    <span class="text-success">Accepted</span>
                                                    @elseif($value->status=='Completed')
                                                    <span class="text-success">Completed</span>
                                                    @elseif($value->status=='Mark As Arrived')
                                                    <span class="text-info">Mark As Arrived</span>
                                                    @elseif($value->status=='Canceled')
                                                    <span class="text-danger">Canceled</span>
                                                    @elseif($value->status=='In Progress')
                                                    <span class="text-warning">In Progress</span>
                                                    @else
                                                    <span class="text-warning">Pending</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ $value->payment_moad }}
                                                    @if($value->payment_moad=='Cash')
                                                        @if($value->payment_collected=='Yes')
                                                            (<small class="text-success">Paid</small>)
                                                        @else
                                                            (<small class="text-warning">Pending</small>)
                                                        @endif
                                                    @endif

                                                    <?php
                                                        if($value->payment_moad=='Card'){
                                                            if(isset($value->payment_type)){
                                                                $pay_mode_type = $value->payment_type;

                                                                echo "<br> ( $pay_mode_type )";
                                                            }                                                            
                                                        }
                                                    ?>
                                                </td>

                                                <td>{{ $value->payment_status }}</td>

                                                
                                                <td class="text-center" id="action-buttons-booking">
                                                    @if($value->service_complete_approval =='Approved' && $value->status !=='Completed')  
                                                    <a href="{{ route('booking.completed',$value->id) }}" onclick="return confirm('Do you want to complete this service?')"><button type="button" class="btn btn-outline-dark btn-ft btn-sm" title="Service Completed" alt="Service Completed"><i class="fa fa-paper-plane" aria-hidden="true"></i></button></a>
                                                    @endif

                                                    @if($value->status !=='Canceled' && $value->status !=='Completed')
                                                    <button type="button" class="btn btn-outline-warning btn-ft btn-sm" data-toggle="modal" data-target="#exampleModal{{$value->id}}" title="Change Vendor" alt="Change Vendor"><i class="fa fa-cog" aria-hidden="true"></i></button>
                                                    @endif

                                                    <a href="{{ route('booking.view',$value->id) }}" target='_blank'><button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Edit" alt="Edit"><i class="fa fa-eye" aria-hidden="true"></i></button></a>

                                                    @if($value->booking_from=='Offline' && $value->status !=='Completed')
                                                    <a href="javascript:" onclick="payPayment({{$value->id}})" data-toggle="modal" data-target="#payPayment{{$value->id}}"><button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Pay Payment" alt="Pay Payment"><i class="fa fa-money" aria-hidden="true"></i></button></a>
                                                    @endif
                                                    
                                                    @if($value->status !=='Completed')
                                                    <a href="{{ route('booking.delete',$value->id) }}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>
                                                    @endif

                                                    @if($value->status !=='Canceled' && $value->status !=='Completed')
                                                    <a href="{{ route('booking.cencal',$value->id) }}" onclick="return confirm('Do you want to cancel the booking?')"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Cancel Booking" alt="Cancel Booking"><i class="fa fa-ban" aria-hidden="true"></i></button></a>
                                                    @endif

                                                    @if($value->booking_from=='Offline' && $value->status !=='Canceled' && $value->status !=='Completed')
                                                    <a href="{{ route('change.booking.date.time',encrypt($value->id)) }}" target="_blank"><button type="button" class="btn btn-outline-dark btn-ft btn-sm" title="Change Booking Slot and Date" alt="Change Booking Slot and Date"><i class="fa fa-calendar" aria-hidden="true"></i></button></a>
                                                    @endif

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Change Company</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('change.vendor') }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="booking_id" value="{{$value->id}}">
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                             <div class="form-group col-lg-12">
                                                                                    <select class="form-control" name="vendor_id">
                                                                                        <option value="">Select Company</option>
                                                                                        @foreach($vendors as $vendor)
                                                                                        <option value="{{$vendor->id}}" {{ $value->accept_user_id==$vendor->id?'selected':'' }}>
                                                                                            {{$vendor->seller?$vendor->seller->company_name:'No Company'}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                             </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Change</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Modal -->
                                                    <div class="modal fade" id="payPayment{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="payPaymentLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="payPaymentLabel">Update Payment</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('update.live.payment') }}" method="POST">
                                                                @csrf
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="booking_id" value="{{$value->id}}" class="booking_id">
                                                                        <div class="form-group">
                                                                            <label for="" style="float: left;">Payment Mode</label>
                                                                            <select name="payment_moad" class="form-control payment_moad" required>
                                                                                <option value="">Select Payment Mode</option>
                                                                                <option value="Cash" {{ $value->payment_moad=='Cash'?'selected':'' }}>Cash</option>
                                                                                <option value="Card" {{ $value->payment_moad=='Card'?'selected':'' }}>Card</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="" style="float: left;">Payment Transaction ID </label>
                                                                            <input type="text" name="tran_id" value="{{ $value->paymentTranId ?? $value->tabby_payment_response_id }}" class="form-control tran_id" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>

                                            </tr>

                                            @endforeach
                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                 <th>#</th>

                                                <th>Booking ID</th>

                                                <th>Creation Date</th>

                                                <th>Company Name</th>
                                                
                                                <th>Customer Name</th>

                                                <th>Service Name</th>

                                                <th>Service <br>Status</th>

                                                <th>Payment <br>Type</th>

                                                <th>Payment <br>Status</th>

                                                <th class="text-center">Action</th>

                                            </tr>

                                        </tfoot>

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
    

    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                searching: false
            } );
            
        } );
        
    </script>  

     <script>
        function changeVendor(params) {
            $('.booking_id').val(params);
            // $('.vendor_id').val(params.accept_user_id);
        }
    </script>

    <script>
        function payPayment(params) {
            $('.booking_id').val(params);
            // $('.payment_moad').val(params.payment_moad);
            // $('.tran_id').val(params.paymentLinkId);
        }
    </script>
@endsection         