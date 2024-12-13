@extends('layouts.dashboard')
<style>
    .count-card {
        color: aliceblue;
        padding: 2%;
    }
    .count {
        float: right;
    }
</style>
@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Vendor Detail</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        

                    </div>

                

                <div class="container-fluid">

                    <div class="row">

                        <div class="col-lg-12">

                            @include('vendor.top')

                        </div>

                        <div class="col-lg-12">

                            <div class="card">

                                <div class="card-body">

                                    <div class="custom-tab-2">

                                        @include('vendor.menu')

                                        <div class="table-responsive">

                                            <table class="table table-border table-hover table-sm" >

                                                <thead>

                                                    <tr>

                                                        <th>S.N.</th>

                                                        <th> Date & Time</th>
                                                        
                                                        <th>Service</th>

                                                        <th>Job Value</th>

                                                        <!-- <th>Net Balance</th> -->

                                                        <th>Payment Mode</th>

                                                        <th>Action</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @if(!empty($data))

                                                    @foreach($data as $key=>$value)

                                                    <tr>

                                                        <td>{{ ++$key }}</td>
                                                        
                                                        <td>{{ $value->created_at->format('d M, Y') }} at {{ $value->created_at->format('h:i A') }} </td>
                                                                                
                                                        <td>{{ $value->card?$value->card->service?$value->card->service->name:'':'' }}</td>

                                                        <td>AED {{ price_format($value->job_value) }} </td>

                                                        <!-- <td>AED {{ price_format($value->net_balance) }}</td> -->

                                                        <td>{{ $value->payment_moad }}
                                                        
                                                        </td>

                                                        <td>
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$value->id}}">
                                                              View
                                                            </button>
                                                          <a href="{{ url('booking/view/'.$value->card_id) }}" target="_blank">
                                                            <button type="button" class="btn btn-outline-info btn-ft btn-sm" title="View Booking" alt="View Booking"><i class="fa fa-eye" aria-hidden="true" style=" padding-top: 6px;  padding-bottom: 6px; "></i></button></a>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="exampleModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                              <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Pay Out Calculation Details</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                      <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">Job Value : </div>
                                                                        <div class="col-sm-6">AED {{ $value->job_value?$value->job_value:'00' }}</div>

                                                                        <div class="col-sm-6">Coupon Amount : </div>
                                                                        <!-- <div class="col-sm-6">AED {{ $value->coupon_amt?$value->coupon_amt:'00' }}</div> -->
                                                                        <div class="col-sm-6">AED {{ number_format($value->coupon_amt ? $value->coupon_amt : '00', 2) }}</div>
                                                                        
                                                                        <div class="col-sm-6">Cash Surcharge : </div>
                                                                        <div class="col-sm-6">AED {{ $value->cash_surcharge?$value->cash_surcharge:'00' }}</div>

                                                                        <div class="col-sm-6">Convenience Fee : </div>
                                                                        <div class="col-sm-6">AED {{ $value->offline_charge?$value->offline_charge:'00' }}</div>

                                                                        <div class="col-sm-6">Offline Discount : </div>
                                                                        <div class="col-sm-6">AED {{ $value->offline_discount?$value->offline_discount:'00' }}</div>

                                                                        <div class="col-sm-6">Tip : </div>
                                                                        <div class="col-sm-6">AED {{ $value->tip?$value->tip:'00' }}</div>

 
                                                                        <div class="col-sm-6"><b>Total (Customer Pay)</b> : </div>
                                                                        <div class="col-sm-6"><b>
                                                                        
                                                                        <?php 
                                                                        // 02-04-2024 Comment by Mohit Calculation may be wrong 
                                                                        // $cash_customer_pay = $value->job_value + $value->cash_surcharge + $value->offline_charge + $value->tip + $value->bank_fees_amt - $value->coupon_amt - $value->offline_discount;
                                                                        // $prepaid_customer_pay = $value->job_value + $value->tip - $value->coupon_amt  - $value->offline_discount;
                                                                        $cash_customer_pay = $value->card_total;
                                                                        $prepaid_customer_pay = $value->card_total;
                                                                        ?>
                                                                        <!-- AED //{{ $value->card_total?$value->card_total:'00' }} -->

                                                                        @if($value->payment_moad == 'Prepaid')
                                                                        
                                                                        AED {{ number_format($prepaid_customer_pay ? $prepaid_customer_pay : '00', 2) }}

                                                                        @elseif($value->payment_moad == 'Cash')
                                                                        <!-- Additional information for Cash payments -->
                                                                        AED {{ number_format($cash_customer_pay ? $cash_customer_pay : '00', 2) }}
                                                                        @else
                                                                        <!-- Default case or additional cases can be added here -->
                                                                        @endif
                                                                        </b>
                                                                    </div>
                                                                        

                                                                        @if($value->payment_moad=='Prepaid')
                                                                        <!-- <div class="col-sm-6">Bank Fees : </div>
                                                                        <div class="col-sm-6">{{ $value->bank_fees }}% + 1</div> -->

                                                                        <div class="col-sm-6">Bank Fees : </div>
                                                                        <div class="col-sm-6">AED {{ number_format($value->bank_fees_amt ? $value->bank_fees_amt : '00', 2) }}</div>
                                                                        @endif

                                                                        <!-- <div class="col-sm-6">Vendor Commission : </div>
                                                                        <div class="col-sm-6">{{ $value->um_comission?$value->um_comission:'00' }}%</div> -->
                                                                        <?php 
                                                                        $um_totalearning = $value->um_com_amt + $value->tip + $value->cash_surcharge + $value->offline_charge + $value->bank_fees_amt;
                                                                        ?>
                                                                        <div class="col-sm-6">UM Total Earning :  </div>
                                                                        <!-- <div class="col-sm-6">AED {{ $value->um_com_amt?$value->um_com_amt:'00' }}</div> -->
                                                                        <div class="col-sm-6">AED {{ $um_totalearning }} </div>

                                                                        <!-- <div class="col-sm-6">UM Commission : </div>
                                                                        <div class="col-sm-6"> -->
                                                                            @if($value->um_comission)
                                                                                <?php $um_c = 100-$value->um_comission ?>
                                                                            @else
                                                                                <?php $um_c = '00' ?>
                                                                            @endif
                                                                            <!-- {{ $um_c }}% -->
                                                                            <!-- {{ $value->um_comission?$value->um_comission:'00' }}% -->
                                                                        <!-- </div> -->

                                                                        <div class="col-sm-6">Vendor Earning : </div>
                                                                        <div class="col-sm-6">
                                                                        
                                                                        <?php 
                                                                        // 02/04/2024 By Mohit this calclation is wrong
                                                                        // $vendor_earning_cash  = $cash_customer_pay - $um_totalearning;
                                                                        // $vendor_earning_online = $prepaid_customer_pay - $um_totalearning;
                                                                        $vendor_earning_cash  = $value->vendor_earning;
                                                                        $vendor_earning_online  = $value->vendor_earning;
                                                                        ?>

                                                                        @if($value->payment_moad == 'Prepaid')

                                                                        AED {{ number_format($vendor_earning_online  ?  $vendor_earning_online  : '00', 2) }}
                                                                        
                                                                        @elseif($value->payment_moad == 'Cash')
                                                                        <!-- Earning calculation for vendor  -->
                                                                        
                                                                        AED {{ number_format($vendor_earning_cash  ?  $vendor_earning_cash  : '00', 2) }}
                                                                        @else
                                                                        <!-- Default case or additional cases can be added here -->
                                                                        @endif
                                                                        

                                                                            <!-- <?php
                                                                                    $um_com_amt  = ($value->job_value*$um_c)/100;
                                                                               $ftotal      = round($um_com_amt,2);
                                                                            ?>
                                                                             AED {{ $ftotal }} -->
                                                                        </div>

                                                                        
                                                                        
                                                                        <!-- <div class="col-sm-6">Net Balance</div>
                                                                        <div class="col-sm-6">AED {{ $value->net_balance?$value->net_balance:'00' }}</div> -->

                                                                    </div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                  </div>
                                                                </div>
                                                              </div>
                                                            </div>

                                                        </td>

                                                    </tr>

                                                    @endforeach

                                                        @if ($data->count() == 0)

                                                        <tr class="text-center">

                                                            <td colspan="6">No data to display.</td>

                                                        </tr>

                                                        @endif

                                                    @endif

                                                </tbody>

                                                <tfoot>

                                                    <tr>

                                                        <th>S.N.</th>

                                                        <th> Date & Time</th>
                                                        
                                                        <th>Service</th>

                                                        <th>Job Value</th>

                                                        <!-- <th>Net Balance</th> -->

                                                        <th>Payment Mode</th>

                                                        <th>Action</th>

                                                    </tr>

                                                </tfoot>

                                            </table>

                                        </div>

                                        <div class="text-left float-left mt-1">

                                            <p>Displaying {{$data->count()}} of {{ $data->total() }} data.</p>

                                        </div>

                                        <div class="text-right float-right">{{ $data->appends(request()->all())->links() }}</div>

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



@endsection      



       