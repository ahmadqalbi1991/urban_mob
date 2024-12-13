@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Users</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <button type="button" class="btn btn-rounded btn-primary"  data-toggle="modal" data-target="#addModal"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i> </span>Add User</button>

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

                                        <div class="col-md-6"></div>
                                        <div class="col-md-4">

                                            <input type="text" class="form-control" name="search" value="{{$request->search}}" placeholder="Search by name, email, phone" required="">

                                        </div>

                                        <div class="col-md-2">

                                            <label class="">&nbsp;</label>

                                            <button type="submit" class="btn btn-outline-info btn-ft">Search</button>

                                        </div>

                                    </div>

                                </form>

                                <hr>

                                <div class="table-responsive">

                                    <table  class="display table table-border table-hover table-sm">

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Name</th>

                                                <th>Email/Phone</th>

                                                <th>Registered</th>

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

                                                <td>{{ $value->email }}<br>{{ $value->phone }}</td>

                                                <td>{{ changeDateFormate($value->created_at) }}</td>

                                                <td>

                                                    @if($value->is_active==1)

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Active</span>

                                                    @else

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">Inactive</span>

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

                                                    <a href="{{ route('customer.verified',$value->id) }}" onclick="return confirm('Are you sure email verify this user')"><button type="button" class="btn btn-outline-warning btn-ft">Verify</button></a>

                                                    @endif

                                                    @if($value->is_active==1)

                                                    <a href="{{ route('customer.status',['customer' => $value->id, 'status' =>0]) }}" onclick="return confirm('Are you sure to de-activate this user')"><button type="button" class="btn btn-outline-danger btn-ft">Inactive</button></a>

                                                    @else

                                                    <a href="{{ route('customer.status',['customer' => $value->id, 'status' =>1]) }}" onclick="return confirm('Are you sure to activate this user')"><button type="button" class="btn btn-outline-success btn-ft">Active</button></a>

                                                    @endif

                                                    <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editModal{{$key}}">Edit</button>

                                                 <div class="modal fade" id="editModal{{$key}}">

                                                            <div class="modal-dialog modal-dialog-centered modal-sm">

                                                            <div class="modal-content">

                                                            

                                                                <!-- Modal Header -->

                                                                <div class="modal-header">

                                                                <h4 class="modal-title">User</h4>

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                </div>

                                                                

                                                                <form action="{{ route('customer.update',$value->id) }}" method="POST">

                                                                    <!-- Modal body -->

                                                                    <div class="modal-body">

                                                                        @csrf

                                                                        <div class="form-group">

                                                                            <label>Full Name<span class="text-danger">*</span></label>

                                                                            <input type="text" class="form-control" name="name" value="{{ $value->name }}" placeholder="" required>

                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label>Email<span class="text-danger">*</span></label>

                                                                            <input type="email" class="form-control" name="email" value="{{ $value->email }}" placeholder="" required>

                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label>Phone<span class="text-danger">*</span></label>

                                                                            <input type="text" class="form-control" name="phone" value="{{ $value->phone }}" placeholder="" required>

                                                                        </div>

                                                                    </div>

                                                                    

                                                                    <!-- Modal footer -->

                                                                    <div class="modal-footer">

                                                                    <button type="submit" class="btn btn-success">Update</button>

                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                                                                    </div>

                                                                </form>

                                                                

                                                            </div>

                                                            </div>

                                                        </div>

                                                </td>

                                            </tr>

                                            @endforeach

                                                @if ($users->count() == 0)

                                                <tr class="text-center">

                                                    <td colspan="6">No user (s) to display.</td>

                                                </tr>

                                                @endif

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Name</th>

                                                <th>Email/Phone</th>

                                                <th>Registered</th>

                                                <th>Status</th>

                                                <th>Email Verified</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                                <div class="text-left float-left mt-1">

                                    <p>Displaying {{$users->count()}} of {{ $users->total() }} user(s).</p>

                                </div>

                                <div class="text-right float-right">

                                    {{ $users->appends(request()->all())->links() }}

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

              <h4 class="modal-title">User</h4>

              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>

            

            <form action="{{ route('user.store') }}" method="POST" id="customer_register">

                <!-- Modal body -->

                <div class="modal-body">

                    @csrf

                    <div class="form-group">

                        <label>Full Name<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" value="{{old('name')}}" required placeholder="Full Name" >

                    </div>

                    <div class="form-group">

                        <label>Email<span class="text-danger">*</span></label>

                        <input type="email" class="form-control" name="email" value="{{old('email')}}" required placeholder="Email" >

                    </div>

                    <div class="form-group">

                        <label>Phone<span class="text-danger">*</span></label>

                        <input type="number" class="form-control" name="phone" value="{{old('phone')}}" required placeholder="Phone" >

                    </div>

                    <div class="form-group">

                        <label>Password<span class="text-danger">*</span></label>

                        <input type="password" class="form-control" name="password" required placeholder="Password" >

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



       