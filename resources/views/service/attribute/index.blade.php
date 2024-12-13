@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Services Category</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ url('service') }}" class="btn btn-rounded bg-grad-4 ml-4">

                            <span class="btn-icon-left text-primary">

                                <i class="fa fa-arrow-left color-primary"></i> 

                            </span>Back

                        </a>
                      
                        <a href="{{ url('create/service/attribute/'.$service_id) }}" class="btn btn-rounded bg-grad-4 ml-4"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i> </span>Add Attribute</a>

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

                                        <div class="col-md-6"></div>

                                        <div class="col-md-4">

                                            <input type="text" class="form-control" name="search" value="{{$request->search}}" placeholder="Search by name" required="">

                                        </div>

                                        <div class="col-md-2">

                                            <label class="">&nbsp;</label>

                                            <button type="submit" class="btn btn-outline-info btn-ft">Search</button>

                                        </div>

                                    </div>

                                </form>

                                <div class="table-responsive">

                                    <table class="table table-border table-hover table-sm serviceattr" >

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Service</th>

                                                <th>Categories</th>

                                                <th>Sub Category</th>

                                                <th>Child Category</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($service_atr))

                                            @foreach($service_atr as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->service->name }}</td>

                                                <td>
                                                    {{ $value->category?$value->category->name:'No Category' }}
                                                </td>

                                               <td>
                                                    {{ $value->sub_category?$value->sub_category->name:'No Category' }}
                                                </td>

                                                <td>{{ $value->child_category?$value->child_category->name:'No Category' }}</td>

                                                <td class="text-right">

                                                    <a href="{{ url('service/attribute/items/list/'.$value->id.'/'.$value->service->id) }}"><button type="button" class="btn btn-outline-warning btn-sm btn-ft" title="Attribute List" alt="Attribute List">Attribute List</button></a>

                                                    <a href="{{ url('edit/service/attribute/'.$value->id) }}"><button type="button" class="btn btn-outline-info btn-ft" title="Edit" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                                    <a href="{{ url('delete/service/attribute/'.$value->id) }}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                </td>

                                            </tr>

                                            @endforeach

                                                @if ($service_atr->count() == 0)

                                                <tr class="text-center">

                                                    <td colspan="6">No service (s) to display.</td>

                                                </tr>

                                                @endif

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Service</th>

                                                <th>Categories</th>

                                                <th>Sub Category</th>

                                                <th>Child Category</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                                <div class="text-left float-left mt-1">

                                    <p>Displaying {{$service_atr->count()}} of {{ $service_atr->total() }} user(s).</p>

                                </div>

                                <div class="text-right float-right">

                                    {{ $service_atr->appends(request()->all())->links() }}

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
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>
<script>
    // $(document).ready(function() {
    //     $('#example').DataTable( {
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'colvis'
    //         ]
    //     } );
    // } );
</script>   