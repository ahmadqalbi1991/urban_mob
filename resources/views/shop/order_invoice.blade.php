@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Order Invoice</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        
                    </div>
                </div>
            <div class="container-fluid">
                @include('flash_msg')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <form method="post" action="{{ route('generate.invoice') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                              <label>Select Month:</label>
                                              <select class="form-control" name="month">
                                                <option value="">-select-</option>
                                                @if($months)
                                                    @foreach($months as $key=>$value)
                                                        <option value="{{$value}}" @if ($request->month == $value) {{ 'selected' }}  @endif >{{$value}}</option>
                                                    @endforeach
                                                @endif
                                              </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                              <label>Select Year:</label>
                                              <select class="form-control" name="year">
                                                <option value="">-select-</option>
                                                @if($years)
                                                    @foreach($years as $key=>$value)
                                                        <option value="{{$value}}" @if ($request->year == $value) {{ 'selected' }}  @endif >{{$value}}</option>
                                                    @endforeach
                                                @endif
                                              </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                             <label class="mt-4">&nbsp;</label>
                                             @csrf
                                            <button type="submit" class="btn btn-info mt-3">Search</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                               <div class="table-responsive">
                                    <table  class="display table table-border table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice Info</th>
                                                <th>Invoice Month</th>
                                                <th>Customer</th>
<!--                                                 <th>Status</th> -->
                                                <th>Generated</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($invoices)
                                        @foreach($invoices as $key=>$value) 
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <b>Invoice ID:</b> {{ $value->id }} <br>
                                                    <b>Invoice Amount:</b> {{ $value->amount }} {{CURRENCY}}  <br>
                                                </td>
                                                <td>  {{ $value->month }} -  {{ $value->year }}</td>
                                                <td><a href="{{ route('shop.customer.detail',encrypt($value->customer_id)) }}" target="_blank">{{ $value->customer->name }}</a></td>
                                                <!-- <td>
                                                    @if($value->status=='Paid')
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-success">Paid</span>
                                                    @else
                                                    <span class="badge mb-2 mb-xl-0 badge-pill badge-danger">{{ $value->status }}</span>
                                                    @endif
                                                    <br>
                                                </td> -->
                                                <td>{{ changeDateFormate($value->created_at) }}</td>
                                                <td>
                                                   <!--  @if($value->order_status=='Unpaid')
                                                    <a href="#" onclick="return confirm('Are you sure to receive payment of this invoice')"><button type="button" class="btn btn-outline-success btn-ft">Receive Now</button></a>
                                                    @endif -->
                                                    <a href="javascript:void(0);" onclick="getOrders('{{$value->customer_id}}','{{$value->month}}','{{$value->year}}','{{ $value->id }}')"><button type="button" class="btn btn-outline-warning btn-ft">View Bill Info</button></a>
                                                    <a href="{{ route('invoice.download',['id'=>$value->id,'download'=>'pdf']) }}"><button type="button" class="btn btn-outline-success btn-ft">Download Invoice</button></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($invoices->count() == 0)
                                        <tr class="text-center">
                                            <td colspan="6">No invoice(s) to display.</td>
                                        </tr>
                                        @endif
                                        @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice Info</th>
                                                <th>Invoice Month</th>
                                                <th>Customer</th>
                                                <!-- <th>Status</th> -->
                                                <th>Generated</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-left float-left mt-1">
                                    <p>Displaying {{$invoices->count()}} of {{ $invoices->total() }} invoice(s).</p>
                                </div>
                                <div class="text-right float-right">
                                    {{ $invoices->appends(request()->all())->links() }}
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
  <div class="modal fade" id="billModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Invoice <span id="invoiceID"></span></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div id="bill_data">
            </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

 <script type="text/javascript">
    function getOrders(customer_id,month,year,invoice_id) {
        $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });

              $('#invoiceID').html(invoice_id);

        $.ajax({
                  url: "<?php echo route('search.order') ?>",
                  method: 'post',
                  data: {
                     customer_id: customer_id,
                     month : month,
                     year : year,
                      _token : "{{ csrf_token() }}"
                  },
                  dataType: 'json',
                  success: function(res){
                     console.log(res);
                      if(res.success == true) {
                        //bill div defined on page
                        $('#bill_data').html(res.html);
                        $('#billModal').modal("show");
                      }
                  },
                  error: function (res) {
                    console.log(res);
                }
            });

    }

</script>     
@endsection      

       