<?php 
	$card = App\Card::where('user_id',\Session::get('user_id'))->where('service_id',$service_id)->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->where('is_checkout','Processing')->orderBy('id', 'DESC')->first();
 ?>
<div class="card mb-4">
    <div class="row p-2" >
        <div class="col-md-6 text-left">
        	 <p class="m-0">Total Amount</p>
           
                @if(App\Card::where('user_id',\Session::get('user_id'))->where('service_id',$service_id)->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->count()>0)
                <?php $card_info = App\Card::where('user_id',\Session::get('user_id'))->where('service_id',$service_id)->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->where('is_checkout','Processing')->orderBy('id', 'DESC')->first(); ?>
                <h5>AED {{$card_info?$card_info->g_total:''}}</h5>
                <?php $cart_val = $card_info->g_total; ?>
                @else
                <?php $cart_val = '0'; ?>
                @endif
           
        </div>
        <div class="col-md-6">
            <p class="card-title m-0">
                <a onclick="showCard()" href="javascript:">
				    View Order Details
				</a>
			</p> 
        </div>
    </div> 
    @if(Auth::check())
        <?php 
            $setting = App\HomeSetting::first(); 
            $net_val = $setting->min_cart_value-$cart_val;
        ?>
        @if($card && isset($card->payment_moad))
            @if($card && isset($card->payment_moad) && $card->payment_moad=='Card' || $card->payment_moad=='Cash')
                @if($setting && $setting->min_cart_value<=$cart_val)
                <p class="p-2 m-0">  <button type="submit" class="urbun_btn text-orange btn btn-dark w-100">Book Now</button></p>
                @else
                <p class="p-2 m-0">  <button type="button" onclick="minCartAlert({{$net_val}})" class="urbun_btn text-orange btn btn-dark w-100">Book Now</button></p>
                <div class="text-center">
                    <p class="minalert text-danger"></p>
                </div>
                @endif
            @else
            <p class="p-2 m-0">   <button type="button" data-bs-toggle="modal" href="#exampleModalToggle" role="button" class="urbun_btn text-orange btn btn-dark w-100 "> Proceed</button></p>
                @if($card && $card->g_total>0)
                    @if($card && $card->card_process=='Complete')
                    <p class="p-2 m-0">   <button type="button" onclick="nextPrev(1)" role="button" class="urbun_btn text-orange btn btn-dark w-100"> Proceed</button></p>
                    @else
                    <p class="p-2 m-0">   <button type="button" data-bs-toggle="modal" href="#exampleModalToggle" role="button" class="urbun_btn text-orange btn btn-dark w-100 "> Proceed</button></p>
                    @endif
                @else
                <p class="p-2 m-0">   <button type="button" onclick="alertfun()" role="button" class="urbun_btn text-orange btn btn-dark w-100"> Proceed</button></p>
                @endif
            @endif
        @else
            @if($card && $card->g_total>0)
                @if($setting && $setting->min_cart_value<=$card->g_total)
                <p class="p-2 m-0"> <button type="button" data-bs-toggle="modal" href="#exampleModalToggle" role="button" class="urbun_btn btn btn-dark w-100 text-orange"> Proceed</button></p>
                @else
                <p class="p-2 m-0"> <button type="button" onclick="minCartAlert({{$net_val}})" class="urbun_btn btn btn-dark w-100 text-orange"> Proceed</button></p>
                <div class="text-center">
                    <p class="minalert text-danger"></p>
                </div>      
                @endif
            @else
            <p class="p-2 m-0"> <button type="button" onclick="alertfun()" role="button" class="urbun_btn  w-100  text-orange btn btn-dark"> Proceed</button></p>
            @endif
           
        @endif

    @else
    <p class="p-2 m-0"> <button type="button" onclick="show('popup')" class="urbun_btn text-orange w-100 btn btn-dark "> Proceed</button> </p>
  
    @endif

</div>

	<div class="cardmodal">
		<?php $sub_total = '0'; $total = '0'; ?>
        @if($card && $card->card_attribute)
            @foreach(App\CardAttribute::where('card_id',$card->id)->get() as $key => $card_atr)
            @if($card_atr->service_type=='Maid')
                <div class="card p-2 mb-2" >
                   <div class="row" >
                       <div class="col-6 text-left" >
                          <h6>{{$card_atr->attribute_name}}</h6>
                       </div>
                       <div class="col-6 text-right" >
                          <a class="#">AED {{$card_atr->attribute_price}}</a>
                          
                       </div>
                   </div>
                   <div class="row" >
                       <div class="col-6 text-left" >
                          <h6>{{$card_atr->attribute_item_name}}</h6>
                       </div>
                       <div class="col-6 text-right" >
                          <a class="#">{{$card_atr->attribute_qty}}</a>
                       </div>
                   </div>
                   <hr>
                   <div class="row" >
                       <div class="col-6 text-left" >
                          <h6></h6>
                       </div>
                       <div class="col-6 text-right">
                          <p>{{$card_atr->attribute_price*$card_atr->attribute_qty}}</p>
                            <form action="{{ url('remove/card/attribute') }}" method="POST" class="removeForm{{$key}}">
                                @csrf
                                <!-- <input type="hidden" name="service_id" value="{{$card->service_id}}">
                                <input type="hidden" name="card_id" value="{{$card->id}}">
                                <input type="hidden" name="card_atr_id" value="{{$card_atr->id}}"> -->

                                <input type="hidden" name="service_id" value="{{$card->service_id}}" class="removeFormServiceId{{$key}}">
                                <input type="hidden" name="card_id" value="{{$card->id}}" class="removeFormCardId{{$key}}">
                                <input type="hidden" name="card_atr_id" value="{{$card_atr->id}}" class="removeFormCardAtrId{{$key}}">
                                
                                <button type="button" class="text-danger" onclick="removeBooking({{$key}})">Remove</button>
                            </form>
                       </div>
                   </div>
                </div> 
            @else
                <div class="card p-2 mb-2" >
                   <div class="row" >
                       <div class="col-6 text-left">
                          <!-- <h6>{{$card_atr->attribute_item_name}}</h6> -->
                          <h6>{{$card->service?$card->service->name:''}} -> 
                            @if($card_atr->main_sub_cat_id && isset($card_atr->main_sub_cat_id))
                                {{$card_atr->main_sub_cat?$card_atr->main_sub_cat->name:''}} -> 
                            @endif
                            @if($card_atr->child_cate_id && isset($card_atr->child_cate_id))
                                {{$card_atr->child_cate?$card_atr->child_cate->name:''}} -> 
                            @endif

                            {{$card_atr->attribute_item_name}}</h6>
                       </div>
                       <div class="col-6 text-right" >
                            <a class="#" >{{$card_atr->attribute_qty}} x AED {{$card_atr->attribute_price}}</a>
                            <br>
                            <button type="button" class="text-danger" onclick="removeBookingAtr({{$card->id}}, {{$card_atr->id}})">Remove</button>
                       </div>
                   </div>
                </div>
            @endif 
            <?php
            	$sub_total += $card_atr->attribute_price*$card_atr->attribute_qty;
            	$total += $card_atr->attribute_price*$card_atr->attribute_qty;
                // $sub_total += $card_atr->attribute_price;
                // $total += $card_atr->attribute_price;
            ?>
            @endforeach
        @endif
        <div class="card p-2 mb-2" >
            <div class="row mt-10" >
                <div class="col-6 text-left">
                   	<h6>Subtotal</h6>
                </div>
                <div class="col-6 text-right" >
                   <a class="#" >AED {{$sub_total}}</a>
                </div>
            </div>
     	</div> 
        @if($card)
            @if($card && $card->material_status=='Apply')
            <div class="card p-2 mb-2" >
                <div class="row mt-10" >
                    <div class="col-6 text-left" >
                        <h6>Material Charge</h6>
                    </div>
                    <div class="col-6 text-right" >
                       <a class="javascript:" >AED {{$card->material_charge}}</a>
                    </div>
                </div>
            </div> 
             <?php $total += $card->material_charge; ?>
            @endif
        @endif
       	
       	
        @if($card && count($card->card_addon))
        <?php $addonAmt = $card->card_addon->sum('value'); ?>
        <div class="card p-2 mb-2" >
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
        @endif
                
        
        <?php
          $coupon = App\CardCoupon::where('card_id',$card?$card->id:'')->first();
          if($coupon){
            $amount = $coupon->amount;
            if($coupon->type=='Amt'){
              $total -= $amount;
              $coupon_Amt = $amount;
            } else {
              $per = ($amount / 100) * $total;
              
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
        <div class="card p-2 mb-2" >
            <div class="row mt-10" >
                <div class="col-6 text-left" >
                    <h6>Coupon</h6>
                    <p>Coupon Applied</p>
                </div>
                <div class="col-6 text-right" >
                    <a class="javascript:" >AED {{$coupon_Amt}}</a>
                    <br>
                    <a href="javascript:" onclick="removeCoupon()" class="text-danger">Remove</a>
                </div>
            </div>
        </div> 
        @endif
        @if($card && $card->cod_charge)
        <div class="card p-2 mb-2">
            <div class="row mt-10" >
                <div class="col-6 text-left" >
                    <h6>Cash Surcharge</h6>
                </div>
                <div class="col-6 text-right" >
                   <a class="javascript:" >AED {{$card->cod_charge}}</a>
                </div>
            </div>
        </div> 
         <?php $total += $card->cod_charge; ?>
        @endif
        
        @if($card && $card->tip_id)
        <div class="card p-2 mb-2">
            <div class="row mt-10" >
                <div class="col-6 text-left">
                    <h6>Tip</h6>
                </div>
                <div class="col-6 text-right" >
                   <a class="javascript:" >AED {{$card->tip_id}}</a>
                </div>
            </div>
        </div> 
        <?php $total += $card->tip_id; ?>
        @endif
     	<div class="card p-2 mb-2">
           	<div class="row mt-10" >
               	<div class="col-6 text-left" >
                  	<h6>Total</h6>
               	</div>
               	<div class="col-6 text-right" >
                  	<a class="javascript:" >AED {{$total}}</a>
               	</div>
           	</div>
        </div> 

	</div>
    <input type="hidden" value="{{$total}}" class="grand_total">
    <script src="https://checkout.tabby.ai/tabby-promo.js"></script>
    <script>
        new TabbyPromo({
            selector: '#tabby',
            price: jQuery('.grand_total').val(),
        });
    </script>
    <script src="https://checkout.tabby.ai/tabby-card.js"></script>