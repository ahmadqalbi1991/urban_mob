<?php 
	$card = App\Card::find($card_id);
 ?>

<div class="card mb-2">
    <div class="row p-2">
        <div class="col-md-6 text-left" >
        	 <p class="m-0">Total Amount</p>
                @if($card)
                    <h5>AED {{$card->g_total}}</h5>
                    <input type="hidden" value="{{$card?$card->id:''}}" class="cc_card_id">
                    <?php $cart_val = $card->g_total; ?>
                @else
                    <?php $cart_val = '0'; ?>
                @endif
        </div>
        <div class="col-md-6">
            <p class="card-title m-0">
                <a class="view-o-d" href="javascript:">
				    View Order Details
				</a>
			</p> 
        </div>
    </div> 
        <?php 
            $setting = App\HomeSetting::first(); 
            $net_val = $setting->min_cart_value-$cart_val;
        ?>
        @if($card && $card->payment_moad)
            @if($card && $card->payment_moad=='Online' || $card->payment_moad=='Cash')
                <p class="p-2">  <button type="submit" class="urban_btn" > Pay Now</button></p>
            @else
                @if($card && $card->g_total>0 && $card->card_attribute->first() && isset($card->card_attribute->first()->attribute_item_id))
                    @if($card && $card->g_total>0 && $card->card_attribute->first() && isset($card->card_attribute->first()->attribute_item_id) && $card->material_charge=='0' || $card->material_charge>'0')
                        @if(Auth::check())
                            @if($setting && $setting->min_cart_value<=$cart_val)
                                <p class="m-0 p-2"><button type="button" data-bs-toggle="modal" href="#exampleModalToggle" role="button" class="urbun_btn text-orange btn btn-dark w-100"> Proceed</button></p>
                            @else
                                <p class="m-0 p-2"><button type="button" onclick="minCartAlert({{$net_val}})" role="button" class="urbun_btn text-orange btn btn-dark w-100"> Proceed</button></p>
                            @endif
                        @else
                        <p class="m-0 p-2">  <button type="button" onclick="show('popup')" class="urban_btn w-100"> Proceed</button> </p>
                        @endif
                    @else
                        <p class="m-0 p-2">    
                            <a href="#Materialscharge" class="urbun_btn text-orange btn btn-dark w-100">
                                <button type="button" href="#Materialscharge" onclick="meterialalert()" role="button"> Proceed</button>
                            </a>
                        </p>
                    @endif
                @else
                <p class="m-0 p-2">   <button type="button" onclick="alertfun()" role="button" class="urbun_btn text-orange btn btn-dark w-100"> Proceed</button></p>
                @endif
               
            @endif
        @else
            @if($card && $card->g_total>0 && $card->card_attribute->first() && isset($card->card_attribute->first()->attribute_item_id))
                @if($card && $card->g_total>0 && $card->card_attribute->first() && isset($card->card_attribute->first()->attribute_item_id) && $card->material_charge=='0' || $card->material_charge>'0')
                    @if(Auth::check())
                        @if($setting && $setting->min_cart_value<=$cart_val)
                            <p class="m-0 p-2"><button type="button" data-bs-toggle="modal" href="#exampleModalToggle" role="button" class="urbun_btn text-orange btn btn-dark w-100"> Proceed</button></p>
                        @else
                            <p class="m-0 p-2"><button type="button" onclick="minCartAlert({{$net_val}})" role="button" class="urbun_btn text-orange btn btn-dark w-100"> Proceed</button></p>
                        @endif
                    @else
                        <p class="p-2 m-0"> <button type="button" onclick="show('popup')" class="urban_btn w-100"> Proceed</button> </p>
                    @endif
                @else
                <p class="m-0 p-2">    <a href="#Materialscharge" class="urbun_btn text-orange btn btn-dark w-100">
                    <button type="button" href="#Materialscharge" onclick="meterialalert()" role="button"> Proceed</button>
                    </a></p>
                @endif
            @else
               <p class="m-0 p-2"> <button type="button" onclick="alertfun()" role="button" class="urbun_btn text-orange btn btn-dark w-100"> Proceed</button></p>
            @endif
      
        @endif
        <div class="text-center">
            <p class="minalert text-danger"></p>
        </div>

</div>

	<div class="v-c-d">
		<?php $sub_total = '0'; $total = '0'; ?>
        @if($card && count($card->card_attribute))
            @foreach($card->card_attribute as $key => $card_atr)
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
                      <a class="#">{{$card_atr->attribute_price*$card_atr->attribute_qty}}</a>
                      
                        <form action="{{ url('remove/card/attribute') }}" method="POST" class="removeForm{{$key}}">
                            @csrf
                            <input type="hidden" name="service_id" value="{{$card->service_id}}">
                            <input type="hidden" name="card_id" value="{{$card->id}}">
                            <input type="hidden" name="card_atr_id" value="{{$card_atr->id}}">
                            <button type="button" class="text-danger" onclick="removeBooking({{$key}})">Remove</button>
                        </form>
                      
                   </div>
               </div>
            </div> 
            <?php
                $sub_total += $card_atr->attribute_price*$card_atr->attribute_qty;
                $total += $card_atr->attribute_price*$card_atr->attribute_qty;
            ?>
            @endforeach
        @endif
        <div class="card p-2 mb-2" >
            <div class="row " >
                <div class="col-6 text-left">
                   	<h6>Subtotal</h6>
                </div>
                <div class="col-6 text-right" >
                   <a class="javascript:">AED {{$sub_total}}</a>
                </div>
            </div>
     	</div> 
       	<?php
          $coupon = App\Coupon::find($card->coupon_id);
          if($coupon){
            $amount = $coupon->amount;
            if($coupon->type=='Amt'){
              $total = $sub_total - $amount;
              $coupon_Amt = $amount;
            } else {
              $per = ($amount / 100) * $sub_total;
              $total = $sub_total - $per;
              $coupon_Amt = $per;
            }
          } else {
            $coupon_Amt = '00';
          }
        ?>
        @if($card && $card->coupon_id)
        <div class="card p-2 mb-2" >
            <div class="row" >
                <div class="col-6 text-left" >
                    <h6>Coupon</h6>
                    <p>Coupon Applied</p>
                </div>
                <div class="col-6 text-right">
                    <a class="javascript:" >AED {{$coupon_Amt}}</a>
                </div>
            </div>
        </div> 
        @endif
       	<div class="card p-2 mb-2" >
            <div class="row " >
                <div class="col-6 text-left" >
                   	<h6>Addons</h6>
                </div>
                <div class="col-6 text-right">
                   <a class="javascript:" >AED 00</a>
                </div>
            </div>
     	</div> 
        @if($card && $card->tip_id)
        <div class="card p-2 mb-2" >
            <div class="row " >
                <div class="col-6 text-left">
                    <h6>Tip</h6>
                </div>
                <div class="col-6 text-right">
                   <a class="javascript:" >AED {{$card->tip_id}}</a>
                </div>
            </div>
        </div> 
        <?php $total += $card->tip_id; ?>
        @endif
        @if($card && $card->cod_charge)
        <div class="card p-2 mb-2" >
            <div class="row " >
                <div class="col-6 text-left" >
                    <h6>Cash Surcharge</h6>
                </div>
                <div class="col-6 text-right">
                   <a class="javascript:" >AED {{$card->cod_charge}}</a>
                </div>
            </div>
        </div> 
         <?php $total += $card->cod_charge; ?>
        @endif
        @if($card)
        @if($card && $card->material_status=='Apply')
        <div class="card p-2 mb-2">
            <div class="row " >
                <div class="col-6 text-left" >
                    <h6>Material Charge</h6>
                </div>
                <div class="col-6 text-right">
                   <a class="javascript:" >AED {{$card->material_charge}}</a>
                </div>
            </div>
        </div> 
         <?php $total += $card->material_charge; ?>
        @endif
        @endif
     	<div class="card p-2 mb-2" >
           	<div class="row " >
               	<div class="col-6" >
                  	<h6>Total</h6>
               	</div>
               	<div class="col-6 text-right">
                  	<a class="javascript:" >AED {{$total}}</a>
               	</div>
           	</div>
        </div> 

	</div>