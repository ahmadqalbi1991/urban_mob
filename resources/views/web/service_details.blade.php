@extends('web.layout.header')
@section('title',$service->meta_title)
@section('meta_tags')
<meta name="description" content="{{$service->meta_description}}">
@endsection
@section('content')

<section class="pt-10 p-relative bg-alice">
    <div class="container" >
    	@if($service_header && $service_header->photos)
	    <div class="service_d_banner" style="background-image: url(<?= URL::to('/') ?>/uploads/service/gallery/{{$service_header->photos}});" title="{{$service->name}}">
	    @else
	    <div  class="service_d_banner" style="background-image: url(<?= URL::to('/') ?>/web/Banner-not-found.jpg);" title="{{$service->name}}">
	    @endif
	        <img src="" alt="">
	        <div class="featured-text banner-style service_banner">
	            <h2  class="text-white">{{$service->name ?? ''}}</h2>
	            <h4 class="text-white">Starting just AED {{$service->price ?? ''}}</h4>
	            <p class="text-white">{!! $service->short_description !!}</p>
	        </div>
	    </div>
    </div>
       <!-- product details  start -->
    <div class="container">
        <div class="row">
           <div class="col-xl-12">
              <div class="product__details-tab-nav">
                 <nav>
                    <div class="product__details-tab-nav-inner nav tp-tab-menu d-flex flex-sm-nowrap flex-wrap custom-service-tab" id="nav-tab-info" role="tablist" style="    overflow-x: auto; overflow-y:hidden">
                       
                       @foreach(App\ServiceAttributeValueItem::where('service_id',$service->id)->groupBy('sub_category_id')->get() as $key => $ser_atr_item)
	                       	@if($ser_atr_item->sub_category)
	                           @if($key=='0')
		                           <a class="nav-link text-center active" href="#{{ str_replace(' ','_',$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:'')}}" >{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}</a>
		                           <span id="marker" class="tp-tab-line d-none d-sm-inline-block"></span>
	                           @else
											<a class="nav-link text-center" href="#{{ str_replace(' ','_',$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:'')}}">{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}</a>
	                           @endif
                           @endif
                       @endforeach	

                    </div>
                 </nav> 
              </div>
           </div>
        </div>
    </div>
     
   	<div class="container mt-20">

	    <div class="row">
	        <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">

	        	@if(App\ServiceAttributeValueItem::where('service_id',$service->id)->count() && App\ServiceAttributeValueItem::where('service_id',$service->id)->with('sub_category')->first()->sub_category)
		        	@foreach(App\ServiceAttributeValueItem::where('service_id',$service->id)->groupBy('sub_category_id')->get() as $key => $ser_atr_item)

		        		@if($ser_atr_item->sub_category)
			            
					    	<div class="card mb-2 p-2" id="{{ str_replace(' ','_',$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:'')}}">
		               	<div class="row align-items-center card_entry_d" >
		                  	<div class="col-md-2 col-3">
				                  @if($ser_atr_item->sub_category && $ser_atr_item->sub_category->icon)
					             		<img src="{{ url('uploads/category/'.$ser_atr_item->sub_category->icon) }}" class="img-fluid w-100px rounded-start" alt="{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}" title="{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}" >
					             	@else
					             		<img src="{{ url('web/Thumbnail-not-found.jpg') }}" class="img-fluid rounded-start w-100px " alt="{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}" title="{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}">
					             	@endif
		                  	</div>
	                  		<div class="col-md-6 col-5 p-0">
				                    <h5 class="card-title">{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}</h5>
				                    <p class="card-text" id="sub-desc-cat">{{substr($ser_atr_item->sub_category?$ser_atr_item->sub_category->meta_description:'','0','300')}} </p>
		                  	</div>
	                     	<div class="col-md-4 col-4 ps-0 text-center">
	                     			@if(isset($ser_atr_item->child_category_id) && $ser_atr_item->child_category_id)
			            				<button type="button" class="btn-sm " data-bs-toggle="modal" data-bs-target="#exampleModal{{$key}}">
			            					<span>+</span> Add
			            				</button>
			            			@else 
			            				<button type="button" class="btn-sm " data-bs-toggle="modal" data-bs-target="#attributeModal{{$key}}">
			            					<span>+</span> Add
			            				</button>
			            			@endif
				                    <p class="card-text" ><small>Starting AED {{$ser_atr_item->sub_category?$ser_atr_item->sub_category->price:''}}</small></p>
		                  	</div>
				            </div>
			           	</div> 
				    	@endif
					   @if(isset($ser_atr_item->child_category_id) && $ser_atr_item->child_category_id)
					    
							<div class="modal fade" id="exampleModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
								    <div class="modal-content">
									    <div class="modal-header">
									        <h5 class="modal-title" id="exampleModalLabel">{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}</h5>
									        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									    </div>
								      	<div class="modal-body">
									       
									        @foreach(App\ServiceAttributeValueItem::where('service_id',$service->id)->where('sub_category_id',$ser_atr_item->sub_category_id)->get() as $index => $child_ser_atr_item)
										        <div class="card mb-2 p-2">
										            <div class="row align-items-center">
											            <div class="col">
											            	@if($child_ser_atr_item->child_category)
											                	<img src="{{ url('uploads/child-category/'.$child_ser_atr_item->child_category->icon) }}" class="img-fluid w-80px rounded-start" alt="{{$child_ser_atr_item->child_category?$child_ser_atr_item->child_category->name:''}}" title="{{$child_ser_atr_item->child_category?$child_ser_atr_item->child_category->name:''}}" >
											               @else
 																	<img src="{{ url('web/Thumbnail-not-found.jpg') }}" class="img-fluid w-80px  rounded-start" alt="{{$child_ser_atr_item->child_category?$child_ser_atr_item->child_category->name:''}}" title="{{$child_ser_atr_item->child_category?$child_ser_atr_item->child_category->name:''}}" >
											               @endif
											            </div>
											            <div class="col">
											                <div class="text-left" >
											                  <h6 class="card-title">{{$child_ser_atr_item->child_category?$child_ser_atr_item->child_category->name:''}}</h6>
											                </div>
											            </div>
										              	<div class="col">
														  <div class="text-left " >
										                 		<button type="button" class="btn btn-warning btn-sm  pr-3 pl-3 pt-1 pb-1" onclick="OpenAttrModel({{$child_ser_atr_item->child_category_id}},{{$child_ser_atr_item->id}},{{$key}})">
									            					<span>+</span> Add
									            				</button>
									            				<input type="hidden" class="sub_cate_name{{$child_ser_atr_item->id}}" value="{{$child_ser_atr_item->child_category?$child_ser_atr_item->child_category->name:''}}">
										                 		<p class="card-text  m-0" >Starting AED {{$child_ser_atr_item->child_category?$child_ser_atr_item->child_category->price:''}}</p>
										               		</div>
										             	</div>
										            </div>
										        </div>									      

								            @endforeach
								      	</div>
								      	<div class="modal-footer">
								        		<button type="button" class="btn btn-dark modelclose" id="attrclose" data-bs-dismiss="modal">OK</button>
								      	</div>
								    </div>
								</div>
							</div>
						@else 
							<?php
								$attribute_ids = [];
								foreach (App\ServiceAttributeValueItem::where('service_id',$service->id)->where('sub_category_id',$ser_atr_item->sub_category_id)->get() as $value) {
									array_push($attribute_ids, $value->id);
								}
							?>
							<!-- Modal -->
							<div class="modal fade" id="attributeModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
								    <div class="modal-content">
									    <div class="modal-header">
									        <h5 class="modal-title" id="exampleModalLabel">{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->name:''}}</h5>
									        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									    </div>
								      	<div class="modal-body sub-category-popup">
								      		@foreach(App\ServiceAttributeValue::whereIn('ser_attr_val_item_id',$attribute_ids)->get() as $attr => $attributeItems)
									      		@if($attributeItems->attributeItem)
											        <div class="card mb-2">
											            <div class="row align-items-center">
											              	<div class="col-8">
												                <div class="card-body p-2" >
												                  <h6 class="card-title">{{$attributeItems->attributeItem->value}}</h6>
												                  <p class="m-0">Starting AED {{$attributeItems->attribute_price}}</p>
												                </div>
											              	</div>
												            <div class="col-4">
												               <div class="d-flex align-items-center" >
												               	<?php $formid =  'addattrforms'.$attributeItems->id.''; $buttonForm =  'buttonForms'.$attributeItems->id.''; $add_booking_from = 'add_booking_from'.$attributeItems->id.''; $addbtn = 'addbtn'.$attributeItems->id.'';?>
												               	<button class="add-button-color-change" onclick="showForm({{$formid}}, {{$buttonForm}}, {{$attributeItems->id}})" id="{{$buttonForm}}">+ Add</button>
												               	<form action="#" method="POST" enctype="multipart/form-data" id="{{$formid}}" class="{{$add_booking_from}}" style="display: none;">
		         														@csrf
		         														<input type="hidden" name="service_id" value="{{$service->id}}">
		         														<input type="hidden" name="category_id" value="{{$service->category_id}}">
		         														<input type="hidden" name="main_sub_cat_id" value="{{$ser_atr_item->sub_category?$ser_atr_item->sub_category->id:''}}">
		         														<input type="hidden" name="sub_cate_id" value="{{$ser_atr_item->sub_category_id}}">
		         														<input type="hidden" name="attribute_id" value="{{$attributeItems->attribute_id}}">
		         														<input type="hidden" name="attribute_name" value="{{$attributeItems->attribute?$attributeItems->attribute->name:''}}">
		         														<input type="hidden" name="attribute_item_id" value="{{$attributeItems->attribute_item_id}}">
		         														<input type="hidden" name="attribute_item_name" value="{{$attributeItems->attributeItem?$attributeItems->attributeItem->value:''}}">
		         														<input type="hidden" name="attribute_price" value="{{$attributeItems->attribute_price}}">
		         														
												                		<button type="button" id="sub" class="minus" onclick="minus_booking({{$attributeItems->id}})">-</button>
																	      <input type="number" id="1" value="1" min="1" class='quantity formqty{{$attributeItems->id}}' max="10" style="width:50px!important; padding:0!important"  />
																	      <button type="button" id="add" class="plus {{$addbtn}}" onclick="add_booking({{$attributeItems->id}})">+</button>
												                	</form>
												               </div>
												            </div>
												        </div>
											        </div>
										        @endif
									        	@endforeach

								      	</div>
								      	<div class="modal-footer">
								        	<button type="button" class="btn btn-dark modelclose" data-bs-dismiss="modal">OK</button>
								      	</div>
								    </div>
								</div>
							</div>

						@endif
				   @endforeach
			   @else
			   	
			   	<div class="maid_section">
			   		<?php
							$attribute_ids = [];
							$attribute = '';
							foreach (App\ServiceAttributeValueItem::where('service_id',$service->id)->get() as $prt => $value) {
								if($prt=='0'){
									array_push($attribute_ids, $value->id);
									$attribute = $value;
								}
								
							}
						?>
						<?php $attrvalue = App\ServiceAttributeValue::where('ser_attr_val_item_id',$attribute->id)->get(); ?>
						<form action="{{ url('sub/attribute') }}" method="POST" class="maidForm">
							@csrf
							<input type="hidden" name="service_id" value="{{$service->id}}">
							<input type="hidden" value="{{$service->category_id}}" class="category_id">   
							<input type="hidden" name="category_id" value="{{$attribute->category_id}}">
							<input type="hidden" name="ser_attr_item_id" value="{{$attribute->id}}">
							<input type="hidden" name="item_id" class="item_id">
							<div class="card mb-3">
							  	<div class="card-header">
							    	<?php
								  		if($attrvalue && count($attrvalue)){
								  			$attr_name = $attrvalue[0]->attribute->name;
								  		} else {
								  			$attr_name = '';
								  		}
								  	?>
								  	<strong>{{$attr_name}}</strong>								  	
							  	</div>
							  	<div class="card-body p-2">
								   @foreach(App\ServiceAttributeValue::whereIn('ser_attr_val_item_id',$attribute_ids)->get() as $key => $attributeItems)
						      		@if($attributeItems->attributeItem)

						      		<?php 
							      		$card_dt = App\Card::where('service_id',$service->id)->where('user_id',Auth::user()?Auth::user()->id:'')->where('payment_status','False')->where('work_done','No')->where('is_checkout','Processing')->orderBy('id', 'DESC')->first(); 
							      		$card_atr = App\CardAttribute::where('card_id',$card_dt?$card_dt->id:'')->where('attribute_id',$attributeItems->attribute_item_id)->orderBy('id', 'DESC')->first();
						      		?>
								        	<div class="card-text">
								            <div class="row align-items-center" >
								              	<div class="col-10">
									               <div class="card-body text-left mt-0 p-1" >
									                  <h6 class="card-title">{{$attributeItems->attributeItem->value}}</h6>
									                  <p class="m-0">Starting AED {{$attributeItems->attribute_price}}</p>
									               </div>
								              	</div>
									            <div class="col-2">
									               <div class="text-right " >
									               	<div class="form-check">
									               		@if($card_atr && isset($card_atr))								               			
															  		<input class="form-check-input" type="radio" name="item_id" value="{{$attributeItems->id}}" id="flexRadioDefault{{$attributeItems->id}}" onclick="submitForm()" checked>
															  		<label class="form-check-label" for="flexRadioDefault{{$attributeItems->id}}"></label>
															  	@else
															  		<input class="form-check-input" type="radio" name="item_id" value="{{$attributeItems->id}}" id="flexRadioDefault{{$attributeItems->id}}" onclick="submitForm()">
															  		<label class="form-check-label" for="flexRadioDefault{{$attributeItems->id}}"></label>
															  	@endif
															</div>
									               </div>
									            </div>
									        	</div>
								        	</div>
								        <hr class="m-1">
							        @endif
						        	@endforeach 
					        	</div>
					     	</div>
				        	<!-- <button class="btn btn-dark" onclick="submitForm()" type="button">Next</button> -->
			        	</form>

			   	</div>

			   @endif	   
			   
	      	</div>

   			@if(App\ServiceAttributeValueItem::where('service_id',$service->id)->count() && App\ServiceAttributeValueItem::where('service_id',$service->id)->with('sub_category')->first()->sub_category)
		       	<div class="col-sm-12 col-md-12 col-lg-5 col-xl-5 cardlist">
			        <div class="card mb-2">
			            <div class="row p-2">
				            <div class="col-md-6 text-left" >
				            	 <p class="m-0">Total Amount</p>
					                @if(App\Card::where('user_id',\Session::get('user_id'))->where('service_id',$service->id)->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->count()>0)
					                <?php $card_info = App\Card::where('user_id',\Session::get('user_id'))->where('service_id',$service->id)->where('is_checkout','Processing')->orderBy('id', 'DESC')->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->first(); ?>
					                <h5>AED {{$card_info?$card_info->g_total:''}}</h5>
					                <?php $cart_val = $card_info?$card_info->g_total:''; ?>
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
			             	<?php $setting = App\HomeSetting::first(); ?>
			            	<?php $card_info = App\Card::where('user_id',Auth::user()->id)->where('service_id',$service->id)->where('is_checkout','Processing')->orderBy('id', 'DESC')->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->first(); ?>
			            	@if($card_info && $card_info->g_total>0)
			            		@if($setting && $setting->min_cart_value<=$cart_val)
			            		<p class="p-2 m-0" data-bs-toggle="modal" href="#exampleModalToggle" role="button"><button class="urban_btn" type="button"> Proceed</button></p>

			            		@else
			            		<?php 
			            			$net_val = $setting->min_cart_value-$card->g_total;
			            		?>
			            		<p class="p-2 m-0" onclick="minCartAlert({{$net_val}})" role="button"><button class="urban_btn" type="button"> Proceed</button></p>
			            		<div class="text-center">
			            			<p class="minalert text-danger"></p>
			            		</div>
			            		@endif
			            	@else
			            		<p class="p-2  m-0" role="button"><button class="urban_btn" type="button" onclick="alertfun()"> Proceed</button></p>
			            	@endif
			            @else
			             	<p class="p-2  m-0"> <button type="button" onclick="showlogin()" class="urban_btn"> Proceed</button> </p>
			            @endif

			        </div>
		         
						<div class="cardmodal ">
	             		<?php $sub_total = '0'; $total = '0'; ?>
				            @if($card && count($card->card_attribute))
					            @foreach($card->card_attribute as $key => $card_atr)
					            <div class="card p-2 mb-2" >
					               <div class="row" >
						               <div class="col-6 text-left" >
						                  <h6>
						                  	<!-- {{$card_atr->attribute_item_name}} -->
						                  	{{$service->name}} -> 
						                  	@if($card_atr->main_sub_cat_id && isset($card_atr->main_sub_cat_id))
						                  		{{$card_atr->main_sub_cat?$card_atr->main_sub_cat->name:''}} -> 
						                  	@endif
						                  	@if($card_atr->child_cate_id && isset($card_atr->child_cate_id))
						                  		{{$card_atr->child_cate?$card_atr->child_cate->name:''}} -> 
						                  	@endif
						                  	{{$card_atr->attribute_item_name}}
						                  </h6>
						               </div>
						               <div class="col-6 text-right">
						                  <a class="#">{{$card_atr->attribute_qty}} x AED {{$card_atr->attribute_price}}</a>
						                  
						                 
						                  	<form action="{{ url('remove/card/attribute') }}" method="POST" class="removeForm{{$key}}">
														@csrf
														<!-- <input type="hidden" name="service_id" value="{{$service->id}}">
														<input type="hidden" name="card_id" value="{{$card->id}}">
														<input type="hidden" name="card_atr_id" value="{{$card_atr->id}}"> -->

														<input type="hidden" name="service_id" value="{{$service->id}}" class="removeFormServiceId{{$key}}">
					                           <input type="hidden" name="card_id" value="{{$card->id}}" class="removeFormCardId{{$key}}">
					                           <input type="hidden" name="card_atr_id" value="{{$card_atr->id}}" class="removeFormCardAtrId{{$key}}">

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
				            <div class="card p-2 mb-2 mt-2" >
				                <div class="row mt-10" >
				                    <div class="col-6 text-left" >
				                       	<h6>Subtotal</h6>
				                    </div>
				                    <div class="col-6 text-right" >
				                       <a class="#" >AED {{$sub_total}}</a>
				                    </div>
				                </div>
				         	</div> 
				         	<?php
					          $coupon = App\Coupon::where('id',$card?$card->coupon_id:'')->where('status','1')->first();
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
				           	<div class="card p-2 mb-2" >
				                <div class="row mt-10" >
				                    <div class="col-6 text-left" >
				                       	<h6>Addons</h6>
				                    </div>
				                    <div class="col-6 text-right" >
				                       <a class="#" >AED 00</a>
				                    </div>
				                </div>
				         	</div> 
				         	<div class="card p-2 mb-2" >
				               	<div class="row mt-10" >
				                   	<div class="col-6 text-left">
				                      	<h6>Total</h6>
				                   	</div>
				                   	<div class="col-6 text-right" >
				                      	<a class="#" >AED {{$total}}</a>
				                   	</div>
				               	</div>
				            </div> 

						</div>

		        	</div>
	        @else
		        <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5 cardlist">
			        <div class="card mb-2">
			            <div class="row p-2" >
				            <div class="col-md-6 text-left" >
				            	 <p class="m-0">Total Amount</p>
					                @if(App\Card::where('user_id',\Session::get('user_id'))->where('service_id',$service->id)->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->count()>0)
					                <?php $card_info = App\Card::where('user_id',\Session::get('user_id'))->where('service_id',$service->id)->where('is_checkout','Processing')->orderBy('id', 'DESC')->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->first(); ?>
					                <h5>AED {{$card_info?$card_info->g_total:''}}</h5>
					                <?php $cart_val = $card_info?$card_info->g_total:'0'; ?>
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
			            @if(Auth::check())
			            	<?php $card_info = App\Card::where('user_id',Auth::user()->id)->where('service_id',$service->id)->where('is_checkout','Processing')->orderBy('id', 'DESC')->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->first(); ?>
			            	@if($card_info && $card_info->g_total>0 && $card_info->card_attribute->first() && isset($card_info->card_attribute->first()->attribute_item_id))
			            		@if($setting && $setting->min_cart_value<=$cart_val)
				               <p class="p-2 m-0" data-bs-toggle="modal" href="#exampleModalToggle" role="button"><button class="urban_btn" type="button"> Proceed</button></p>
				               @else
				               <p class="p-2 m-0" onclick="minCartAlert({{$net_val}})" role="button"><button class="urban_btn" type="button"> Proceed</button></p>
				               <div class="text-center">
			            			<p class="minalert text-danger"></p>
			            		</div>
				               @endif
			            		
			            	@else
			            		<p class="p-2  m-0" role="button"><button class="urban_btn" type="button" onclick="alertfun()" > Proceed</button></p>
			            	@endif
			            @else
			             <p class="p-2  m-0"> <button type="button" onclick="showlogin()" class="urban_btn"> Proceed</button> </p>
			          
			            @endif

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
						               <div class="col-6 text-left">
						                  <h6></h6>
						               </div>
						               <div class="col-6 text-right">
						                  <a class="#">{{$card_atr->attribute_price*$card_atr->attribute_qty}}</a>
						                  
						                  	<form action="{{ url('remove/card/attribute') }}" method="POST" class="removeForm{{$key}}">
														@csrf
														<!-- <input type="hidden" name="service_id" value="{{$service->id}}">
														<input type="hidden" name="card_id" value="{{$card->id}}">
														<input type="hidden" name="card_atr_id" value="{{$card_atr->id}}"> -->

														<input type="hidden" name="service_id" value="{{$service->id}}" class="removeFormServiceId{{$key}}">
					                           <input type="hidden" name="card_id" value="{{$card->id}}" class="removeFormCardId{{$key}}">
					                           <input type="hidden" name="card_atr_id" value="{{$card_atr->id}}" class="removeFormCardAtrId{{$key}}">

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
				                       <a class="#" >AED {{$sub_total}}</a>
				                    </div>
				                </div>
				         	</div> 
				         	@if($card)
					        @if($card && $card->material_status=='Apply')
					        <div class="card p-2  mb-2" >
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
					        @if($card && $card->coupon_id)
					        <?php
					          $coupon = App\Coupon::where('id',$card?$card->coupon_id:'')->where('status','1')->first();
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
					       	<div class="card p-2  mb-2" >
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
				           	<div class="card p-2  mb-2" >
				                <div class="row" >
				                    <div class="col-6 text-left" >
				                       	<h6>Addons</h6>
				                    </div>
				                    <div class="col-6 text-right" >
				                       <a class="#" >AED 00</a>
				                    </div>
				                </div>
				         	</div> 
				         	
				         	<div class="card p-2  mb-2">
				               	<div class="row" >
				                   	<div class="col-6 text-left" >
				                      	<h6>Total</h6>
				                   	</div>
				                   	<div class="col-6 text-right">
				                      	<a class="#" >AED {{$total}}</a>
				                   	</div>
				               	</div>
				            </div> 

						</div>

		        </div>
	        @endif
        </div>
   	</div>
   	</div>

</section>  
      <!-- product details tab area end -->

    <!-- hero area end -->

@if($service->description && isset($service->description))  
<section class="pt-80  p-relative bg-white service_d_content" >
    <div class="container ">  
        {!! $service->description !!} 
    <div>
</section>

@else
<section class="pt-20  p-relative bg-white" >
    <div class="container ">  
         <div class="mb-20 text-left">
              <h4>Save Your Time, Money, and Stress With Urban Mop</h4>
               <p class="fs-15" >UrbanMop is a part of Urban Service LLC, driven by a vision to deliver the best home services across the UAE. We offer a wide range of affordable services across UAE, ensuring your Home, Villas, Offices, Shops, Restaurants, and more are maintained in an ideal condition.</p>

                    <p class="fs-15" >Our mission is to provide you with an unparalleled experience at budget-friendly prices. Through the UrbanMop platform, customers can effortlessly book professional experts for various services including deep cleaning, general cleaning, disinfection, pest control, handyman services, water tank cleaning, AC cleaning, laundry, painting, carpentry, and more for both residential and commercial properties.</p>

                    <p class="fs-15" >In this fast-paced era, finding reliable professionals for home maintenance can be challenging. That's where <a href="{{url('/')}}" class="text-info">UrbanMop.com</a> steps in, providing you with trusted professionals to efficiently complete your chores on time, without any hassle.
                    </p>
          </div>
            <div>
               <h4 class="mt-23">Why Choose Urban Mop Cleaning Services?</h4>
                <p>Just book Your slot in less than 1 minute and leave the rest of Us!</p>
            </div>
               
            <div class="row">
              <div class="col-1 mt-10">
                  <img src="{{ url('web/assets/img/home page images/trained.png') }}" alt="Trained" title="Trained" height="50px" width="50px">
              </div>
                 <div class="col-11 mt-10">
                    <!-- <h5>Trained professional</h5> -->
                    <p>Trained Professionals: Our skilled experts ensure high-quality service, using their expertise to meet your needs effectively.</p> 
                   </div> 
                </div>
            <div class="row">
             <div class="col-1 mt-10">
                <img src="{{ url('web/assets/img/home page images/quality.png') }}" alt="Quality" height="50px" width="50px">
              </div>
            <div class="col-11 mt-10">
                  <!-- <h5>Quality products</h5> -->
                  <p>Quality Products: We utilize premium products to deliver exceptional results, leaving your spaces spotless and fresh.</p> 
                </div> 
              </div>
             <div class="row">
            <div class="col-1 mt-10">
            <img src="{{ url('web/assets/img/home page images/secure.png') }}" alt="Secure" title="Secure" height="50px" width="50px">
            </div>
           <div class="col-11 mt-10">
              <!-- <h5>Secure payments</h5> -->
              <p>Secure Payments: Rest assured with our secure payment options, making your transaction experience hassle-free and safe.</p> 
             </div> 
           </div>
  
         <div class="row">
         <div class="col-1 mt-10">
            <img src="{{ url('web/assets/img/home page images/customer.png') }}" alt="Customer" title="Customer" height="50px" width="50px">
              </div>
           <div class="col-11 mt-10">
          <!-- <h5>Customer support</h5> -->
          <p>Customer Support: Our dedicated customer support team is ready to assist you with any queries or concerns you may have.</p> 
         </div> 
         </div>
         <div>
</section>

<section class="pb-80 p-relative bg-white" >
 <div class="container">
  <div class="row text-left" >
    <h5 class="mb-20 mt-5">Our House Cleaners:</h5>
    <div class="col-md-12">
     <div class="onstore_section">
       <div class="row">
         <div class="col-lg-6">
            <ul class="fs-15">
                <li>On-time and Professional</li>
                <li>Super Friendly</li>
                <li>Thorough and Efficient Cleaners</li>
                <li>Excellent Communication Skills</li>
                <li>Completely Dedicated</li>
            </ul>
          </div>  
          <div class="col-lg-6">
            <div class="mobile-app-girl"><img src="{{ url('web/assets/img/about/home-3/cleangirl.png') }}"></div>
          </div>  
        </div>
    </div>
    </div>
    <div class="col-md-12">
    <!-- <h5 class="mt-15">What Urban Mop Trained Experts can do:</h5> -->
    <ul class="ml-20 mt-20 fs-15">
        <li>Experience the convenience and excellence of Urban Mop. Book your slot in less than 1 minute and leave the rest to us!</li>
    </ul>
    </div>
    </div>
              
    </div>
</section>
@endif

	<form action="{{ route('update.profile') }}" method="POST" class="profile_address_form" enctype="multipart/form-data">
	@csrf
		
	<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered ">
	    <div class="modal-content" id="personal_information">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalToggleLabel">Personal Information</h5>
	        
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body padding-on-desk">
	        <p>We just need a bit of information about you. Don't worry. This will only take a minute and will help us personalize your experience.</p>
	        <input type="hidden" name="service_id" value="{{$service->id}}">
	        <div class="text-center">
	        	@if(Auth::user() && Auth::user()->profile)
	              <img src="{{ url('uploads/user/'.Auth::user()->profile) }}" alt="{{Auth::user()->name}} Profile" title="{{Auth::user()->name}} Profile" class="w-100px h-100px">
	            @else
	              <img src="{{ url('web/assets/img/user.png') }}" width="40" alt="{{Auth::user()?Auth::user()->name:''}}" title="{{Auth::user()?Auth::user()->name:''}} Profile">
	            @endif
	        </div>
	       
       		<div class="mt-10">
          		<label for="exampleInputEmail1">Name</label>
          		<br/>
         		<input type="text" name="name" value="{{Auth::user()?Auth::user()->name:''}}" placeholder="john Doe" class="form-control" required>
       		</div>

       		<div class="mt-10">
          		<label for="exampleInputEmail1">Email</label>
          		<br/>
         		<input type="email" name="email" placeholder="Email" value="{{Auth::user()?Auth::user()->email:''}}" class="form-control">
       		</div>

       		<div class="mt-10">
          		<label for="exampleInputEmail1">Date of Birth</label>
          		<br/>
         		<input type="date" name="date" placeholder="" value="{{Auth::user()?Auth::user()->DOB:''}}"  class="form-control">
       		</div>

        		<div class="mt-10">
               <label for="exampleInputEmail1">Gender</label><br/>
               <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" name="gender" {{Auth::user()?Auth::user()->gender=='Male'?"checked":'':''}} value="Male">
               <label class="form-check-label" for="inlineRadio1">Male</label>
               <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" name="gender" {{Auth::user()?Auth::user()->gender=='Female'?"checked":'':''}} value="Female">
               <label class="form-check-label" for="inlineRadio2">Female</label>
             </div>
	      </div>
	      <div style="text-align: center;" class=" p-2 ">
	        <button type="button" class="btn btn-dark  text-orange m-w-50"  data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Next</button>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" >
	   <div class="modal-dialog">
	    	<div class="modal-content">
		      <div class="modal-header">
		        	<h5 class="modal-title" id="staticBackdropLabel">Address Info</h5>
		        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
	      	<div class="modal-body">
		       	<p>We're almost there! To ensure a seamless cleaning experience ,please add your address so we can get to you with ease.</p>
		       	<div class="container-address">
		            <div class="col-lg-12 mt-10">
		            	<label for="exampleInputEmail1">Current Location</label>
							  <br/>

							<div class="input-group mb-3">
								<input type="hidden" name="address_id" value="{{$address?$address->id:''}}">
							  <input type="text" class="form-control live_address" list="browsers" placeholder="Search Current Location" onkeypress="getLiveAddres(this.value)" value="{{$address?$address->address:''}}" name="address" required>

							  <datalist id="browsers">
										  
								</datalist>
							  <div class="input-group-append">
							    <button class="btn btn-warning getLetLong" type="button" onclick="getLetLong()">Search Location</button>
							  </div>
							  
							</div>
							<small class="text-danger addresserror"></small>
		            </div>
		            @include('web.live_location')
		         	<br>
		         	<br>
		         	@include('web.map')
		           	<input type="hidden" value="False" class="checkAddress">
		         	<div class="row">
			            <div class="col-lg-12 mt-10">
			               <label for="exampleInputEmail1">Address Type</label><br/>
			               <input class="" type="radio" name="address_type" id="inlineRadio1" value="Home" {{$address?$address->address_type=='Home'?'checked':'checked':'checked'}}>
			               <label class="" for="inlineRadio1">Home</label>
			               <input class="" type="radio" name="address_type" id="inlineRadio2" value="Office" {{$address?$address->address_type=='Office'?'checked':'':''}}>
			               <label class="" for="inlineRadio2">Office</label>
			               <input class="" type="radio" name="address_type" id="inlineRadio3" value="Work" {{$address?$address->address_type=='Work'?'checked':'':''}}>
			               <label class="" for="inlineRadio3">Work</label>
			            </div>
		         	</div>

		         	<div class="row">
		         		<div class="col-lg-6 mt-10">
		            		<label for="exampleInputEmail1">Flat/Office No.</label>
		            		<br/>
		           			<input type="location" name="flat_no" value="{{$address?$address->flat_no:''}}" placeholder="Flat/Office No." class="form-control flat_no" required>
		         			<small class="text-danger flaterror"></small>
		         		</div>

		            	<div class="col-lg-6  mt-10">
		               		<label>Building Name</label>
		               		<br/>
		              		<input type="location" name="building" value="{{$address?$address->building:''}}" placeholder="Building Name" class="form-control building" required>
		            		<small class="text-danger buildingerror"></small>
		            	</div>

		            	<div class="col-sm-6 mt-10">
		            		<label>City</label>
	               		<br/>
	               		<select class="form-control select2 city_val" name="city_id" onchange="getLocality(this.value)" required>
	               			<option value="">Select City</option>
	               			@foreach($city as $cty)
	               			<option value="{{$cty->id}}">{{$cty->name}}</option>
	               			@endforeach
	               		</select>
	               		<small class="text-danger cityerror"></small>
		            	</div>
		   
		            	<div class="col-sm-6 mt-10">
		               		<label>Locality</label>
		               		<br/>
		               		<select class="form-control select2 localitylist" name="locality">
		               			<option value="">Select Locality</option>	               			
		               		</select>	              		
		            	</div>
		            </div>
		      	</div>
		      	<div style="text-align: center;" class="mb-2 mt-4">
			        <!-- <button type="submit" class="btn btn-dark mb-15 ml-50 mr-50 mt-10 text-orange m-w-50" >Submit</button> -->
			        <button type="button" onclick="checkSubmitForm()" class="btn btn-dark mb-15 ml-50 mr-50 mt-10 text-orange m-w-50" >Submit</button>
			      </div>
		      </div>
	    	</div>
	  	</div>
	</div>
	</form>
	<!-- <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Open first modal</a> -->


	  <!-- Modal -->
		<div class="modal fade" id="attributeModalSec" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
		    	<div class="modal-content">
				   <div class="modal-header">
				      <h5 class="modal-title subCateName" id="exampleModalLabel"></h5>
				      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				   </div>
			      <div class="modal-body attributelist">
				      <div class="card mb-2">
				            
				      </div>
			     	</div>
			     	<div class="modal-footer">
			        	<button type="button" class="btn btn-dark modelclose" data-bs-dismiss="modal">OK</button>
			      </div>
			   </div>
			</div>
		</div>

   <input type="hidden" value="{{$service->id}}" class="service_id">     
     <input type="hidden" value="{{$card?$card->id:''}}" class="card_id">
  
@endsection

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script>
	function OpenAttrModel(child_id, subid, attrid) {
		
		var attritemmod 		= '#attributeModalSec';
		var sub_cat_name_cls = '.sub_cate_name'+subid;
		var attrmod 			= 'exampleModal'+attrid;
		var sub_cat_name 		= jQuery(sub_cat_name_cls).val();
		var service_id 		= jQuery('.service_id').val();
		jQuery('.subCateName').text(sub_cat_name);
		jQuery('.attributelist').html('')
		jQuery('.modelclose').click();
		if(child_id){
			jQuery.ajax({
              
               headers: {
	                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	            },
	            type:"POST",
               url: "{{ url('get_child_cat_attr_items') }}",
               data: {
                        child_category_id: child_id, service_id : service_id, sub_cat_id : subid,
                    },
               success: function (res) {
                  console.log(res);

						jQuery('#attrclose').click();
                  jQuery("#attributeModalSec").modal('show');
                  
                  jQuery.each(res, function(key,val) {
			        		var formid =  'addattrformss'+val.id; 
			        		var buttonForm =  'buttonFormss'+val.id;
			        		var add_booking_from = 'add_booking_from'+val.id;
			        		var formqty = 'formqty'+val.id;
			        		var addbtn = 'addbtn'+val.id;
			            jQuery('.attributelist').append('<div class="card mb-2"><div class="row align-items-center"> <div class="col-md-6"> <div class="card-body text-left mt-0" > <h6 class="card-title">'+ val.attributename +'</h6> <p><small>Starting AED '+ val.attribute_price +'</small></p> </div></div><div class="col-md-6"><div class="card-body text-right " ><button class="add-button-color-change" onclick="showForm('+formid+', '+buttonForm+', '+val.id+')" id="'+buttonForm+'">+ Add</button>	<form action="{{ route("add.attribute.card") }}" method="POST" style="display: none;" enctype="multipart/form-data" id="'+formid+'" class="'+add_booking_from+'">@csrf<input type="hidden" name="service_id" value="' + val.service_id + '"><input type="hidden" name="category_id" value="'+ val.category_id +'"><input type="hidden" name="sub_cate_id" value="'+ val.sub_cat_id +'"><input type="hidden" name="main_sub_cat_id" value="'+ val.main_sub_cat_id +'"><input type="hidden" name="child_category_id" value="'+ child_id +'"><input type="hidden" name="attribute_id" value="'+ val.attribute_id +'"><input type="hidden" name="attribute_name" value="'+ val.attribute_name +'"><input type="hidden" name="attribute_item_id" value="'+ val.attribute_item_id +'"><input type="hidden" name="attribute_item_name" value="'+ val.attribute_item_name +'"><input type="hidden" name="attribute_price" value="'+ val.attribute_price +'"><button type="button" id="sub" class="minus" onclick="minus_booking('+val.id+')">-</button><input type="number" id="1" value="1" min="1" class="quantity '+formqty+'" max="10" /><button type="button" onclick="add_booking('+val.id+')" id="add" class="plus '+addbtn+'">+</button></form></div></div></div></div>');
			        });
                  
                  
               },
               error: function (res) {
                   console.log(res);
               }
         });
		}
		
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
<script>
	jQuery('.v-c-d').hide();
	jQuery(".view-o-d").click(function(){
	   jQuery(".v-c-d").fadeToggle();
	});
</script>
<style type="text/css">
   .minus, .plus{
			width:34px!important;
			height:36px!important;
			background-image: linear-gradient(#feb83a, #d84e56)!important;
			border-radius:4px!important;
			padding:8px 5px 8px 5px!important;
			border:1px solid #ddd; color:#fff!important;
		display: inline-block!important;
		vertical-align: middle!important;
		text-align: center!important; margin:0!important;
	}

	.quantity {
		height:37px!important;
		width: 100px!important; margin:0!important;
		text-align: center!important;
		font-size: 14px!important; color:#fff!important;
		border:1px solid #ddd!important;
		border-radius:4px; 	background-image: linear-gradient(#feb83a, #d84e56)!important;
		display: inline-block!important!important;
		vertical-align: middle!important;
	}

</style>
<script type="text/javascript">
               var input = $('.quantity'),
         minValue =  parseInt(input.attr('min')),
         maxValue =  parseInt(input.attr('max'));


         $('.plus').on('click', function () {
         var inputValue = input.val();
         if (inputValue < maxValue) {
         input.val(parseInt(inputValue) + 1);
         }
         });

         $('.minus').on('click', function () {
         var inputValue = input.val();
         if (inputValue < maxValue) {
         input.val(parseInt(inputValue) - 1);
         }
         });

</script>
<script>
	function submitForm(argument) {
		jQuery('.textgtotal').hide();
		jQuery.ajax({
            headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },    
            type: 'Post',
            url: "{{ url('sub/attribute') }}",
            data: jQuery('.maidForm').serialize(),
            dataType: 'json',
            success: function (data) {
            	console.log(data);
                 jQuery('.maid_section').html('');       
                 jQuery('.maid_section').html(data.modal_view);             
            },
            error: function (data) {
                console.log(data);
            }
      });
	}
</script>
<script>
	let lasthur = '';
	function getSubItemid(datas) {
		var hurid = '#flexRadioDefault'+datas.id;
		jQuery(lasthur).prop('checked', false);
		lasthur = hurid;
		jQuery('.attribute_detail_id').val(datas.id);
		submitSubAttrForm();
	}
</script>
<script>
	let material = '';
	function submitSubAttrForm() {
		jQuery.ajax({
            headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },    
            type: 'Post',
            url: "{{ url('update/sub/attribute') }}",
            data: jQuery('.submitSubAttrForm').serialize(),
            dataType: 'json',
            success: function (data) {
            	console.log(data);
                 jQuery('.cardlist').html('');       
                 jQuery('.cardlist').html(data.modal_view); 
                 jQuery('.textgtotal').show();    
                 jQuery('.gtotal').text(data.peramt);  
                  material = data.peramt;
                  jQuery("#flexRadioDefault1").prop('checked', false);
                  jQuery("#flexRadioDefault2").prop('checked', false);
            },
            error: function (data) {
                console.log(data);
            }
      });
	}
</script>
<script>

	function cleaningCheck(argument) {
		if(argument=='Yes'){
			var status = 'Apply';
		} else {
			var status = 'Not';
		}

		jQuery.ajax({
            headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },    
            type: 'Post',
            url: "{{ url('update/material/charge') }}",
            data: {
            	card_id : jQuery('.cc_card_id').val(),
            	material_amt : material,
            	status : status,
            },
            dataType: 'json',
            success: function (data) {
            	console.log(data);
            	  jQuery('.cardlist').html('');       
                 jQuery('.cardlist').html(data.modal_view);    
                 jQuery('.textgtotal').show();    
                 jQuery('.gtotal').text(data.peramt);  
                  material = data.peramt; 
            },
            error: function (data) {
                console.log(data);
            }
      });
	}
</script>
<script>
	function reloadPage() {
		location.reload();
	}
</script>

<style type="text/css">
	
	#attributeModalSec .card-body p {
    	margin: 0em 0 !important;
    }
	#attributeModalSec .card-body{
			padding: 5px 15px 2px 15px !important;
		}

	#attributeModalSec .card-title {
    margin-bottom: 0px !important;
	}


@media only screen and (max-width: 768px) and (min-width: 200px)  {

	#personal_information input[type="date"]{
		height: 70px !important;
    	line-height: 20px !important;
	}

		#personal_information input[type="date"]{
		padding: 0rem 0.75rem !important;
	}

	}	

</style>

<script>
	function checkSubmitForm() {
		var checkAddress = jQuery('.checkAddress').val();
		if(checkAddress=='True'){
			jQuery('.profile_address_form').submit();
		} else {
			if(jQuery('.live_address').val()==''){
				jQuery('.addresserror').text('This field is required.');
			} else if(jQuery('.flat_no').val()==''){
				jQuery('.addresserror').text('');
				jQuery('.flaterror').text('This field is required.');
			} else if(jQuery('.building').val()==''){
				jQuery('.addresserror').text('');
				jQuery('.flaterror').text('');
				jQuery('.buildingerror').text('This field is required.');
			} else if(jQuery('.city_val').val()==''){
				jQuery('.addresserror').text('');
				jQuery('.flaterror').text('');
				jQuery('.buildingerror').text('');
				jQuery('.cityerror').text('This field is required.');
			} else {
				jQuery.ajax({
			         headers: {
			                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			            },    
			         type: 'Post',
			         url: "{{ url('get/lat/long') }}",
			         data: {
			           location : jQuery('.live_address').val(),
			         },
			         dataType: 'json',
			         success: function (response) {
			              
			              if(response.status=='1'){
			                 jQuery('#latitude').val(response.latitude);
			                 jQuery('#longitude').val(response.longitude);
			                 map = new google.maps.Map(document.getElementById('map'), {
			                    center: { lat: response.latitude, lng: response.longitude }, // Default to centering at (0, 0)
			                    zoom: 15 // Adjust the zoom level as desired
			                 });

			                 // Create a marker at the default location (0, 0)
			                 marker = new google.maps.Marker({
			                    position: { lat: response.latitude, lng: response.longitude },
			                    map: map,
			                    draggable: true // Allow the marker to be dragged
			                 });

			                 // Add an event listener to update the latitude and longitude when the marker is dragged
			                 marker.addListener('dragend', function() {
			                    updateCoordinates(marker.getPosition());
			                 });
			               } 
			               jQuery('.checkAddress').val('True');
								jQuery('.profile_address_form').submit();   
			         },
			         error: function (response) {
			            jQuery('.checkAddress').val('True');
							jQuery('.profile_address_form').submit();
			         }
			      });
				
			}
			
			// jQuery('.profile_address_form').submit();
		}
	}
</script>


<style type="text/css">
	
	#sub-desc-cat{
		font-size: 13px !important;
	}


	.add-button-color-change{
	background-color: #eab42f !important;
    color: #fff !important;
    padding: 0px 20px !important;
    font-size: 17px !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
    -webkit-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75) !important;
    -moz-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75) !important;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75) !important;
}

	.attributelist .add-button-color-change, .btn-sm{
	background-color: #eab42f !important;
    color: #fff !important;
    padding: 0px 20px !important;
    font-size: 17px !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
    -webkit-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75) !important;
    -moz-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75) !important;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75) !important;
}

.card-title{
	font-size: 16px !important;
	color:#6a6d7a !important;
}

.service_d_content h1, .service_d_content h2, .service_d_content h3, .service_d_content h4, .service_d_content h5, .service_d_content h6{
	color:#6a6d7a !important;
}

 .cardlist h1, .cardlist h2, .cardlist h3, .cardlist h4, .cardlist h5, .cardlist h6{
 	color:#6a6d7a !important;	
 }


@media only screen and (max-width: 720px) and (min-width: 200px)  {
	.sub-category-popup .col-8, .sub-category-popup .col-4{
		width: 50% !important;
	}
	
}



</style>
