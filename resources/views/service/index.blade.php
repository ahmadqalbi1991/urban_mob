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

                            <h3 class="ml-1">Services</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                      
                        <a href="{{url('service/create')}}" class="btn btn-rounded bg-grad-4 ml-4"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i> </span>Add Service</a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <form method="GET" action="" class="d-none">

                                    <div class="row">

                                        <div class="col-md-4"></div>
                                        
                                        <div class="col-md-4">

                                            <input type="text" class="form-control" name="search" value="{{$request->search}}" placeholder="Search by name" required="">

                                        </div>

                                        <div class="col-md-4">

                                            <label class="">&nbsp;</label>

                                            <button type="submit" class="btn btn-outline-info btn-ft">Search</button>

                                            <label class="">&nbsp;</label>
                                            <a href="{{url('service')}}"><button type="button" class="btn btn-outline-warning btn-ft">Back</button></a>

                                        </div>

                                    </div>

                                </form>

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped" >

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Service</th>

                                                <th>Category</th>

                                                <th>Price</th>

                                                <th>Featured</th>

                                                <th>Status</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($services))

                                            @foreach($services as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->name }}</td>

                                                <td>{{ $value->category?$value->category->name:'NA' }}</td>

                                                <td>{{ Session::get('currencies') }} {{ $value->price }}</td>

                                                <td>

                                                    @if($value->featured==1)

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Yes</span>

                                                    @else

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">No</span>

                                                    @endif

                                                </td>

                                                <td>

                                                    @if($value->status==1)

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Active</span>

                                                    @else

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">Inactive</span>

                                                    @endif

                                                </td>

                                                <td class="text-right">

                                                <a href="{{ url('service/attributes/'.$value->id) }}"><button type="button" class="btn btn-outline-warning btn-ft" title="Manage Attribute" alt="Manage Attribute"><i class="fa fa-cog" aria-hidden="true"></i></button></a>

                                                <a href="{{ url('service/'.$value->id) }}"><button type="button" class="btn btn-outline-info btn-ft" title="Edit" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                                <a href="{{ route('service.delete',$value->id) }}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                </td>

                                            </tr>

                                            @endforeach

                                            
                                            @endif

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
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
            } );
        } );
        
    </script>  
@endsection         