
@extends('web.layout.header')
@section('title','Service Details')
@section('content')
<main>
   
      <section class="pt-50  p-relative">
        <div class="registration-form">
            <h2>Congratulations</h2>
            <p class="text-center" >Your order has been successfully placed !</p>
            <p class="text-center"><small>Your Order ID : {{$card?$card->tran_id:''}}</small></p>
            <div class="card pt-1 pb-1 pl-4 pr-4" style="padding: 10%;">
                <?php $sub_total = '0'; $total = '0'; ?>
                @if($card && $card->card_attribute)
                    @foreach($card->card_attribute as $key => $card_atr)
                    @if($card_atr->service_type=='Maid')
                        <div class="row" >
                            <div class="col-6 text-left" >
                               <p>{{$card_atr->attribute_name}}</p>
                            </div>
                            <div class="col-6 text-right" >
                               <p class="#">AED {{$card_atr->attribute_price}}</p>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-6 text-left">
                               <p>{{$card_atr->attribute_item_name}}</p>
                            </div>
                            <div class="col-6 text-right" >
                               <p class="#">{{$card_atr->attribute_qty}}</p>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-6 text-left">
                               <p></p>
                            </div>
                            <div class="col-6 text-right">
                               <p class="#">AED {{$card_atr->attribute_price*$card_atr->attribute_qty}}</p>
                            </div>
                        </div>
                    @else
                        <div class="row" >
                            <div class="col-6 text-left" >
                               <!-- <p>{{$card_atr->attribute_item_name}}</p> -->
                               <p>
                                    
                                    @if($card_atr->main_sub_cat_id && isset($card_atr->main_sub_cat_id))
                                        {{$card_atr->main_sub_cat?$card_atr->main_sub_cat->name:''}} -> 
                                    @endif
                                    @if($card_atr->child_cate_id && isset($card_atr->child_cate_id))
                                        {{$card_atr->child_cate?$card_atr->child_cate->name:''}} -> 
                                    @endif
                                    {{$card_atr->attribute_item_name}}

                               </p>
                            </div>
                            <div class="col-6 text-right" >
                               <p class="#">{{$card_atr->attribute_qty}} x AED {{$card_atr->attribute_price}}</p>
                            </div>
                        </div>
                    @endif
                    <?php
                        $sub_total += $card_atr->attribute_price*$card_atr->attribute_qty;
                        $total += $card_atr->attribute_price*$card_atr->attribute_qty;
                    ?>
                    @endforeach
                @endif
                <hr>
                @if($card)
                    @if($card && $card->material_status=='Apply')
                    <div class="row" >
                        <div class="col-6 text-left" >
                            <p>Material Charge</p>
                        </div>
                        <div class="col-6 text-right" >
                           <a class="javascript:" >AED {{$card->material_charge}}</a>
                        </div>
                    </div>
                    <hr>
                     <?php $total += $card->material_charge; ?>
                     <?php $sub_total += $card->material_charge; ?>
                    @endif
                @endif
                
                <div class="row" >
                    <div class="col-6 text-left">
                        <p>Subtotal</p>
                    </div>
                    <div class="col-6 text-right" >
                       <a class="#" >AED {{$sub_total}}</a>
                    </div>
                </div>
                <hr>

                <?php
                  $coupon = App\CardCoupon::where('card_id',$card?$card->id:'')->first();
                  
                  if($coupon){
                    $amount = $coupon->amount;
                    if($coupon->type=='Amt'){
                        $total -= $amount;
                        $coupon_Amt = $amount;
                    } else {
                        $per = ($amount / 100) * $sub_total;
                        
                        if($per>$coupon->max_amount){
                            $coupon_Amt = $coupon->max_amount;
                        } else {
                            $coupon_Amt = price_format($per);
                        }
                        $total -= $coupon_Amt;
                    }
                  } else {
                    $coupon_Amt = '00';
                  }
                ?>
                @if($card && $card->coupon_id)
                <div class="row" >
                    <div class="col-6 text-left">
                        <p>Coupon</p>
                        <p>Coupon Applied</p>
                    </div>
                    <div class="col-6 text-right">
                        <a class="javascript:" >AED {{$coupon_Amt}}</a>
                    </div>
                </div>
                <hr>
                @endif

                @if($card && count($card->card_addon))
                <?php $addonAmt = $card->card_addon->sum('value'); ?>
                <div class="card p-5">
                    <div class="row mt-10" >
                        <div class="col-6 text-left" >
                            <h6>Addons</h6>
                        </div>
                        <div class="col-6 text-right" >
                           <a class="javascript:" >AED {{$addonAmt}}</a>
                        </div>
                    </div>
                </div> 
                <?php $total += $addonAmt ?>
                <hr>
                @endif

                @if($card && $card->tip_id)
                <div class="row" >
                    <div class="col-6 text-left">
                        <p>Tip</p>
                    </div>
                    <div class="col-6 text-right" >
                       <a class="javascript:" >AED {{$card->tip_id}}</a>
                    </div>
                </div>
                <hr>
                <?php $total += $card->tip_id; ?>
                @endif
                
                @if($card && $card->cod_charge)
                <div class="row mt-10" >
                    <div class="col-6 text-left" >
                        <p>Cash Surcharge</p>
                    </div>
                    <div class="col-6 text-right" >
                       <a class="javascript:" >AED {{$card->cod_charge}}</a>
                    </div>
                </div>
                <hr>
                 <?php $total += $card->cod_charge; ?>
                @endif
                <div class="row mt-10" >
                    <div class="col-6 text-left" >
                        <p>Total</p>
                    </div>
                    <div class="col-6 text-right" >
                        <a class="javascript:" >AED {{$total}}</a>
                    </div>
                </div>
            </div> 

            <div class="card mt-10 pt-2 p-5 pb-1 pt-5">
                <div>
                   <h6>Your {{$card->service?$card->service->name:''}} is Scheduled At:</h6>
                   <p>{{ date('d F Y', strtotime($card->date))}} | {{$card->slot?$card->slot->name:''}}</p>
                </div>   
                <p><b>Booking Instructions :</b> {{$card?$card->note:''}}</p>          
            </div>
            <div class="mt-10 text-center">
                <button type="button"><a href="{{url('/')}}"> Book Your Next Clean</a></button>
            </div> 
            
          

          </div>

      </section>
   </main>

   @endsection