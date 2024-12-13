@extends('web.layout.header')
@section('title','Card Details')
@section('content')
<form action="{{route('update.card')}}" method="POST" enctype="multipart/form-data">
@csrf	
<input type="hidden" name="card_id" class="card_id" value="{{$card?$card->id:''}}">
   <main>
    <section>
    	
        <div class="container">
	        <div class="head-1">
	            <h3><a href="javascript:" onclick="nextPrev(-1)"><</a> {{$service?$service->name:''}}</h3>
	        </div>
        
         	<!-- Circles which indicates the steps of the form: -->
		    <div class="step1">
		        <div class="row">
		            <div class="col-3">
		            	<span class="step">1</span>
		            	<p>Location</p>
		           </div>

		           <div class="col-3">
		            	<span class="step">2</span>
		            	<p>Choose Time</p>
		           </div>

		           <div class="col-3">
		            	<span class="step">3</span>
		            	<p>Confirmation</p>
		           </div>

		           <div class="col-3">
		            	<span class="step">4</span>
		            	<p>Payment</p>
		           </div>
		        </div>
          	</div>
        
        <!-- One "tab" for each step in the form: -->

        <div class="tab "> 
         	<div class="row mt-40">
         		<div class="col-md-6"> 

           <div class="card mb-2 p-3" >
            <div class="row" >
            <div class="col-6 text-left" >
               <h6>Location</h6>
            </div>
            <div class="col-6 text-right" >
               <a class="t-rel-t" href="javascript:" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Change Location</a>
            </div>
            </div>
            
            <div class="location_details" >
            	<input type="hidden" name="address_id" class="address_id" value="{{$address?$address->id:''}}">
             	<div class="address_section">
             		<div>{{$address?$address->address_type:''}}</div>
	               	<div>{{$address?$address->flat_no:''}}, {{$address?$address->building:''}}, {{$address?$address->locality:''}},</div>
	                <div>{{$address?$address->address:''}}</div>
             	</div>
            </div>
             </div> 

             <div class="card mb-2 text-left p-3" >
               <div class="col-6" >
                  <h6>Contact Detail</h6>
               </div>

               
               <div class="location_details">
                	<div>Name 		: {{$user?$user->name:''}}</div>
                  <div>Mobile No. : {{$user?$user->phone:''}}</div>
                  <div>Email 		: {{$user?$user->email:''}}</div>
               </div>
                </div> 

             <div class="card mb-2 text-left p-3" >
               <h6>Want To add Alternate Number?</h6>
               <div class="row">
                 <div class="col-md-6 ">
                 <input type="text" placeholder="+971-373823884" name="alternative_number" class="alternative_number" value="{{Auth::user()?Auth::user()->phone:''}}">
                  </div>
               </div>   
             </div> 

             	<div class="card mb-2 text-left p-3" >
               	<h6>Booking Instructions</h6>
               	<div class="row">
                 	<div class="mb-3">
							    	<textarea class="form-control" placeholder="Instructions to professional" name="note"></textarea>
							  	</div> 
             		</div> 
             	</div> 

            @if($addons && count($addons))
	            @foreach($addons as $addon)
	            <div class="card mb-2 p-2">
	               	<div class="row" >
	                  	<div class="col-3 text-left" >
		                   	@if($addon && $addon->icon)
					             		<img src="{{ url('uploads/addon/'.$addon->icon) }}" class="img-fluid rounded-start w-100px mt-2" alt="..." >
					             	@else
					             		<img src="{{ url('web/Thumbnail-not-found.jpg') }}" class="img-fluid rounded-start w-100px mt-2" alt="..." >
					             	@endif
	                  	</div>
	                  	<div class="col-4 text-left" >
		                    <h5 class="card-title">{{$addon?$addon->name:''}}</h5>
		                    <p class="card-text">{{$addon?$addon->short_description:''}}</p>
	                  	</div>
	                 	<div class="col-4 text-right">
	                 		<div class="addbutton{{$addon->id}}">
	                 			@if($addon_ids)
			                 		@if (in_array($addon->id, $addon_ids))
				                 		<button type="button" class="btn-sm text-danger mr-40" onclick="RemoveAddon({{$card->id}}, {{$addon->id}})">
			            						<span>+</span> Remove
			            					</button>
			                 		@else
			            					<button type="button" class="btn-sm mr-40" onclick="addAddon({{$card->id}}, {{$addon->id}})">
			            						<span>+</span> Add
			            					</button>
		            					@endif
	            					@else
	            						<button type="button" class="btn-sm mr-40" onclick="addAddon({{$card->id}}, {{$addon->id}})">
		            						<span>+</span> Add
		            					</button>
	            					@endif
	                 		</div>
	                 		
	                    <p class="card-text mt-0 mb-0"><small>Price <b> <del>AED {{$addon?$addon->orignal_price:''}}</del></b></small></p>
	                    <p class="card-text"><small>Discount Price <b>AED {{$addon?$addon->value:''}}</b></small></p>
                  	</div>
		            </div>
	           	</div> 
	           	@endforeach
           	@endif

        </div> 

        <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5 cardlist">
            <div class="card mb-4">
               	<div class="row p-2">
	                <div class="col-md-6 text-left">
	                   <p class="m-0">Total Amount</p>
	                   <h5>AED {{$card?$card->g_total:''}}</h5>
	                </div>
	                <div class="col-md-6">
				        <p class="card-title m-0">
					        <a onclick="showCard()" href="javascript:">
								View Order Details
							</a>
						</p> 
				    </div>
	            </div> 
	              <p class="m-0 p-2">    <button type="button" class="btn btn-dark  text-orange w-100"  onclick="nextPrev(1)">Choose Timing</button></p>
	            </div>

	           <div>
	           	<div class="cardmodal">
	            <?php $sub_total = '0'; $total = '0'; ?>
	            @if(isset($card->card_attribute) && count($card->card_attribute))
		            @foreach($card->card_attribute as $key => $card_atr)
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
					                  <h6>
					                  	{{$card->service?$card->service->name:''}} -> 
		                                @if($card_atr->main_sub_cat_id && isset($card_atr->main_sub_cat_id))
		                                    {{$card_atr->main_sub_cat?$card_atr->main_sub_cat->name:''}} -> 
		                                @endif
		                                @if($card_atr->child_cate_id && isset($card_atr->child_cate_id))
		                                    {{$card_atr->child_cate?$card_atr->child_cate->name:''}} -> 
		                                @endif

		                                {{$card_atr->attribute_item_name}}
					                  </h6>
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
		            ?>
		            @endforeach
	            @endif
	            <div class="card p-2 mb-2" >
		            <div class="row mt-10" >
		                <div class="col-6 text-left">
		                    <h6>Subtotal</h6>
		                </div>
		                <div class="col-6 text-right">
		                    <a class="javascript:" >AED {{$sub_total}}</a>
		                </div>
		            </div>
		      	</div> 
		      	@if($card)
			        @if($card && $card->material_status=='Apply')
			        <div class="card p-2 mb-2" >
			            <div class="row mt-10" >
			                <div class="col-6 text-left">
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
	         	<?php $total += $addonAmt; ?>
	         	@endif

	         	@if($card && $card->tip_id)
		        <div class="card p-2 mb-2" >
		            <div class="row mt-10" >
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
	         	<div class="card p-2 mb-2">
	               	<div class="row mt-10" >
	                   	<div class="col-6 text-left">
	                      	<h6>Total</h6>
	                   	</div>
	                   	<div class="col-6 text-right" >
	                      	<p class="m-0">AED {{$total}}</p>
	                   	</div>
	               	</div>
	            </div> 
	          </div>
	        </div>
	  </div>
		
    </div>
</div>

        
        

        <div class="tab "> 
         <div class="row mt-40">
         <div class="col-md-6" >
         <div class="card mb-2 p-2" id="slot_date_prefered">
              <div class="col-md-6 ">
                <p>Select Date</p>
                <input type="date" name="date" class="form-control slot_date" onchange="filterSlot(this.value)" placeholder="Select Date">
                <small class="slot_date_error text-danger"></small>
              </div> 

          </div>

          <div class="card mb-2 p-2" >
              <div class="col-md-6 " >
                <p>Choose The Preferred Time Slot</p>
                <select name="slot_id" class="form-control slot_value" >
                  	<option value="">Select Slot</option>
                  	@foreach($slots as $slot)
                  	<option value="{{$slot->id}}" >{{$slot->name}}</option>
                  	@endforeach
                </select>
                <small class="slot_value_error text-danger"></small>
              </div> 
          </div>

         </div> 

        <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5 cardlist">
            <div class="card mb-2">
               <div class="row p-2" >
                 <div class="col-md-6 text-left">
                   <p class="m-0">Total Amount</p>
                   <h5>AED {{$card?$card->g_total:''}}</h5>
                 </div>
                   <div class="col-md-6">
			                <p class="card-title m-0">
				                <a onclick="showCard()" href="javascript:">
								    View Order Details
								</a>
							</p> 
			        </div>
               </div> 
                <p class="m-0 p-2">  <button type="button" class="btn btn-dark w-100 text-orange" onclick="nextPrev(1)">Proceed</button></p>
            </div>

            <div>
          	<div class="cardmodal">
            <?php $sub_total = '0'; $total = '0'; ?>
            @if(isset($card->card_attribute) && count($card->card_attribute))
	            @foreach($card->card_attribute as $key => $card_atr)
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
			                           <!--  <input type="hidden" name="service_id" value="{{$card->service_id}}">
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
	            ?>
	            @endforeach
            @endif

						<div class="card p-2 mb-2" >
	            <div class="row " >
	                <div class="col-6 text-left" >
	                    <h6>Subtotal</h6>
	                </div>
	                <div class="col-6 text-right" >
	                    <a class="javascript:" >AED {{$sub_total}}</a>
	                </div>
	            </div>
	      		</div> 
	      		@if($card)
			        @if($card && $card->material_status=='Apply')
								<div class="card p-2 mb-2" >
				            <div class="row " >
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
	            <div class="row " >
	                <div class="col-6 text-left" >
	                    <h6>Coupon</h6>
	                    <p>Coupon Applied</p>
	                </div>
	                <div class="col-6 text-right">
	                    <a class="javascript:" >AED {{$coupon_Amt}}</a>
	                    <br>
                    	<a href="javascript:" onclick="removeCoupon()" class="text-danger">Remove</a>
	                </div>
	            </div>
	      	</div> 
	      	@endif

      		@if($card && count($card->card_addon))
      			<?php $addonAmt = $card->card_addon->sum('value'); ?>
				  <div class="card p-2 mb-2" >
                <div class="row" >
                    <div class="col-6 text-left" >
                       	<h6>Addons</h6>
                    </div>
                    <div class="col-6 text-right" >
                       <a class="javascript:" >AED {{$addonAmt}}</a>
                    </div>
                </div>
         	</div> 
         	<?php $total += $addonAmt; ?>
         	@endif

         	@if($card && $card->tip_id)
			 <div class="card p-2 mb-2" >
	            <div class="row " >
	                <div class="col-6 text-left" >
	                    <h6>Tip</h6>
	                </div>
	                <div class="col-6 text-right" >
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
	                    <h6>COD Charge</h6>
	                </div>
	                <div class="col-6 text-right">
	                   <a class="javascript:" >AED {{$card->cod_charge}}</a>
	                </div>
	            </div>
	        </div> 
	         <?php $total += $card->cod_charge; ?>
	        @endif
			<div class="card p-2 mb-2" >
               	<div class="row" >
                   	<div class="col-6 text-left">
                      	<h6>Total</h6>
                   	</div>
                   	<div class="col-6 text-right">
                      	<p class="m-0">AED {{$total}}</p>
                   	</div>
               	</div>
            </div>
          </div>
        </div>
        </div>
        
           </div>
        </div>

        <div class="tab">
          <div class="row mt-40">
            <div class="col-md-6"> 

               	<div class="card mb-2 p-2" >
	                <div class="row" >
		                <div class="col-6 text-left" >
		                   <h6>Services Details </h6>
		                </div>
	                </div>
	                
	                <div class="location_details" >
	                   <div>{{$service->name}}</div>
	                </div>
                </div> 
    
                <div class="card mb-2 text-left p-2" >
                   <div class="col-6" >
                      <h6>Location</h6>
                   </div>
                   
                   <div class="location_details" >
                   		<div class="address_section">
                      		<div>{{$address?$address->address_type:''}}</div>
		               		<div>{{$address?$address->flat_no:''}}, {{$address?$address->building:''}}, {{$address?$address->locality:''}},</div>
		                	<div>{{$address?$address->address:''}}</div>
		                </div>
                   </div>
                </div> 
    
                <div class="card mb-2 text-left p-2" >
                 	<div class="col-6" >
                    	<h6>Contact Details</h6>
                	</div>
  
                 	<div class="location_details">
                        <div>Name 		: {{$user?$user->name:''}}</div>
	                  	<div>Mobile No. : {{$user?$user->phone:''}}</div>
	                   	<div>Email 		: {{$user?$user->email:''}}</div>
                 	</div>
                </div> 
    
             	</div> 
 						<div class="col-sm-6 col-md-6 col-lg-5 col-xl-5 cardlist">
	            <div class="card mb-2">
	               <div class="row p-2" >
	                 <div class="col-md-6 text-left" >
	                   <p class="m-0">Total Amount</p>
	                   <h5>AED {{$card?$card->g_total:''}}</h5>
	                 </div>
	                     <div class="col-md-6">
			                <p class="card-title m-0">
				                <a onclick="showCard()" href="javascript:">
								    View Order Details
								</a>
							</p> 
			        </div>
	               </div> 
	                 <p class="p-2 m-0"> <button type="button" class="btn btn-dark w-100 text-orange" onclick="nextPrev(1)">Payment Info</button></p>
	            </div>
	             <div>
	             	<div class="cardmodal">
	            <?php $sub_total = '0'; $total = '0'; ?>
	            @if(isset($card->card_attribute) && count($card->card_attribute))
		            @foreach($card->card_attribute as $key => $card_atr)
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
		            ?>
		            @endforeach
	            @endif
	            <div class="card p-2 mb-2" >
		            <div class="row" >
		                <div class="col-6 text-left" >
		                    <h6>Subtotal</h6>
		                </div>
		                <div class="col-6 text-right" >
		                    <a class="javascript:" >AED {{$sub_total}}</a>
		                </div>
		            </div>
		      	</div> 

		      	@if($card)
			        @if($card && $card->material_status=='Apply')
					<div class="card p-2 mb-2" >
			            <div class="row" >
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
		            <div class="row " >
		                <div class="col-6 text-left" >
		                    <h6>Coupon</h6>
		                    <p>Coupon Applied</p>
		                </div>
		                <div class="col-6 text-right">
		                    <a class="javascript:" >AED {{$coupon_Amt}}</a>
		                    <br>
		                    <a href="javascript:" onclick="removeCoupon()" class="text-danger">Remove</a>
		                </div>
		            </div>
	      		</div> 
	      		@endif

	      		@if($card && count($card->card_addon))
        			<?php $addonAmt = $card->card_addon->sum('value'); ?>
					<div class="card p-2 mb-2" >
	                <div class="row" >
	                    <div class="col-6 text-left" >
	                       	<h6>Addons</h6>
	                    </div>
	                    <div class="col-6 text-right" >
	                       <a class="javascript:" >AED {{$addonAmt}}</a>
	                    </div>
	                </div>
	         	</div> 
	         	<?php $total += $addonAmt; ?>
	         	@endif

	         	@if($card && $card->tip_id)
				 <div class="card p-2 mb-2" >
		            <div class="row " >
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
		        
		        @if($card && $card->cod_charge)
		        <div class="card p-2 mb-2" >
		            <div class="row" >
		                <div class="col-6 text-left" >
		                    <h6>COD Charge</h6>
		                </div>
		                <div class="col-6 text-right" >
		                   <a class="javascript:" >AED {{$card->cod_charge}}</a>
		                </div>
		            </div>
		        </div> 
		         <?php $total += $card->cod_charge; ?>
		        @endif
				<div class="card p-2 mb-2" >
	               	<div class="row" >
	                   	<div class="col-6 text-left">
	                      	<h6>Total</h6>
	                   	</div>
	                   	<div class="col-6 text-right">
	                      	<p class="m-0">AED {{$total}}</p>
	                   	</div>
	               	</div>
	            </div> 
	          </div>
	        </div>
          </div>
			
        </div>
    </div>
        
    <div class="tab">
       <div class="row mt-40">

         <div class="col-md-6">

            <div class="card mb-2 p-2">
               	<div class="row">
                  	<p>HAVE A COUPON ?</p>
                  	<div class="coupon">
                  		<span class="text-danger couponerror"></span><br>
                  		<span class="text-success"></span><br>
	                    <input id="coupon_code" class="input-text coupon_code" name="coupon_code" placeholder="Coupon code" type="text">
	                    <button class="tp-btn tp-color-btn banner-animation" onclick="applyCoupon()" type="button">Apply Coupon</button>
	                </div>
                  <div>
                     <p> Tip Amount</p> 
                       <div class="input-field d-flex c-g-20">
                      	
                  	    <div class="form-check">
												  <input class="form-check-input tip_check" type="radio" {{$card->tip_id=='5'?'checked':''}} name="tip" value="5" onclick="payTip(5)" id="exampleRadios1" >
												  <label class="form-check-label" for="exampleRadios1" onclick="payTip(5)">
												    AED 5
												  </label>
												</div>
												<div class="form-check">
												  <input class="form-check-input tip_check" type="radio" {{$card->tip_id=='10'?'checked':''}} name="tip" value="10" onclick="payTip(10)" id="exampleRadios2">
												  <label class="form-check-label" for="exampleRadios2" onclick="payTip(10)">
												    AED 10
												  </label>
												</div>
												<div class="form-check">
												  <input class="form-check-input tip_check" type="radio" {{$card->tip_id=='20'?'checked':''}} name="tip" value="20" onclick="payTip(20)" id="flexRadioDefault3" >
												  <label class="form-check-label" for="flexRadioDefault3" onclick="payTip(20)">
												    AED 20
												  </label>
												</div>
												<div class="form-check">
												  <input class="form-check-input custom_tip" type="radio" onclick="customTip()" id="flexRadioDefault4" >
												  <label class="form-check-label" for="flexRadioDefault4" onclick="customTip()">
												    Custom Tip
												  </label>
												</div>
												
												
                     </div>

                     <div class="input-field tip_input" >
                      <div class="input-group mb-3">
											  <input type="text" class="form-control tip_custom_text" onpaste="return false;"  name="tip" id="paytip" placeholder="Enter Tip" aria-describedby="button-addon2">
											  <button class="btn btn-dark" type="button" onclick="payTip('custom')" id="button-addon2">Pay Tip</button>
											</div>
                     </div>

                  </div>

             
               	</div> 
                 
            </div>
         
            <div class="card mb-2 p-2" >
               <div class="row">
                 	<div class="col-md-6 ">
                   	<div class="location_details p-3">
                   		<P>PAYMENT INFORMATION</P>
                   		<ul class="list-style-none " >

                           	<div class="row mt-10 payment-box" >
                              	<div class="col-8 text-left" >
                                 	PAY BY CARD
                              	</div>
                              	<div class="col-3 text-right" >
                                 	<!-- <input type="radio" name="payment_moad" value="Card" onclick="paymentMode('Card')" required {{$card->payment_moad=='Online'?'checked':''}}/> -->
                              	</div>
                              	
                            </div>

                            <div class="form-group mt-10">
						        <div>
					               <input class="card_redio" type="radio" id="inlineRadio1" onclick="paymentMode('Card')" name="payment_type" value="MamoPay" checked="" data-gtm-form-interact-field-id="2">
					               <label class="" for="inlineRadio1" onclick="paymentMode('Card')"><img src="{{ url('payment-logo/mamo-pay-logo.png') }}" height="50px">  </label><br><br>
					               <input class="card_redio" type="radio" id="inlineRadio2" onclick="paymentMode('Card')" name="payment_type" value="Tabby" data-gtm-form-interact-field-id="0">
					               <label class="" for="inlineRadio2" onclick="paymentMode('Card')">
					               	<img src="{{ url('payment-logo/tabby-new.png') }}" height="50px">
					               	<br>					               	
					               </label>
					               <div id="tabby"></div>
					               <div id="tabbyCard"></div>
				               	</div>
						  	</div>
						  	
				            <!-- <table class="table" style="font-size: 14px;">
							  <tbody>
							    <tr>
							      <td width="64px"><span class="tabby_month_value"></span> AED</td>
							      <td><span class="tabby_month_value"></span> AED</td>
							      <td><span class="tabby_month_value"></span> AED</td>
							      <td><span class="tabby_month_value"></span> AED</td>
							    </tr>
							    <tr>
							      <td width="64px">Today</td>
							      <td>In 1 Month</td>
							      <td>In 2 Month</td>
							      <td>In 3 Month</td>
							    </tr>
							  </tbody>
							</table> -->

                           	<!-- <div class="form-check mt-10 by-card">
							  <input class="form-check-input" type="checkbox" value="MamoPay" onclick="selectPayment('MamoPay')" name="payment_type" id="mamoPay" checked>
							  <label class="form-check-label" for="mamoPay">
							    Payment By MamoPay
							  </label>
							</div>
							<div class="form-check mt-10 by-card">
							  <input class="form-check-input" type="checkbox" value="Tabby" onclick="selectPayment('Tabby')" name="payment_type" id="tabbtpay">
							  <label class="form-check-label" for="tabbtpay">
							    Payment By Tabby
							  </label>
							</div> -->
                            
                          	<div class="row mt-10 payment-box" >
                                <div class="col-8 text-left">
                                    CASH <span class="amount"></span>
                                </div>
                                <div class="col-3 text-right" >
                                    <input type="radio" class="cash_radio" onclick="paymentMode('Cash')" {{$card->payment_moad=='Cash'?'checked':''}}/>
                                </div> 
                         	</div>
                            <input type="hidden" name="payment_moad" value="Card" class="pay_value">
                        </ul>
                    </div>
	                </div>
	                @if($card->payment_moad=='Cash')
                    <p class="cardtool text-warning mt-10">We support sustainability. Go Green, Save mother earth.<br>Pay online and save {{App\HomeSetting::first()?App\HomeSetting::value('cash_surcharge'):'0'}} AED cash surcharge.</p>
                 	@else
                   	<p class="cardtool text-warning mt-10">We support sustainability. Go Green, Save mother earth.<br>Pay online and save {{App\HomeSetting::first()?App\HomeSetting::value('cash_surcharge'):'0'}} AED cash surcharge.</p>
                 	@endif
             
               </div> 
                 
            </div>
         
         
        </div>


         <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5 cardlist">
            <div class="card mb-2">
               <div class="row p-2">
                 <div class="col-md-6 text-left" >
                   <p class="m-0">Total Amount</p>
                   <h5>AED {{$card?$card->g_total:''}}</h5>
                 </div>
                  <div class="col-md-6">
			                <p class="card-title m-0">
				                <a onclick="showCard()" href="javascript:">
								    View Order Details
								</a>
							</p> 
			        </div>
               </div> 
               <?php $cart_val = $card->g_total; ?>
               	<?php 
		            $setting = App\HomeSetting::first(); 
		            $net_val = $setting->min_cart_value-$cart_val;
		        ?>
               @if($setting && $setting->min_cart_value<=$cart_val)
                	<p class="m-0 p-2"><button type="submit" class="btn btn-dark w-100 text-orange">Book Now</button></p>
               @else
                	<p class="m-0 p-2" onclick="minCartAlert({{$net_val}})" ><button type="submit" class="btn btn-dark w-100 text-orange">Book Now</button></p>
               <div class="text-center">
        			<p class="minalert text-danger"></p>
        		</div>
               @endif
                 
            </div>
           <div>
            <?php $sub_total = '0'; $total = '0'; ?>
            @if(isset($card->card_attribute) && count($card->card_attribute))
	            @foreach($card->card_attribute as $key => $card_atr)
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
			                           <!--  <input type="hidden" name="service_id" value="{{$card->service_id}}">
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
	            ?>
	            @endforeach
            @endif
			<div class="card p-2 mb-2" >
	            <div class="row" >
	                <div class="col-6 text-left" >
	                    <h6>Subtotal</h6>
	                </div>
	                <div class="col-6 text-right">
	                    <a class="javascript:" >AED {{$sub_total}}</a>
	                </div>
	            </div>
	      	</div> 
	      	@if($card)
		        @if($card && $card->material_status=='Apply')
				<div class="card p-2 mb-2" >
		            <div class="row" >
		                <div class="col-6 text-left">
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
	            <div class="row " >
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
	      	
      		@if($card && count($card->card_addon))
      			<?php $addonAmt = $card->card_addon->sum('value'); ?>
				  <div class="card p-2 mb-2" >
                <div class="row" >
                    <div class="col-6 text-left" >
                       	<h6>Addons</h6>
                    </div>
                    <div class="col-6 text-right" >
                       <a class="javascript:" >AED {{$addonAmt}}</a>
                    </div>
                </div>
         	</div> 
         	<?php $total += $addonAmt; ?>
         	@endif
         	@if($card && $card->tip_id)
			 <div class="card p-2 mb-2" >
	            <div class="row" >
	                <div class="col-6 text-left" >
	                    <h6>Tip</h6>
	                </div>
	                <div class="col-6 text-right" >
	                   <a class="javascript:" >AED {{$card->tip_id}}</a>
	                </div>
	            </div>
	        </div> 
	         <?php $total += $card->tip_id; ?>
	        @endif
	        
	        @if($card && $card->cod_charge)
			<div class="card p-2 mb-2" >
	            <div class="row " >
	                <div class="col-6 text-left">
	                    <h6>COD Charge</h6>
	                </div>
	                <div class="col-6 text-right" >
	                   <a class="javascript:" >AED {{$card->cod_charge}}</a>
	                </div>
	            </div>
	        </div> 
	        <input type="hidden" name="cod_charge" value="{{$card->cod_charge}}">
	         <?php $total += $card->cod_charge; ?>
	        @endif
	        <input type="hidden" value="{{$total}}" class="grand_total">
			<div class="card p-2 mb-2" >
               	<div class="row" >
                   	<div class="col-6 text-left" >
                      	<h6>Total</h6>
                   	</div>
                   	<div class="col-6 text-right" >
                      	<p class="m-0">AED {{$total}}</p>
                   	</div>
               	</div>
            </div> 
        </div>
       </div>
        
       </div>
        </div>
        
        
   	</div>

    </section>
</main>
</form>

<div class="popup6" id="popup6">
    <div class="text-center p-1" >
     	<div  class="popupcloseicon"><a href="#" onclick="hide('popup6')">X</a></div>

     	@foreach($address_info as $key => $add)
     	<div class="row mt-30 p-5"  >
     		<div class="col-2 text-left" >
           		<div class="form-check">
           			@if($key==0)
				  		<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault{{$key}}" checked>
				  	@else
				  		<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault{{$key}}">
				  	@endif
				</div>
        	</div>
        	<div class="col-8 text-left" >
           		<h6 class="mb-0">{{$add->address_type}}</h6>
            	<p > {{$add->flat_no}}, {{$add->building}}, {{$add->locality}}, {{$add->address}} </p>
        	</div>
        	<div class="col-2">
           		<!-- <button onclick="show('popup5')" class="#" >Edit</button> -->
            </div>
            <hr>
        </div>
        @endforeach

        <div class="text-center mt-3 mb-3" style="color:rgb(146, 146, 33); ">
            <button type="submit" onclick="show('popup5')" style="width:350px; height: 40px; border: 1px solid black; border-radius: 5px; background-color: black;">Add New Location</button>
        </div>  
  	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable">
	    <div class="modal-content">
		    <div class="modal-header">
		        <h5 class="modal-title" id="staticBackdropLabel">Change Location</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		    </div>
	      	<div class="modal-body">
				<div class="card">
					<div class="card-header">
					    Home
					</div>
					<div class="card-body">
						@foreach($address_info->where('address_type','Home') as $key => $add)
					    	<div class="row">
					    		<div class="col-lg-2">
					    			<div class="form-check">
					           			@if($key==0)
									  		<input class="form-check-input" type="checkbox" onclick="changeLocation({{$add}})" id="flexRadioDefault{{$add->id}}" checked>
									  	@else
									  		<input class="form-check-input" type="checkbox" onclick="changeLocation({{$add}})" id="flexRadioDefault{{$add->id}}">
									  	@endif
									</div>
					    		</div>
					    		<div class="col-lg-10">
			            			<p> {{$add->flat_no}}, {{$add->building}}, {{$add->locality}}, {{$add->address}} </p>
					    		</div>
					    	</div>
			            			<hr>
					    @endforeach
					</div>
				</div>

				<div class="card mt-2">
					<div class="card-header">
					    Office
					</div>
					<div class="card-body">
						@foreach($address_info->where('address_type','Office') as $key => $add)
					    	<div class="row">
					    		<div class="col-lg-2">
					    			<div class="form-check">
					           			@if($key==0)
									  		<input class="form-check-input" type="checkbox" onclick="changeLocation({{$add}})" id="flexRadioDefault{{$add->id}}" checked>
									  	@else
									  		<input class="form-check-input" type="checkbox" onclick="changeLocation({{$add}})" id="flexRadioDefault{{$add->id}}">
									  	@endif
									</div>
					    		</div>
					    		<div class="col-lg-10">
			            			<p> {{$add->flat_no}}, {{$add->building}}, {{$add->locality}}, {{$add->address}} </p>
					    		</div>
					    	</div>
					    @endforeach
					</div>
				</div>

				<div class="card mt-2">
					<div class="card-header">
					    Work
					</div>
					<div class="card-body">
						@foreach($address_info->where('address_type','Work') as $key => $add)
					    	<div class="row">
					    		<div class="col-lg-2">
					    			<div class="form-check">
					           			@if($key==0)
									  		<input class="form-check-input" type="checkbox" onclick="changeLocation({{$add}})" id="flexRadioDefault{{$add->id}}" checked>
									  	@else
									  		<input class="form-check-input" type="checkbox" onclick="changeLocation({{$add}})" id="flexRadioDefault{{$add->id}}">
									  	@endif
									</div>
					    		</div>
					    		<div class="col-lg-10">
			            			<p> {{$add->flat_no}}, {{$add->building}}, {{$add->locality}}, {{$add->address}} </p>
					    		</div>
					    	</div>
					    @endforeach
					</div>
				</div>

	      	</div>
		    <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		   	</div>
	    </div>
	</div>
</div>
<input type="hidden" value="{{date('Y-m-d')}}" class="current_date">
@endsection
@section('script')

	<script>     	
     	jQuery(window).on('load', function(){
		    tabbyPromoSniped();
		    tabbyCardSniped();
		});
    </script>
     
   <script>
   	jQuery('.tip_input').hide();
   	jQuery(".slot_date").keydown(false);
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab
    
    function showTab(n) {
      // This function will display the specified tab of the form...
      var x = document.getElementsByClassName("tab");
      
      x[n].style.display = "block";
      //... and fix the Previous/Next buttons:
      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
      } else {
        document.getElementById("nextBtn").innerHTML = "Next";
      }
      //... and run a function that will display the correct step indicator:
      fixStepIndicator(n)
    }
    
    function nextPrev(n) {
      // This function will figure out which tab to display
      var x = document.getElementsByClassName("tab");
      // Exit the function if any field in the current tab is invalid:
     
      if (n == 1 && !validateForm()) return false;
      // Hide the current tab:
      x[currentTab].style.display = "none";
      // Increase or decrease the current tab by 1:
      currentTab = currentTab + n;
      // if you have reached the end of the form...
      if (currentTab >= x.length) {
        // ... the form gets submitted:
        document.getElementById("regForm").submit();
        return false;
      }
      // Otherwise, display the correct tab:
      showTab(currentTab);
    }
    
    function validateForm() {
      // This function deals with validation of the form fields
      var x, y, i, valid = true;
      x = document.getElementsByClassName("tab");
      
      y = x[currentTab].getElementsByTagName("input");
      
      // A loop that checks every input field in the current tab:
      for (i = 0; i < y.length; i++) {
        // If a field is empty...
        if (y[i].value == "") {
          // add an "invalid" class to the field:
          y[i].className += " invalid";
          // and set the current valid status to false
          valid = false;
        }
      }
      var slot_date = jQuery('.slot_date').val();
      var selectslot = jQuery('.slot_value').val();
      var alternative_number = jQuery('.alternative_number').val();
  		
      if( y.length == '1'){
      		if(slot_date==''){
		      	jQuery('.slot_date_error').text('Please Select Date Slot.');
		      	valid = false;
		    }

		    if(selectslot== ""){
		      	jQuery('.slot_value_error').text('Please Choose a Time Slot.');
		      	if(!slot_date==''){
		      		jQuery('.slot_date_error').text('');
		      	}
		      	valid = false;
		    }
      }


      // If the valid status is true, mark the step as finished and valid:
      if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
      }
      return valid; // return the valid status
    }
    
    function fixStepIndicator(n) {
      // This function removes the "active" class of all steps...
      var i, x = document.getElementsByClassName("step");
      for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
      }
      //... and adds the "active" class on the current step:
      x[n].className += " active";
    }
    </script>
    
    <script>
      $ = function(id) {
        return document.getElementById(id);
      }
      
      var show = function(id) {
         $(id).style.display ='block';
      }
      var hide = function(id) {
         $(id).style.display ='none';
      }
      </script>

      <script>
      	function changeLocation(address) {
      		if(address){
      			jQuery('.address_id').val(address.id);
      			jQuery('.address_section').html('<div>'+address.address_type+'</div><div>'+address.flat_no+', '+address.building+','+address.locality+',</div> <div>'+address.address+'</div>');
      		}
      		
      	}
      </script>

      <script>
      	function applyCoupon() {
      		var coupon_code = jQuery('.coupon_code').val();
      
      		if(coupon_code){
      			jQuery.ajax({
			            headers: {
			                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			               },    
			            type: 'Post',
			            url: "{{ url('apply-coupon') }}",
			            data: {
			            	coupon_code : coupon_code,
			            	card_id : jQuery('.card_id').val(),
			            },
			            success: function (data) {
			                 console.log(data);
			                 if(data.status=='True'){
			                 	var service_id = data.service_id;
			                 	jQuery('.text-danger').text('');
			                 	jQuery('.text-success').text(data.msg);
			                 	jQuery.ajax({
			                        headers: {
			                               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			                           },    
			                        type: 'Post',
			                        url: "{{ route('get.card.booking') }}",
			                        data: {
			                           service_id : service_id,
			                        },
			                        dataType: 'json',
			                        success: function (response) {
			                             console.log(response);
			                             jQuery('.couponerror').html('');       
			                             jQuery('.cardlist').html(response.modal_view); 
			                             tabbyPromoSniped(); 
										 tabbyCardSniped();            
			                        },
			                        error: function (response) {
			                            console.log(response);
			                        }
			                  }); 
			                 } else {
			                 	jQuery('.couponerror').text(data.msg);
			                 }		                      
			            },
			            error: function (data) {
			                console.log(data);
			            }
			      });
      		}
      	}
      </script>

      

      <script>
      	
      	function customTip() {
      	
      		if(jQuery('.custom_tip').prop('checked')==true){
      			jQuery('.tip_check').prop('checked', false);
						jQuery('.custom_tip').prop('checked', true);
      			jQuery('.tip_input').show();
      		} else {
      			
      			// jQuery('.tip_check').prop('checked', true);
						jQuery('.custom_tip').prop('checked', false);
      			jQuery('.tip_input').hide();
      		}
      	}
      </script>

      <script>
		  jQuery(document).ready(function() {

		      jQuery('#paytip').on('keypress', function(e) {
		          
		          var phone = jQuery('#paytip').val();
		          console.log(phone);
		          var regex = new RegExp("^[0-9\b]+$");
		          var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
		          // for 10 digit number only
		          if (phone.length > 8) {
		              e.preventDefault();
		              return false;
		          }
		          
		          if (regex.test(str)) {
		              return true;
		          }
		          e.preventDefault();
		          return false;
		      });

		   });
		</script>

      <script>
      	function payTip(tip) {
      		if(tip=='custom'){
      			var tipamt = jQuery('.tip_custom_text').val();
      			
      		} else {
      			var tipamt = tip;
      			jQuery('.tip_input').hide();      			
				jQuery('.custom_tip').prop('checked', false);
      		}
      		if(tipamt){
      			jQuery.ajax({
                    headers: {
                           'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                       },    
                    type: 'Post',
                    url: "{{ route('pay.tip') }}",
                    data: {
                       card_id : jQuery('.card_id').val(),
                       tip : tipamt,
                    },
                    success: function (response) {
                         console.log(response);
                         jQuery('.cardlist').html('');       
                         jQuery('.cardlist').html(response.modal_view);  
                         tabbyPromoSniped(); 
						 tabbyCardSniped();           
                    },
                    error: function (response) {
                        console.log(response);
                    }
              }); 
      		}
      	}
      </script>

      <script>
      	jQuery('.cardtool').hide();
      	jQuery('.by-card').hide();
      	function paymentMode(mode) {
      		if(mode=='Cash'){      			
      			jQuery('.pay_value').val('Cash');
      			jQuery('.card_redio').prop('checked', false);
      			jQuery('.card_redio').attr('checked', false);
      			var codamt = '10';
      			var moad = 'Cash';
      			jQuery('.cardtool').show();
      			jQuery('.by-card').hide();
      		} else {
      			jQuery('.pay_value').val('Card');
      			jQuery('.cash_radio').prop('checked', false);
      			jQuery('.cash_radio').attr('checked', false);
      			var codamt = '0';
      			var moad = 'Card';
      			jQuery('.cardtool').hide();
      			jQuery('.by-card').show();
      		}
      		jQuery.ajax({
                    headers: {
                           'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                       },    
                    type: 'Post',
                    url: "{{ route('cod.charge') }}",
                    data: {
                       card_id : jQuery('.card_id').val(),
                       codamt : codamt,
                       moad : moad,
                    },
                    success: function (response) {
                         console.log(response);
                         jQuery('.cardlist').html('');       
                         jQuery('.cardlist').html(response.modal_view);             
                    },
                    error: function (response) {
                        console.log(response);
                    }
              });
      	}
      </script>

      <script>
      	function addAddon(card_id, addon_id) {
      		var addbutton = '.addbutton'+addon_id;
      
      			jQuery.ajax({
                  headers: {
                         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                     },    
                  type: 'Post',
                  url: "{{ url('add/addon') }}",
                  data: {
                     card_id : card_id,
                     addon_id : addon_id,
                     dtfrm : 'Add',
                  },
                  success: function (response) {
                       console.log(response);
                       jQuery(addbutton).html('<button type="button" class="btn-sm text-danger mr-40" onclick="RemoveAddon('+card_id+', '+addon_id+')"><span>-</span> Remove</button>');
                       
                       jQuery('.cardlist').html('');       
                       jQuery('.cardlist').html(response.modal_view);             
                  },
                  error: function (response) {
                      console.log(response);
                  }
            });
      	}
      </script>
      <script>
      	function RemoveAddon(card_id, addon_id) {
      		var addbutton = '.addbutton'+addon_id;
     
      			jQuery.ajax({
                  headers: {
                         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                     },    
                  type: 'Post',
                  url: "{{ url('add/addon') }}",
                  data: {
                     card_id : card_id,
                     addon_id : addon_id,
                     dtfrm : 'Remove',
                  },
                  success: function (response) {
                       console.log(response);
                       jQuery(addbutton).html('<button type="button" class="btn-sm mr-40" onclick="addAddon('+card_id+', '+addon_id+')"><span>+</span> Add</button>');
                       
                       jQuery('.cardlist').html('');       
                       jQuery('.cardlist').html(response.modal_view);             
                  },
                  error: function (response) {
                      console.log(response);
                  }
            });
      	}
      </script>
		 	<script type="text/javascript">
		    window.onload=function(){//from ww  w . j  a  va2s. c  o  m
						var today = new Date().toISOString().split('T')[0];
						document.getElementsByName("date")[0].setAttribute('min', today);
    		}

      </script> 

      <script>
      	function filterSlot(selectDate) {
      		var currentDate = jQuery('.current_date').val();
      		
      		if(currentDate==selectDate){
      			jQuery.ajax({
	                headers: {
	                         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	                    },    
	                type: 'Post',
	                url: "{{ url('get/slot') }}",
	                data: {
	                     from : 'current'
	                },
	                success: function (response) {
	                       jQuery('.slot_value').html('');       
	                       jQuery('.slot_value').html(response);             
	                },
	                error: function (response) {
	                      console.log(response);
	                }
	            });
      		} else {
      			if(selectDate>=currentDate){
      				jQuery.ajax({
		                headers: {
		                         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
		                    },    
		                type: 'Post',
		                url: "{{ url('get/slot') }}",
		                data: {
		                     from : 'no'
		                },
		                success: function (response) {
		                       jQuery('.slot_value').html('');       
		                       jQuery('.slot_value').html(response);          
		                },
		                error: function (response) {
		                      console.log(response);
		                }
		            });
      			} else {
      				jQuery('.slot_value').html(''); 
      			}
      			
      		}

      	}
      </script>

      	<script src="https://checkout.tabby.ai/tabby-promo.js"></script>
	    <script>      

	    function tabbyPromoSniped() {
	    	
	    	jQuery('#tabby').html('');
	      	new TabbyPromo({
		        selector: '#tabby',
		        price: jQuery('.grand_total').val(),
		    });
	    }
	    </script>

	    <script src="https://checkout.tabby.ai/tabby-card.js"></script>
	    <script>
	    	function tabbyCardSniped() {
	    		
	    		jQuery('#tabbyCard').html('');
	    		new TabbyCard({
				  selector: '#tabbyCard', // empty div for TabbyCard.
				  currency: 'AED', // required, currency of your product. AED|SAR|KWD|BHD|QAR only supported, with no spaces or lowercase.
				  lang: 'en', // Optional, language of snippet and popups.
				  price: jQuery('.grand_total').val(), // required, total price or the cart. 2 decimals max for AED|SAR|QAR and 3 decimals max for KWD|BHD.
				  size: 'narrow', // required, can be also 'wide', depending on the width.
				  theme: 'black', // required, can be also 'default'.
				  header: false // if a Payment method name present already. 
				});
	    	}
			
		</script>

@endsection



<style type="text/css">
	
	#slot_date_prefered input[type="date"]{
		padding: 0rem 0.75rem !important;
	}

</style>