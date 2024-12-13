@extends('layouts.dashboard')

@section('content')

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="breadcrumb-range-picker">
                <h3 class="ml-1">Customers</h3>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        @include('flash_msg')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ url()->current() }}" class="d-none">
                            <div class="row text-right">
                                <div class="col-md-6"></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control right-search" name="search" value="{{ $request->search }}" placeholder="Search by name, email, phone">
                                </div>
                                <div class="col-md-2">
                                    <label class="">&nbsp;</label>
                                    <button type="submit" class="btn btn-outline-info btn-primary btn-ft">Search</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>DOB</th>
                                        <th>Gender</th>
                                        <th>Date</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Verified</th>
                                        <th>Reward Points</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $key => $value)
                                    <tr>
                                        <td>{{ $users->firstItem() + $key }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->phone }}</td>
                                        <td>{{ $value->DOB }}</td>
                                        <td>{{ $value->gender }}</td>
                                        <td>{{ changeDateFormate($value->created_at) }}</td>
                                        <td>
                                            @php $addresses = App\Address::where('user_id', $value->id)->get(); @endphp
                                            @foreach($addresses as $address)
                                                <small><b>{{ $address->address_type }}</b>, Flat No. {{ $address->flat_no }}, {{ $address->building }}, {{ $address->address }}, {{ $address->city->name ?? '' }}, {{ $address->locality_info->name ?? '' }}</small><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($value->is_active == 1)
                                                <a href="{{ route('customer.status', ['customer' => $value->id, 'status' => 0]) }}" onclick="return confirm('Are you sure to deactivate this customer?')"><span class="badge badge-success">Active</span></a>
                                            @else
                                                <a href="{{ route('customer.status', ['customer' => $value->id, 'status' => 1]) }}" onclick="return confirm('Are you sure to activate this customer?')"><span class="badge badge-danger">Inactive</span></a>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $value->is_verified == 1 ? 'badge-success' : 'badge-danger' }}">{{ $value->is_verified == 1 ? 'Yes' : 'No' }}</span>
                                        </td>
                                        <td>{{ $value->reward_points ?? 0 }}</td>
                                        <td class="text-center">
                                            @if($value->is_verified != 1)
                                                <a href="{{ route('customer.verified', $value->id) }}" onclick="return confirm('Are you sure to verify this customer?')">
                                                    <button type="button" class="btn btn-outline-warning btn-ft">Verify</button>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editModal{{ $key }}" title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            <!-- Modal for Editing -->
                                            <div class="modal fade" id="editModal{{ $key }}">
                                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Customer</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form action="{{ route('customer.update', $value->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body text-left">
                                                                <div class="form-group">
                                                                    <label class="text-dark">Full Name<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="name" value="{{ $value->name }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-dark">Email<span class="text-danger">*</span></label>
                                                                    <input type="email" class="form-control" name="email" value="{{ $value->email }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-dark">Phone<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="phone" value="{{ $value->phone }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-dark">Gender</label>
                                                                    <select class="form-control" name="gender">
                                                                        <option value="">Select Gender</option>
                                                                        <option value="Male" {{ $value->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                                        <option value="Female" {{ $value->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-dark">DOB</label>
                                                                    <input type="text" class="form-control" name="DOB" value="{{ $value->DOB }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-dark">Profile</label>
                                                                    <input type="file" class="form-control" name="profile">
                                                                    @if($value->profile)
                                                                        <img src="{{ asset('/uploads/user/'.$value->profile) }}" height="100px">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="text-center">No customers found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Display pagination links -->
                        <div class="pagination-wrapper">
                            {{ $users->links() }}
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

              <h4 class="modal-title">Customer</h4>

              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>

            

            <form action="{{ route('customer.store') }}" method="POST" id="customer_register">

                <!-- Modal body -->

                <div class="modal-body">

                    @csrf

                    <div class="form-group">

                        <label>Full Name<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" placeholder="Full Name" value="{{old('name')}}" required>

                    </div>

                    <div class="form-group">

                        <label>Email<span class="text-danger">*</span></label>

                        <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Email" >

                    </div>

                    <div class="form-group">

                        <label>Phone<span class="text-danger">*</span></label>

                        <input type="number" class="form-control" name="phone" value="{{old('phone')}}" placeholder="Phone" required>

                    </div>

                    <div class="form-group">

                        <label>Password<span class="text-danger">*</span></label>

                        <input type="password" class="form-control" name="password" value="{{old('password')}}" placeholder="Password" required>

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
            "paging": false,
             dom: 'Bfrtip',
                buttons: [
                   // {
                   //     extend: 'pdf',
                   //     footer: true,
                   //     exportOptions: {
                   //          columns: [1,2,3,4,5,6,7]
                   //      }
                   // },
                   {
                       extend: 'csv',
                       footer: false,
                       exportOptions: {
                            columns: [1,2,3,4,5,6,7]
                        }
                      
                   },
                   {
                       extend: 'excel',
                       footer: false,
                       exportOptions: {
                            columns: [1,2,3,4,5,6,7]
                        }
                   }         
                ]
        } );
    } );
    
</script>  
@endsection 



       