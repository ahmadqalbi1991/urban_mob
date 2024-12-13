@extends('layouts.dashboard')
<!-- <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet"> -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->
@section('content')
<style>
    .dt-buttons {
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

                            <h3 class="ml-1">Draft Bookings</h3>

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

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped" >

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Transaction ID</th>

                                                <th>Creation Date</th>

                                                <th>Customer Name</th>

                                                <th>Service Name</th>

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

                                                <td>{{ $value->tran_id }}</td>

                                                <td>{{ date('d F Y', strtotime($value->created_at)) }}</td>

                                                <td>{{ $value->user?$value->user->name:'' }}</td>

                                                <td>{{ $value->service?$value->service->name:'' }}</td>
                                                
                                                <td>{{ $value->status }}</td>

                                                <td>
                                                    {{ $value->payment_moad }}
                                                    @if($value->payment_moad=='Cash')
                                                        @if($value->payment_collected=='Yes')
                                                            <small>(Paid)</small>
                                                        @else
                                                            <small>(Pending)</small>
                                                        @endif
                                                    @endif
                                                </td>

                                                <td>{{ $value->payment_status }}</td>

                                                
                                                <td class="text-center">

                                                    <a href="{{ route('booking.view',$value->id) }}"><button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Edit" alt="Edit"><i class="fa fa-eye" aria-hidden="true"></i></button></a>

                                                    <a href="{{ route('booking.delete',$value->id) }}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

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
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
            } );
        } );
        
    </script>  
@endsection         