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

                            <h3 class="ml-1">Operator</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <button type="button" class="btn btn-rounded bg-grad-4 ml-4"  data-toggle="modal" data-target="#addModal"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i> </span>Add Operator</button>


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

                                    <table id="example" class="table table-striped" >

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Name</th>

                                                <th>Email</th>

                                                <th>Phone</th>

                                                <th>Date</th>

                                                <th>Status</th>

                                                <th>Email Verified</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($users))

                                            @foreach($users as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->name }}</td>

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

                                                    <a href="{{ route('vendor.verified',$value->id) }}" onclick="return confirm('Are you sure email verify this vendor')"><button type="button" class="btn btn-outline-warning btn-ft">Verify</button></a>

                                                    @endif

                                                <button type="button" title="Edit" alt="Edit" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editModal{{$key}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                                <a href="{{ route('vendor.delete',encrypt($value->id)) }}" title="Delete" alt="Delete"><button onclick="return confirm('Are you sure delete this vendor')" type="button" class="btn btn-outline-primary btn-ft"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                     <!-- The Modal -->

                                                        <div class="modal fade" id="editModal{{$key}}">

                                                            <div class="modal-dialog modal-dialog-centered">

                                                            <div class="modal-content">

                                                            

                                                                <!-- Modal Header -->

                                                                <div class="modal-header">

                                                                <h4 class="modal-title">Edit Operator</h4>

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                </div>

                                                                

                                                                <form action="{{ route('vendor.update',$value->id) }}" method="POST">

                                                                    <!-- Modal body -->
                                                                    <input type="hidden" name="id" value="{{$value->id}}">
                                                                    <input type="hidden" name="from" value="Operator">
                                                                    <div class="modal-body text-left">

                                                                        @csrf

                                                                        <div class="form-group">

                                                                            <label class="text-dark">Full Name<span class="text-danger">*</span></label>

                                                                            <input type="text" class="form-control" name="name" value="{{ $value->name }}" placeholder="" required>

                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label class="text-dark">Email<span class="text-danger">*</span></label>

                                                                            <input type="email" class="form-control" name="email" value="{{ $value->email }}" placeholder="" required>

                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label class="text-dark">Phone<span class="text-danger">*</span></label>

                                                                            <input type="text" class="form-control" name="phone" value="{{ $value->phone }}" placeholder="" required>

                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label class="text-dark">Role<span class="text-danger">*</span></label>

                                                                            <select class="form-control" name="role" required>
                                                                                <option value="">Select Role</option>
                                                                                @foreach($roles as $role)
                                                                                <option value="{{$role->name}}" {{ $value->getRoleNames()->contains($role->name) ? 'selected' : "" }}>{{$role->name}}</option>
                                                                                @endforeach
                                                                            </select>

                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label class="text-dark">Password</label>

                                                                            <input type="text" class="form-control" name="password" value="" placeholder="" >

                                                                        </div>

                                                                    </div>

                                                                    

                                                                    <!-- Modal footer -->

                                                                    <div class="modal-footer">

                                                                    <button type="submit" class="btn btn-success bg-grad-4 ">Update</button>

                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

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

                                                <th>Name</th>

                                                <th>Email</th>

                                                <th>Phone</th>

                                                <th>Date</th>

                                                <th>Status</th>

                                                <th>Email Verified</th>

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

              <h4 class="modal-title">Add Operator</h4>

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