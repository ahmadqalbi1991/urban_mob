@extends('layouts.dashboard')

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

                            <h3 class="ml-1">Vendor</h3>

                        </div>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <form method="GET" action="" class="d-none">

                                    <div class="row text-right">

                                        <div class="col-md-6">
                                        </div>

                                        <div class="col-md-4">

                                            <input type="text" class="form-control right-search" name="search" value="{{$request->search}}" placeholder="Search by name, email, phone" required="">

                                        </div>

                                        <div class="col-md-2">

                                            <label class="">&nbsp;</label>

                                            <button type="submit" class="btn btn-outline-success bg-grad-4 btn-ft">Search</button>

                                        </div>

                                    </div>

                                </form>

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped">

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Name</th>

                                                <th>Company</th>

                                                <th>Email</th>

                                                <th>Phone</th>

                                                <th>Date</th>

                                                <th>Status</th>

                                                <th>Verified</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($users))

                                            @foreach($users as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td> {{ $value->name }} </td>

                                                <td> {{ $value->seller?$value->seller->company_name:'' }} </td>

                                                <td>{{ $value->email }}</td>

                                                <td>{{ $value->phone }}</td>

                                                <td>{{ changeDateFormate($value->created_at) }}</td>

                                                <td>

                                                    @if($value->is_active==1)

                                                    <a href="{{ route('vendor.status',['vendor' => $value->id, 'status' =>0]) }}" onclick="return confirm('Are you sure to de-activate this vendor')"><span class="badge mb-2 mb-xl-0 badge-pill badge-success">Active</span></a>

                                                    @else

                                                    <a href="{{ route('vendor.status',['vendor' => $value->id, 'status' =>1]) }}" onclick="return confirm('Are you sure to activate this vendor')"><span class="badge mb-2 mb-xl-0 badge-pill badge-danger">Inactive</span></a>

                                                    @endif

                                                </td>

                                                <td>

                                                    @if($value->is_verified==1)

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Yes</span>

                                                    @else

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">No</span>

                                                    @endif

                                                </td>

                                                <td class="text-right">

                                                    

                                                    @if($value->is_verified!==1)

                                                    <a href="{{ route('vendor.verified',$value->id) }}" onclick="return confirm('Are you sure verify this vendor')"><button type="button" class="btn btn-outline-warning btn-ft">Verify</button></a>

                                                    @endif
                                                    

                                                    <a href="{{route('vendor.details',$value->id)}}"><button type="button" title="Detail" alt="Detail" class="btn btn-outline-info btn-ft"><i class="fa fa-cog" aria-hidden="true"></i></button></a>

                                                    <a href="{{route('vendor.view',$value->id)}}"><button type="button" title="View" alt="View" class="btn btn-outline-info btn-ft"><i class="fa fa-eye" aria-hidden="true"></i></button></a>

                                                    <a href="{{route('vendor.edit',$value->id)}}"><button type="button" title="Edit" alt="Edit" class="btn btn-outline-info btn-ft"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                                    <a href="{{ route('vendor.delete',encrypt($value->id)) }}" title="Delete" alt="Delete"><button onclick="return confirm('Are you sure delete this vendor')" type="button" class="btn btn-outline-primary btn-ft"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                </td>

                                            </tr>

                                            @endforeach

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Name</th>

                                                <th>Company</th>

                                                <th>Email</th>

                                                <th>Phone</th>

                                                <th>Date</th>

                                                <th>Status</th>

                                                <th>Verified</th>

                                                <th class="text-right">Action</th>

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



        <!-- The Modal -->

      <div class="modal fade" id="addModal">

        <div class="modal-dialog modal-dialog-centered">

          <div class="modal-content">

          

            <!-- Modal Header -->

            <div class="modal-header">

              <h4 class="modal-title">Add Vendor</h4>

              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>

            

            <form action="{{ route('vendor.store') }}" method="POST" id="vendor_register">

                <!-- Modal body -->

                <div class="modal-body">

                    @csrf

                    <div class="form-group">

                        <label>Full Name<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" placeholder="" >

                    </div>

                    <div class="form-group">

                        <label>Email<span class="text-danger">*</span></label>

                        <input type="email" class="form-control" name="email" placeholder="" >

                    </div>

                    <div class="form-group">

                        <label>Phone<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="phone" placeholder="" >

                    </div>

                    <div class="form-group">

                        <label>Role<span class="text-danger">*</span></label>

                        <select class="form-control" name="role" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{$role->name}}">{{$role->name}}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="form-group">

                        <label>Password<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="password" placeholder="" >

                    </div>

                </div>

                

                <!-- Modal footer -->

                <div class="modal-footer">

                <button type="submit" class="btn btn-success bg-grad-4 ">Submit</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

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