@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Roles</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ route('create.role') }}"><button type="button" class="btn btn-rounded bg-grad-4 ml-4"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i> </span>Add Role</button></a>


                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <form method="GET" action="">

                                    <div class="row text-right">
                                        <div class="col-md-6">
                                        </div>

                                        <div class="col-md-4">

                                            <input type="text" class="form-control search-field right-search" name="search" value="{{$request->search}}" placeholder="Search by Role" required="">

                                        </div>

                                        <div class="col-md-2">

                                            <label class="">&nbsp;</label>

                                            <button type="submit" class="btn btn-outline-info bg-grad-4 btn-ft btn-sm">Search</button>

                                        </div>

                                    </div>

                                </form>

                                <hr>

                                <div class="table-responsive">

                                    <table class="table table-border table-hover table-sm" >

                                        <thead>

                                            <tr>

                                                <th width="5%">#</th>

                                                <th width="60%">Name</th>

                                                <th width="35%" class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($roles))

                                            @foreach($roles as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->name }}</td>

                                                <td class="text-right">

                                                <a href="{{ route('role.permission',$value->id) }}" title="Permission" alt="Permission"><button type="button" class="btn btn-outline-warning btn-ft btn-sm"><i class="fa fa-cog" aria-hidden="true"></i></button></a>

                                                <button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Edit" alt="Edit" data-toggle="modal" data-target="#editModal{{$key}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                                 <a href="{{ route('role.delete',encrypt($value->id)) }}" title="Delete" alt="Delete" onclick="return confirm('Are you sure to delete this role')"><button type="button" class="btn btn-outline-danger btn-ft btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                     <!-- The Modal -->

                                                        <div class="modal fade" id="editModal{{$key}}">

                                                            <div class="modal-dialog modal-dialog-centered modal-sm">

                                                            <div class="modal-content">

                                                            

                                                                <!-- Modal Header -->

                                                                <div class="modal-header">

                                                                <h4 class="modal-title">Edit Role</h4>

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                </div>

                                                                

                                                                <form action="{{ route('role.update',$value->id) }}" method="POST">

                                                                    <!-- Modal body -->

                                                                    <div class="modal-body text-left">

                                                                        @csrf

                                                                        <div class="form-group">

                                                                            <label>Role<span class="text-danger">*</span></label>

                                                                            <input type="text" class="form-control" name="name" value="{{ $value->name }}" placeholder="" required>

                                                                        </div>

                                                                    </div>

                                                                    

                                                                    <!-- Modal footer -->

                                                                    <div class="modal-footer">

                                                                    <button type="submit" class="btn btn-success bg-grad-4">Update</button>

                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                                                                    </div>

                                                                </form>

                                                                

                                                            </div>

                                                            </div>

                                                        </div>

                                                </td>

                                            </tr>

                                            @endforeach

                                                @if ($roles->count() == 0)

                                                <tr class="text-center">

                                                    <td colspan="6">No roles to display.</td>

                                                </tr>

                                                @endif

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Name</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                                 <div class="text-left float-left mt-1">

                                    <p>Displaying {{$roles->count()}} of {{ $roles->total() }} roles.</p>

                                </div>

                                <div class="text-right float-right">

                                    {{ $roles->appends(request()->all())->links() }}

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

        <div class="modal-dialog modal-dialog-centered modal-sm">

          <div class="modal-content">

          

            <!-- Modal Header -->

            <div class="modal-header">

              <h4 class="modal-title">Add Roles</h4>

              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>

            

            <form action="{{ route('role.store') }}" method="POST" id="vendor_register">

                <!-- Modal body -->

                <div class="modal-body">

                    @csrf

                    <div class="form-group">

                        <label>Role<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" placeholder="" >

                    </div>

                </div>

                

                <!-- Modal footer -->

                <div class="modal-footer">

                <button type="submit" class="btn btn-success">Submit</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                </div>

            </form>

            

          </div>

        </div>

      </div>

@endsection      



       