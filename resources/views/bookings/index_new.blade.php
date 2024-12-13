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

    .pd-1 {
        padding: 2% !important;
    }

    .mr-5 {
        margin-right: 5px !important;
        margin-top: 5px !important;
    }

    #posts_filter {
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

                                <form action="{{ route('bookings.search') }}" method="Get">
                                    
                                    <div class="row">
                                    
                                        <div class="col-lg-3 form-group">

                                            <label>Booking ID</label>
                                            
                                            <input type="text" name="booking_id" class="form-control" placeholder="Search By Booking ID">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Slot Date</label>
                                            
                                            <input type="date" name="slot_date" class="form-control" placeholder="Search By Slot Date">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Vendor Company</label>
                                            
                                            <input type="text" name="vendor_name" class="form-control" placeholder="Search By Vendor Company">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Customer Name</label>
                                            
                                            <input type="text" name="customer_name" class="form-control" placeholder="Search By Customer Name">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Customer Mobile No.</label>
                                            
                                            <input type="number" name="customer_number" class="form-control" placeholder="Search By Customer Mobile Number">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Service Name</label>
                                            
                                            <input type="text" name="service_name" class="form-control" placeholder="Search By Service Name">

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Service Status</label>
                                            
                                            <select class="form-control" name="service_status">
                                                <option value="">Select Service Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Accept">Accept</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Mark As Arrived">Mark As Arrived</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Canceled">Canceled</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Payment Mode</label>
                                            
                                            <select class="form-control" name="payment_mode">
                                                <option value="">Select Payment Mode</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Card">Card</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Payment Status</label>
                                            
                                            <select class="form-control" name="payment_status">
                                                <option value="">Select Payment Status</option>
                                                <option value="True">True</option>
                                                <option value="False">False</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Pending Approval</label>
                                            
                                            <select class="form-control" name="pending_approval">
                                                <option value="">Select Pending Approval</option>
                                                <option value="Approved">Not Approved</option>
                                                <option value="Not Approved">Approved</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 form-group">

                                            <label>Payment ID</label>
                                            
                                            <input type="text" name="payment_id" class="form-control" placeholder="Search By Payment ID">

                                        </div>

                                        <div class="col-lg-3 form-group mt-4">
                                            <button class="btn btn-primary mt-1" type="submit">Search</button>
                                            <button class="btn btn-danger mt-1" type="reset">Clear</button>
                                        </div>

                                    </div>

                                </form>
                                
                            </div>

                        </div>

                        <div class="card pd-1">

                            <div class="card-body card-card-body">                                

                                <div class="table-responsive">

                                    <table id="posts" class="table table-striped" >

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

        <!-- Modal -->
        <div class="modal fade" id="payPayment" tabindex="-1" role="dialog" aria-labelledby="payPaymentLabel" aria-hidden="true">
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
                            <input type="hidden" name="booking_id" class="booking_id">
                            <div class="form-group">
                                <label for="">Payment Mode</label>
                                <select name="payment_moad" class="form-control payment_moad" required>
                                    <option value="">Select Payment Mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Payment Transaction ID </label>
                                <input type="text" name="tran_id" class="form-control tran_id" required>
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <input type="hidden" name="booking_id" class="booking_id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <select class="form-control vendor_id" name="vendor_id">
                                        <option value="">Select Company</option>
                                        @foreach($vendors as $vendor)
                                        <option value="{{$vendor->id}}"> {{$vendor->seller?$vendor->seller->company_name:'No Company'}}</option>
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

@endsection 

  
@section('script')


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    

    <script>
        
        
    </script>

    <script>
        $(document).ready(function () {
            $('#posts').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollX": true,
                "pageLength": 25,
                "searching": true,
                "bSort": true,
                "ajax":{
                         "url": "{{ route('get.bookings') }}",
                         "dataType": "json",
                         "type": "POST",
                         "data":{ _token: "{{csrf_token()}}"}
                       },
                "columns": [
                    { "data": "No" },
                    { "data": "BookingId" },
                    { "data": "SlotDate" },
                    { "data": "CompanyName" },
                    { "data": "CustomerName" },
                    { "data": "ServiceName" },
                    { "data": "ServiceStatus" },
                    { "data": "PaymentType" },
                    { "data": "PaymentStatus" },
                    { "data": "options" }
                ]    

            });
        });
    </script>

    <script>
        function payPayment(params) {
            $('.booking_id').val(params.id);
            $('.payment_moad').val(params.payment_moad);
            $('.tran_id').val(params.paymentLinkId);
        }
    </script>

    <script>
        function changeVendor(params) {
            $('.booking_id').val(params.id);
            $('.vendor_id').val(params.accept_user_id);
        }
    </script>
@endsection         