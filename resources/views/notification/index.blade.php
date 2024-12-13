@extends('layouts.dashboard')
<style>
    .count-card {
        color: aliceblue;
        padding: 2%;
    }
    .count {
        float: right;
    }

    .dt-buttons {
            display: none;
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

                            <h3 class="ml-1">Notifications</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vendorModal">
                          Send Vendor
                        </button>
                        &nbsp;&nbsp;
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#customerModal">
                          Send Customer
                        </button>

                    </div>

                

                <div class="container-fluid">

                    @include('flash_msg')

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="card">

                                <div class="card-body">

                                    <div class="custom-tab-2">

                                        <ul class="nav nav-tabs nav-justified mb-4">

                                            <li class="nav-item"><a class="nav-link {{$type=='vendor'?'active':''}}" href="{{url('vendor/notifications/')}}">Vendors</a></li>
                                           
                                            <li class="nav-item"><a class="nav-link {{$type=='customer'?'active':''}}" href="{{url('customer/notifications/')}}">Customers</a></li>

                                        </ul>

                                        <div class="table-responsive">

                                            <table id="example" class="table table-striped" >

                                                <thead>

                                                    <tr>

                                                        <th>S.N.</th>

                                                        <th>Title</th>

                                                        <th>Description</th>

                                                        <th>Date</th>

                                                        <th>Action</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @if(!empty($data))

                                                    @foreach($data as $key=>$value)

                                                    <tr>

                                                        <td>{{ ++$key }}</td>

                                                        <td>{{ $value->title }}</td>

                                                        <td>{{ $value->description }}</td>

                                                        <td>{{ date("d-m-Y", strtotime($value->created_at)) }}</td>

                                                        <td> <a href="{{ route('notifications.delete',$value->id) }}" onclick="return confirm('Are you sure delete this?')" class="btn btn-outline-danger btn-sm">Delete</a> </td>

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

                                                        <th>Title</th>

                                                        <th>Description</th>

                                                        <th>Date</th>

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

            </div>

        </div>

        <!--**********************************

            Content body end

        ***********************************-->



        <!-- Modal -->
        <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">Send Customer Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                   <form action="{{ url('send/notification') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" placeholder="Enter Title" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="description" placeholder="Enter Description"></textarea>
                            </div>
                            <input type="hidden" name="type" value="Customer">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="vendorModal" tabindex="-1" role="dialog" aria-labelledby="vendorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="vendorModalLabel">Send Vendor Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                   <form action="{{ url('send/notification') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" placeholder="Enter Title" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="description" placeholder="Enter Description"></textarea>
                            </div>
                            <input type="hidden" name="type" value="Vendor">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Send</button>
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



       