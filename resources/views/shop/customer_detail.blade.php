@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="breadcrumb-range-picker">
                        <h3 class="ml-1">Customer Detail</h3>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                     @include('flash_msg')
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-5 col-xxl-4 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="media align-items-center mb-4">
                                    <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="{{asset('images/form-user.png')}}" width="80" height="80" alt="">
                                    <div class="media-body">
                                        <h3 class="mb-0">{{$user->name}}</h3>
                                        <p class="text-muted mb-0">Customer</p>
                                    </div>
                                </div>

                                <h4>Address</h4>
                                <p class="text-muted">{{$user->address}}</p>
                                <p class="text-muted">{{$user->city}}</p>
                                <ul class="card-profile__info">
                                    <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span>{{$user->phone}}</span></li>
                                    <li class="mb-1"><strong class="text-dark mr-4">Email</strong> <span>{{$user->email}}</span></li>
                                    <li class="mb-1"><strong class="text-dark mr-4">Registered</strong> <span>{{ changeDateFormate($user->created_at) }}</span></li>
                                </ul>
                                @if($package)
                                <a href="{{ route('shop.package.items',$package->id) }}" target="_blank"><button type="button" class="btn btn-outline-info btn-ft">View Subscription Package</button></a>
                                <hr>
                                <div class="float-left mt-1">
                                	<b>Current Balance: </b>
                                	@if($balance<0)
                                		<i class="text-danger">{{$balance}} {{CURRENCY}}</i>
                                	@elseif($balance>0)
                                		<i class="text-success">{{$balance}} {{CURRENCY}}</i>
                                	@else
                                		<i>{{$balance}} {{CURRENCY}}</i>
                                	@endif
                                </div>
                                <div class="float-right"> <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal"><button type="button" class="btn btn-outline-warning btn-ft btn-sm">Receive Now</button></a></div>
                               <!--  @if($balance>0)
                                    <div class="float-right"> <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal"><button type="button" class="btn btn-outline-success btn-ft">Receive Now</button></a></div>
                                @endif -->
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-xxl-8 col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                   <div class="custom-tab-2">
                                    <ul class="nav nav-tabs nav-justified">
                                       
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#transection">My Transection</a>
                                        </li>
                                         <li class="nav-item"><a class="nav-link " data-toggle="tab" href="#invoice">My Invoice</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content tab-content-default">
                                    	<div class="tab-pane fade show active" id="transection" role="tabpanel">
                                            <div class="table-responsive">
                                                <table id="DT2" class="display">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date</th>
                                                            <th>Amount</th>
                                                            <th>Remark</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($transections)
                                                    @foreach($transections as $key=>$value) 
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td>{{ changeDateFormate($value->created_at) }}</td>
                                                            <td>
                                                                @if($value->type=='Cr')
                                                                    <span class="text-danger">- {{ $value->amount }} {{CURRENCY}}</span>
                                                                @else
                                                                     <span class="text-success">+ {{ $value->amount }} {{CURRENCY}}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                              {{ $value->remark }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date</th>
                                                            <th>Amount</th>
                                                            <th>Remark</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="invoice" role="tabpanel">
                                            <div class="table-responsive">
                                                <table id="DT1" class="display">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Invoice ID</th>
                                                            <th>Amount</th>
                                                            <th>Month</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($invoices)
                                                    @foreach($invoices as $key=>$value) 
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td>{{ $value->id }}</td>
                                                            <td>
                                                              {{ $value->amount }} {{CURRENCY}}
                                                            </td>
                                                            <td>
                                                              {{ $value->month }} -  {{ $value->year }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Invoice ID</th>
                                                            <th>Amount</th>
                                                            <th>Month</th>
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
                </div>
            </div>
            
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


          <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
       <form action="{{ route('payment.save',$user->id) }}" method="post">
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Receive Payment</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
           
            <!-- Modal body -->
            <div class="modal-body">
              
              		<div class="form-group">
    				  <label>Amount:</label>
    				  <input type="text" name="amount" class="form-control" value="{{$balance}}">
    				</div>
              		<div class="form-group">
    				  <label>Remark:</label>
    				  <textarea class="form-control" name="remark" rows="3"></textarea>
    				</div>
             
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
                @csrf
                <button type="submit" name="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
    </div>
  </div>

@endsection      

       