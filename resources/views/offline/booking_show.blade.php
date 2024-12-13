@extends('layouts.dashboard')

@section('content')

<style>
    .mt-18 {
        margin-top: 4%;
    }
    .print {
            margin-right: 5%;
    }
</style>

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">
                           <strong> Source - {{ $card->booking_from }} </strong><br>
                            Booking ID - {{$card->tran_id}}</h3>
                            
                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ url()->previous() }}">

                            <button type="button" class="btn btn-rounded bg-grad-4 ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-arrow-left color-primary"></i> 

                                </span>Back

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

                                <div class="row">

                                    <div class="col-sm-2">
                                        <b>User : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->user?$card->user->name:''}}
                                    </div>
                                    <div class="col-sm-2">
                                        <b>Service : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->service?$card->service->name:''}}
                                    </div>

                                </div>

                                <div class="row mt-2"> 

                                    <div class="col-sm-2">
                                        <b>Category : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->category?$card->category->name:'No Category'}}
                                    </div>
                                    <div class="col-sm-2">
                                        <b>Slot Date : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{ date('d F Y', strtotime($card->date))}}
                                    </div>

                                </div>

                                <div class="row mt-2">   

                                    <div class="col-sm-2">
                                        <b>Payment Mode : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->payment_moad?$card->payment_moad:''}}
                                        @if($card->payment_moad=='Cash')
                                            @if($card->payment_collected=='Yes')
                                                <small class="text-success">(Paid)</small>
                                            @else
                                                <small class="text-warning">(Pending)</small>
                                            @endif
                                        @else
                                            
                                        @endif
                                    </div>
                                    
                                    <div class="col-sm-2">
                                        <b>Slot : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->slot?$card->slot->name:''}}
                                    </div>

                                </div>

                                <div class="row mt-2">   

                                    <div class="col-sm-2">
                                        <b>Alternative No. : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->alternative_number?'+971':''}}{{$card->alternative_number}}
                                    </div>
                                    <div class="col-sm-2">
                                        <b>Creation Date : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{date('d F Y', strtotime($card->created_at))}}
                                    </div>
                                    
                                </div>

                                <div class="row mt-2">   

                                    <div class="col-sm-2">
                                        <b>Contact No. : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        @if($card->user && $card->user->phone)
                                            +971{{$card->user->phone}}
                                        @endif
                                        
                                    </div>
                                    <div class="col-sm-2">
                                        <b>Booking Instructions : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->note}}
                                    </div>   
                                    
                                </div>

                                <div class="row mt-2">   
                                    <div class="col-sm-2">
                                        <b>Address : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        @if(is_numeric($card->address_id))
                                            
                                            <strong>Address Type - {{$card->address?$card->address->address_type:''}}</strong>
                                            <br>
                                            Flat No. {{$card->address?$card->address->flat_no:''}}, {{$card->address?$card->address->building:''}}, 
                                            <br>
                                            @if($card->address && $card->address->locality_info)
                                            {{$card->address->locality_info?$card->address->locality_info->name:''}}, 
                                            @endif

                                            @if($card->address && $card->address->city)
                                            {{$card->address->city?$card->address->city->name:''}}, 
                                            @endif

                                            
                                            <br>
                                            {{$card->address?$card->address->address:''}}

                                        @elseif(json_decode($card->address_id))
                                            
                                            <strong>
                                                Address Type - {{json_decode($card->address_id)->address_type}}</strong>
                                            <br>
                                            Flat No. {{json_decode($card->address_id)->flat_no}}, {{json_decode($card->address_id)->building}}, 
                                            <br>
                                            {{json_decode($card->address_id)->locality}},

                                            {{json_decode($card->address_id)->city_name}},

                                            
                                            <br>
                                            {{json_decode($card->address_id)->address}}
                                        @else

                                        @endif
                                        
                                        
                                        <br><br>

                                        @if(is_numeric($card->address_id))
                                        <a style="text-decoration: underline; color:blue; font-weight: 700;" target='_blank' href="https://www.google.com/maps?q={{$card->address?$card->address->latitude:''}},{{$card->address?$card->address->longitude:''}}"> Click here for location </a>
                                        @else
                                        <a style="text-decoration: underline; color:blue; font-weight: 700;" target='_blank' href="https://www.google.com/maps?q={{json_decode($card->address_id)->latitude}},{{json_decode($card->address_id)->longitude}}"> Click here for location </a>
                                        @endif

                                    </div> 
                                    @if($card->payment_moad=='Card' && $card->paymentTranId)
                                        <div class="col-sm-2">
                                            <b>Payment Transaction ID : </b>
                                        </div>
                                        <div class="col-sm-4">
                                            {{$card->paymentTranId}}
                                        </div>
                                    @endif 
                                                                   
                                </div>


                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 mt-18">
                                        <table class="table table-border table-hover table-sm mt-4">
                                          <thead>
                                            <tr>
                                              <th scope="col">#</th>
                                              <th scope="col">Sub Category</th>
                                              <th scope="col">Attribute</th>
                                              <th scope="col">Attribute Item</th>
                                              <th scope="col">Qty</th>
                                              <th scope="col">Price</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php $total = '0'; $subtotal = '0'; ?>
                                            @foreach($card->card_attribute as $key => $items)
                                            <tr>
                                              <td>{{ ++$key }}</td>
                                              <td>{{$items->main_sub_cat?$items->main_sub_cat->name:''}}</td>
                                              <td>{{$items->attribute_name}}</td>
                                              <td>{{$items->attribute_item_name}}</td>
                                              <td>{{$items->attribute_qty}}</td>
                                              <td>{{ Session::get('currencies') }} {{$items->attribute_qty*$items->attribute_price}}</td>
                                            </tr>
                                            <?php
                                              $total += $items->attribute_qty*$items->attribute_price;
                                              $subtotal += $items->attribute_qty*$items->attribute_price;
                                            ?>
                                            @endforeach
                                            <?php
                                              $subtotal += $card->material_charge;
                                            ?>
                                            <tr>
                                              <td colspan="4"></td>
                                              <td>Sub Total</td>
                                              <td>{{ Session::get('currencies') }} {{$subtotal}}</td>
                                            </tr>
                                            
                                            @if($card && $card->material_status=='Apply')
                                            <tr>
                                              <td colspan="4"></td>
                                              <td>Material Charge</td>
                                              <td>{{ Session::get('currencies') }} {{price_format($card->material_charge)}}</td>
                                            </tr>
                                            <?php $total += $card->material_charge; ?>
                                            @endif

                                            @if($card->tip_id)
                                            <tr>
                                              <td colspan="4"></td>
                                              <td>Tip</td>
                                              <td>{{ Session::get('currencies') }} {{$card->tip_id}}</td>
                                            </tr>
                                            <?php $total += $card->tip_id ?>
                                            @endif
                                            @if($card->offline_charge)
                                            <tr>
                                              <td colspan="4"></td>
                                              <td>Convenience Fee (+)</td>
                                              <td>{{ Session::get('currencies') }} {{price_format($card->offline_charge)}}</td>
                                              <?php $total += $card->offline_charge; ?>
                                            </tr>
                                            @endif
                                            @if($card->offline_discount)
                                            <tr class="text-danger">
                                              <td colspan="4"></td>
                                              <td>Discount (-)</td>
                                              <td>{{ Session::get('currencies') }} {{price_format($card->offline_discount)}}</td>
                                              <?php $total -= $card->offline_discount; ?>
                                            </tr>
                                            @endif

                                            <?php
                                                $coupon = App\CardCoupon::where('card_id',$card?$card->id:'')->first();
                                                if($coupon){
                                                $amount = $coupon->amount;
                                                if($coupon->type=='Amt'){
                                                    $subtotal -= $amount;
                                                    $coupon_Amt = $amount;
                                                } else {
                                                    $per = ($amount / 100) * $subtotal;
                                                    
                                                    if($per>$coupon->max_amount){
                                                    $coupon_Amt = $coupon->max_amount;
                                                    } else {
                                                    $coupon_Amt = price_format($per);
                                                    }
                                                }
                                                } else {
                                                    $coupon_Amt = '00';
                                                }
                                            ?>
                                            
                                            @if($coupon_Amt>0)
                                            <tr class="text-danger">
                                              <td colspan="4"></td>
                                              <td>Coupon (-)</td>
                                              <td>{{ Session::get('currencies') }} {{price_format($coupon_Amt)}}</td>
                                              <?php $total -= $coupon_Amt; ?>
                                            </tr>
                                            @endif

                                            @if($card && $card->cod_charge)
                                            <tr>
                                              <td colspan="4"></td>
                                              <td>Cash Surcharge</td>
                                              <td>{{ Session::get('currencies') }} {{$card->cod_charge}}</td>
                                            </tr>
                                            <?php $total += $card->cod_charge; ?>
                                            @endif
                                            
                                            
                                           
                                            <tr>
                                              <td colspan="4"></td>
                                              <td>Grand Total</td>
                                              <td>{{ Session::get('currencies') }} {{price_format($total)}}</td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- <div class="text-right print"><a href="{{url('/booking/invoice/'.$card->id)}}"><i class="fa fa-print" style="font-size:36px"></i></a></div> -->

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



       