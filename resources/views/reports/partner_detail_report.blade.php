@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Partner Report</h3>

                        </div>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <form method="GET" action="">

                                    <div class="row text-left">

                                        <div class="col-md-3">
                                            
                                            <label class="text-left">From Date</label>

                                            <input type="date" class="form-control" name="from_date" value="{{$from_date}}">

                                        </div>

                                        <div class="col-md-3">
                                            
                                            <label class="">To Date</label>

                                            <input type="date" class="form-control" name="to_date" value="{{$to_date}}">

                                        </div>

                                        <div class="col-md-3">

                                            <label class="">Service</label>

                                           <select name="service" class="form-control select2">
                                               <option value="">Select Service</option>
                                               @foreach($service as $ser)
                                               <option value="{{$ser->id}}" {{$service_id==$ser->id?'selected':''}}>{{$ser->name}}</option>
                                               @endforeach
                                           </select>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="btn-group mt-14" role="group" aria-label="Basic example">
                                              <button type="submit" class="btn btn-outline-info btn-primary btn-ft">Search</button>
                                              <a href="{{ url('partner/details/report') }}"><button type="button" class="btn btn-outline-info btn-primary btn-ft">Clear</button></a>
                                            </div>

                                        </div>

                                    </div>

                                </form>

                                <hr>

                                <div class="table-responsive">

                                    <table id="example" class="table table-striped" style="text-align: center;">

                                        <thead>

                                            <tr>

                                                <th>Onbording Date</th>

                                                <th>Partner ID</th>

                                                <th>Partner Company Name</th>

                                                <th>Partner Contact Name</th>

                                                <th>Partner Email</th>

                                                <th>Partner Mobile</th>

                                                <th>Partner Landline Number</th>

                                                <th>Service Added</th>

                                                <th>Emirate of Interest</th>

                                                <th>Office Address</th>

                                                <th>Bank Details</th>

                                                <th>Verifcation Status</th>

                                                <th>Vat</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($users as $key=>$value)

                                            <tr>

                                                <td>{{ date("d-m-Y", strtotime($value->created_at)) }}</td>

                                                <td>{{ $value->id }}</td>

                                                <td>{{ $value->seller?$value->seller->company_name:'' }}</td>

                                                <td>{{ $value->name }}</td>

                                                <td>{{ $value->email }}</td>

                                                <td>{{ $value->phone }}</td>

                                                <td>{{ $value->seller?$value->seller->landline_no:'' }}</td>

                                                <td>
                                                    @if($value->seller && $value->seller->seller_service)
                                                        @foreach($value->seller->seller_service as $ser)
                                                            {{$ser->service?$ser->service->name:''}}, 
                                                        @endforeach
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($value->seller && $value->seller->city && $value->seller->city_info)
                                                        {{$value->seller->city_info->name}}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if(count($value->address))
                                                        @foreach($value->address as $add)
                                                            <small><b>{{$add->address_type}}</b>, Flat No. {{$add->flat_no}}, {{$add->building}}, {{$add->address}}, {{$add->city?$add->city->name:''}}, {{$add->locality_info?$add->locality_info->name:''}}</small><br>
                                                        @endforeach
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($value->seller)
                                                        @if($value->seller->bank_name)
                                                        {{$value->seller->bank_name}},
                                                        @endif
                                                        @if($value->seller->ac_holder_name)
                                                        {{$value->seller->ac_holder_name}},
                                                        @endif
                                                        @if($value->seller->ac_number)
                                                        {{$value->seller->ac_number}},
                                                        @endif
                                                        @if($value->seller->contact_ac_no)
                                                        {{$value->seller->contact_ac_no}}
                                                        @endif
                                                    @endif
                                                </td>

                                                <td>{{$value->is_active==1?'Active':'Inactive'}}</td>

                                                <td>
                                                    @if($value->seller && $value->seller->vat_no)
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </td>

                                            </tr>

                                            @endforeach

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
                   // {
                   //     extend: 'pdf',
                   //     footer: true,
                   //     exportOptions: {
                   //          columns: [1,2,3,4,5,6,7]
                   //      }
                   // },
                   // {
                   //     extend: 'csv',
                   //     footer: false,
                   //     exportOptions: {
                   //          columns: [1,2,3,4,5,6,7]
                   //      }
                      
                   // },
                   {
                       extend: 'excel',
                       footer: false,
                       title: 'Partner Details',
                       
                   }        
                ],
                "oLanguage": {

                    "sSearch": "Search Company:"

                    }
        } );
    } );
    
</script>  
@endsection 



       