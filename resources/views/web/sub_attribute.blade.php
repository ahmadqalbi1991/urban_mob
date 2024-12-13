	<?php $attrvalue = App\ServiceAttributeValue::where('ser_attr_val_item_id',$serviceItem->id)->get(); ?>
	<form action="{{ url('sub/attribute') }}" class="submitSubAttrForm" method="POST">
		@csrf
		<input type="hidden" name="service_id" class="service_id" value="{{$service->id}}">
		<input type="hidden" name="attribute_id" value="{{$serviceItem->id}}">
		<input type="hidden" name="card_attribute_id" value="{{$card_attribute_id}}">
		<input type="hidden" name="card_id" class="card_id" value="{{$card_id}}">
		<input type="hidden" name="attribute_detail_id" class="attribute_detail_id">
		<!-- <input type="hidden" name="card_attribute_id" class="card_attribute_id"> -->
  	</form>
		<div class="card">
		  	<div class="card-header">
		    	<?php
			  		if($attrvalue && count($attrvalue)){
			  			$attr_name = $attrvalue[0]->attribute->name;
			  		} else {
			  			$attr_name = '';
			  		}
			  	?>
			  	<strong>{{$attr_name}}</strong>
			  	<a href="{{ url('service/details/'.$service->slug.'/no') }}">
			  	<button class="btn btn-dark" style="padding: 1%; float: right;" type="button" title="Go to preview page"><i class='fa fa-arrow-left'></i></button>
			  	</a>
		  	</div>
		  	<div class="card-body p-2">
			   @foreach(App\ServiceAttributeValue::where('ser_attr_val_item_id',$serviceItem->id)->get() as $key => $attributeItems)
					@if($attributeItems->attributeItem)
			        	<div class="card-text mb-0">
			            <div class="row align-items-center">
			              	<div class="col-10">
				               <div class="card-body p-1 text-left" >
				                  <h6 class="card-title">{{$attributeItems->attributeItem->value}}</h6>
				                  <p class="m-0"><small>Starting {{$attributeItems->attribute_price}}</small></p>
				               </div>
			              	</div>
				            <div class="col-2">
				               <div class="text-right" >
				               	<div class="form-check">
										  	<input class="form-check-input" type="radio" id="flexRadioDefault{{$attributeItems->id}}" onclick="getSubItemid({{$attributeItems}})">
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

  	<!-- <button class="btn btn-dark" onclick="submitSubAttrForm()" type="button">Finish</button> -->
  	@if($service->material_status=='True')
  	<?php $card_in = App\Card::where('id',$card_id)->first(); ?>
  	<input type="hidden" class="cc_card_id" value="{{$card_in?$card_in->id:''}}">
     	<div class="card mb-2 mt-2" id="Materialscharge" style="background: #d3d3d385;">
         <div class="row">
           	<div class="col-md-12">
                <div class="card-body text-left mt-5">
                  <h6 class="card-title">Would you like Us To Bring a Cleaning Materials??</h6>
                  <div class="form-check form-check-inline">
										  <input class="form-check-input" type="radio" onclick="cleaningCheck('Yes')" {{$card_in->material_status=='Apply'?'checked':''}} name="flexRadioDefault" id="flexRadioDefault1">
										  <label class="form-check-label" for="flexRadioDefault1" onclick="cleaningCheck('Yes')">
										    Yes
										  </label>

									</div>
									<div class="form-check form-check-inline">
										  <input class="form-check-input" type="radio" name="flexRadioDefault" onclick="cleaningCheck('No')" id="flexRadioDefault2">
										  <label class="form-check-label" for="flexRadioDefault2" onclick="cleaningCheck('No')">
										    No
										  </label>
									</div>
									<p class="textgtotal"> Materials Charge AED <span class="gtotal"></span></p>
                  <p>{{$service->recommended}}</p>
                </div>
           	</div>
        </div>
     	</div>
  	@endif
