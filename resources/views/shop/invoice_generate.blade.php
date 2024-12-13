@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Generate Invoice</h3>
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
                                                        <option value="{{$value['id']}}" @if ($request->month == $value['id']) {{ 'selected' }}  @endif >{{$value['label']}}</option>
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
                                                <option value="{{ now()->year }}"   @if ($request->year == now()->year) {{ 'selected' }} @endif >{{ now()->year }}</option>
                                                <option value="{{ now()->year-1 }}" @if ($request->year == now()->year-1) {{ 'selected' }} @endif >{{ now()->year-1 }}</option>
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
                                        <table id="example" class="display">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Customer</th>
                                                    <th>Contact</th>
                                                    <th>View</th>
                                                    <th>Operation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($customers))
                                                @foreach($customers as $key=>$value)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td><a href="{{ route('shop.customer.detail',encrypt($value->customer_id)) }}" target="_blank">{{ $value->customer->name }}</a></td>
                                                    <td>{{ $value->customer->email }}<br>{{ $value->customer->phone }}</td>
                                                    <td>
                                                       <a href="javascript:void(0);" onclick="getOrders('{{$value->customer_id}}')"><button type="button" class="btn btn-outline-warning btn-ft">View Bill Info</button></a>
                                                    </td>
                                                    <td>
                                                        @if($value->invoice)
                                                          <p>Bill already generated <br>
                                                           Invoice Id : {{$value->invoice}}</p>
                                                        @else
                                                          <a href="javascript:void(0);" onclick="generateBill('{{$value->customer_id}}')"><button type="button" class="btn btn-outline-success btn-ft">Generate Bill</button></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Customer</th>
                                                    <th>Contact</th>
                                                    <th>View</th>
                                                    <th>Operation</th>
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
  <div class="modal fade" id="billModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Month Order Bill</h4>
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

   <!-- The Modal -->
  <div class="modal fade" id="invoiceModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Invoice</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div id="invoice_data">
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
    function getOrders(customer_id) {
        $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
        $.ajax({
                  url: "<?php echo route('search.order') ?>",
                  method: 'post',
                  data: {
                     customer_id: customer_id,
                     month : "{{$request->month}}",
                     year : "{{$request->year}}",
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

    function generateBill(customer_id) {
        // escape here if the confirm is false;
        if (!confirm('Are you sure?')) return false;
        $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
        $.ajax({
                  url: "<?php echo route('invoice.save') ?>",
                  method: 'post',
                  data: {
                     customer_id: customer_id,
                     month : "{{$request->month}}",
                     year : "{{$request->year}}",
                      _token : "{{ csrf_token() }}"
                  },
                  dataType: 'json',
                  success: function(res){
                     console.log(res);
                      if(res.success == true) {
                        //invoice div defined on page
                        $('#invoice_data').html(res.html);
                        $('#invoiceModal').modal("show");
                        setTimeout(function () { 
                          location.reload(true);
                        }, 5000);
                      }
                      else
                      {
                        if(res.msg)
                        {
                          alert(res.msg);
                        }
                      }
                  },
                  error: function (res) {
                    console.log(res);
                }
            });

    }
</script>
@endsection      

       