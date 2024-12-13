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

                            <h3 class="ml-1">Sub Categories</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{route('sub.category.create')}}">

                            <button type="button" class="btn btn-rounded bg-grad-4 ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-plus color-primary"></i> 

                                </span>
                                Add Sub Category

                            </button>

                        </a>

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

                                            <input type="text" class="form-control right-search" name="search" value="{{$request->search}}" placeholder="Search by sub category">

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

                                                <th>#</th>

                                                <th>Icon</th>

                                                <th>Category</th>

                                                <th>Sub Category</th>

                                                <th>Status</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($category))

                                            @foreach($category as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>
                                                    @if($value->icon)
                                                    <img src="{{ url('/uploads/category/'.$value->icon) }}" height="50"> 
                                                    @endif
                                                </td>

                                                <td>{{ App\Category::where('id',$value->parent_id)->value('name') }}</td>

                                                <td>{{ $value->name }}</td>

                                                <td>
                                                    
                                                    @if($value->status==1)

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Active</span>

                                                    @else

                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">Inactive</span>

                                                    @endif
                                                    

                                                </td>

                                                <td class="text-right">

                                                <a href="{{ route('category.edit',encrypt($value->id)) }}"><button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Edit" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                                <a href="{{ route('category.delete',encrypt($value->id)) }}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                </td>

                                            </tr>

                                            @endforeach

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Icon</th>

                                                <th>Category</th>

                                                <th>Sub Category</th>

                                                <th>Status</th>

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

              <h4 class="modal-title">Category</h4>

              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>

            

            <form action="{{ route('category.store') }}" method="POST" id="vendor_register">

                <!-- Modal body -->

                <div class="modal-body">

                    @csrf

                    <div class="form-group">

                        <label>Name<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" placeholder="" >

                    </div>

                    <div class="form-group">

                        <label>Meta Title<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="meta_title" placeholder="" >

                    </div>

                    <div class="form-group">

                        <label>Meta Description<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="meta_description" ></textarea>

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
                 dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
            } );
        } );
        
    </script>  
@endsection  

       