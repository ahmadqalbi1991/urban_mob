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

                            <h3 class="ml-1">Attributes</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <button type="button" class="btn btn-rounded bg-grad-4 ml-4"  data-toggle="modal" data-target="#addModal"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i> </span>Add Attribute</button>


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

                                        <div class="col-md-6"></div>

                                        <div class="col-md-4">

                                            <input type="text" class="form-control right-search" name="search" value="{{$request->search}}" placeholder="Search by attribute">

                                        </div>

                                        <div class="col-md-2">

                                            <label class="">&nbsp;</label>

                                            <button type="submit" class="btn btn-outline-info bg-grad-4 btn-ft">Search</button>

                                        </div>

                                    </div>

                                </form>

                                <div class="table-responsive">

                                     <table id="example" class="table table-striped" >

                                        <thead>

                                            <tr>

                                                <th width="5%">#</th>

                                                <th width="20%">Name</th>

                                                <th width="40%">Value</th>

                                                <th valign="20%" class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($attribute))

                                            @foreach($attribute as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->name }}</td>

                                                <td>
                                                    @foreach($value->attributeValue as $key => $attval)
                                                    <span class="badge badge-secondary badge-inline badge-md">{{ $attval->value }}</span>
                                                    @endforeach
                                                </td>

                                                <td class="text-right">

                                                <a href="{{ route('attribute.manage',encrypt($value->id)) }}"><button type="button" class="btn btn-outline-warning btn-ft btn-sm" title="Manage Attribute" alt="Manage Attribute"><i class="fa fa-cog" aria-hidden="true"></i></button></a>

                                                <button type="button" class="btn btn-outline-info btn-ft btn-sm" data-toggle="modal" data-target="#editModal{{$value->id}}"title="Edit" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                                <a href="{{ route('attribute.delete',encrypt($value->id)) }}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                     <!-- The Modal -->

                                                        <div class="modal fade" id="editModal{{$value->id}}">

                                                            <div class="modal-dialog modal-dialog-centered modal-sm">

                                                            <div class="modal-content">

                                                            

                                                                <!-- Modal Header -->

                                                                <div class="modal-header">

                                                                <h4 class="modal-title">Attribute</h4>

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                </div>

                                                                

                                                                <form action="{{ route('attribute.update',$value->id) }}" method="POST">

                                                                    <!-- Modal body -->

                                                                    <div class="modal-body">

                                                                        @csrf

                                                                        <div class="form-group text-left">

                                                                            <label>Name <span class="text-danger">*</span></label>

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

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Name</th>

                                                <th>Value</th>

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

        <div class="modal-dialog modal-dialog-centered modal-sm">

          <div class="modal-content">

          

            <!-- Modal Header -->

            <div class="modal-header">

              <h4 class="modal-title">Attribute</h4>

              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>

            

            <form action="{{ route('attribute.store') }}" method="POST">

                <!-- Modal body -->

                <div class="modal-body">

                    @csrf

                    <div class="form-group">

                        <label>Name<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" placeholder="" >

                    </div>

                </div>

                

                <!-- Modal footer -->

                <div class="modal-footer">

                <button type="submit" class="btn btn-success bg-grad-4">Submit</button>

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
       