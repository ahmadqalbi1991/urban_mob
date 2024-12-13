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

                            <h3 class="ml-1">Coupons</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">


                        <a href="{{url('create/coupon')}}">

                            <button type="button" class="btn btn-rounded bg-grad-4 ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-plus color-primary"></i> 

                                </span>
                                Add Coupon

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

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped" >

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Code</th>

                                                <th>Amount</th>

                                                <th>Type</th>

                                                <th>Max Users</th>

                                                <th>Amount</th>

                                                <th>Start Date</th>

                                                <th>End Date</th>

                                                <th>Status</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($coupons))

                                            @foreach($coupons as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->code }}</td>

                                                <td>{{ $value->amount }}</td>

                                                <td>{{ $value->type }}</td>

                                                <td>{{ $value->user_used }}</td>

                                                <td>
                                                    @if($value->max_amount)

                                                        <small><b>Min Amount</b> : {{$value->min_amount}}</small><br>
                                                        <small><b>Max Amount</b> : {{$value->max_amount}}</small>

                                                    @else

                                                    <p>Min Amount : {{$value->min_amount}}</p>

                                                    @endif
                                                </td>

                                                <td>{{ date("d-m-Y", strtotime($value->start_date)) }}</td>

                                                <td>{{ date("d-m-Y", strtotime($value->end_date)) }}</td>

                                                <td>
                                                    @if($value->status=='1')
                                                    <span class="badge badge-success">Active</span>
                                                    @else
                                                    <span class="badge badge-primary">Inactive</span>
                                                    @endif
                                                </td>
                                                
                                                <td class="text-right">
                                                    @if($value->status=='1')
                                                        <a href="{{url('status/coupon/'.$value->id.'/0')}}"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Inactive" alt="Inactive">Inactive</button></a>
                                                    @endif

                                                <!-- <a href="{{url('edit/coupon/'.$value->id)}}"><button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Edit" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a> -->

                                                <a href="{{url('delete/coupon/'.$value->id)}}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                </td>

                                            </tr>

                                            @endforeach

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Code</th>

                                                <th>Amount</th>

                                                <th>Type</th>

                                                <th>Max Users</th>

                                                <th>Amount</th>

                                                <th>Start Date</th>

                                                <th>End Date</th>

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
       