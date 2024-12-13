@extends('web.layout.header')
@section('title','My Address')

@section('content')

<main>
   
    <section class="pt-50 p-relative">           
               
        <div class="card registration-form2">
        	<div class="card">
				<div class="card-header">
				    <strong>Home</strong>
				</div>
				<div class="card-body">
					@foreach($address->where('address_type','Home') as $key => $add)
				    	<div class="row">
				    		<div class="col-lg-8">
		            			<p> {{$add->flat_no}} {{$add->building}} {{$add->locality}} {{$add->address}} {{$add->city?$add->city->name:''}} </p>
				    		</div>
				    		<div class="col-lg-4">
				    			<div class="form-check">
				           			<button type="button" class="text-warning"><a href="{{url('update/address/'.$add->id)}}">Edit</a></button>
				           			<button type="button" class="text-danger" onclick="return confirm('Are you sure you want to delete this address?');"><a href="{{url('remove/address/'.$add->id)}}">Delete</a></button>
								</div>
				    		</div>
				    	</div>

						<hr>
				    @endforeach
				</div>
			</div>

			<div class="card mt-2">
				<div class="card-header">
				    <strong>Office</strong>
				</div>
				<div class="card-body">
					@foreach($address->where('address_type','Office') as $key => $add)
				    	<div class="row">
				    		<div class="col-lg-8">
		            			<p> {{$add->flat_no}} {{$add->building}} {{$add->locality}} {{$add->address}} {{$add->city?$add->city->name:''}} </p>
				    		</div>
				    		<div class="col-lg-4">
				    			<div class="form-check">
				           			<button type="button" class="text-warning"><a href="{{url('update/address/'.$add->id)}}">Edit</a></button>
				           			<button type="button" class="text-danger" onclick="return confirm('Are you sure you want to delete this address?');"><a href="{{url('remove/address/'.$add->id)}}">Delete</a></button>
								</div>
				    		</div>
				    	</div>
						<hr>
				    @endforeach
				</div>
			</div>

			<div class="card mt-2">
				<div class="card-header">
				    <strong>Work</strong>
				</div>
				<div class="card-body">
					@foreach($address->where('address_type','Work') as $key => $add)
				    	<div class="row">
				    		<div class="col-lg-8">
		            			<p> {{$add->flat_no}} {{$add->building}} {{$add->locality}} {{$add->address}} {{$add->city?$add->city->name:''}} </p>
				    		</div>
				    		<div class="col-lg-4">
				    			<div class="form-check">
				           			<button type="button" class="text-warning"><a href="{{url('update/address/'.$add->id)}}">Edit</a></button>
				           			<button type="button" class="text-danger" onclick="return confirm('Are you sure you want to delete this address?');"><a href="{{url('remove/address/'.$add->id)}}">Delete</a></button>
								</div>
				    		</div>
				    	</div>
						<hr>
				    @endforeach
				</div>
			</div>
        	
            <div class="text-center mt-4 mb-3" >
             	<button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"class="urban_btn" >Add New Location</button>
            </div>  
        </div>
    </section> 
</main>

   <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Add Address</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      	<form action="{{ url('store/address') }}" method="POST" enctype="multipart/form-data">
					@csrf
			      <div class="modal-body">
			        <div class="col-lg-12 mt-10">
	            		<label for="exampleInputEmail1">Current Location</label>
						  <br/>
                	
						<div class="input-group mb-3">
							
						  <input type="text" class="form-control live_address" list="browsers" placeholder="Search Current Location" onkeypress="getLiveAddres(this.value)" name="address" required>
						  <datalist id="browsers">
									  
							</datalist>
						  <div class="input-group-append">
						    <button class="btn btn-warning" type="button" onclick="getLetLong()">My Location</button>
						  </div>
						  
						</div>

	            	</div>
				  	<br>
				  	<!-- <div id="map-container-google-1" class="z-depth-1-half map-container">
				        <iframe src="https://maps.google.com/maps?q=manhatan&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0"
				         style="border:0; min-height: 100px;" allowfullscreen></iframe>
			        </div> -->
			        @include('web.map')
			        
				  	<div class="form-group mt-4">
				        <label for="exampleInputEmail1">Address Type</label>
				        <div>
			               <input class="" type="radio" id="inlineRadio1" name="address_type" value="Home" checked>
			               <label class="" for="inlineRadio1">Home</label>
			               <input class="" type="radio" id="inlineRadio2" name="address_type" value="Office">
			               <label class="" for="inlineRadio2">Office</label>
			               <input class="" type="radio" id="inlineRadio3" name="address_type" value="Work">
			               <label class="" for="inlineRadio3">Work</label>
		               	</div>
				  	</div>
				  	<div class="form-group mt-4">
				        <label for="exampleInputEmail1">Flat/Office No.</label>
				        <input type="text" placeholder="Enter Flot/Office No." name="flat_no" class="form-control" required>
				  	</div>
				  	<div class="form-group mt-4">
				        <label for="exampleInputEmail1">Building Name</label>
				        <input type="text" placeholder="Building Name" name="building" class="form-control" required>
				  	</div>
				  	<div class="row">
				  		<div class="col-lg-6">
				  			<div class="form-group mt-4 mb-4">
						        <label for="exampleInputEmail1">City</label>
						        <select class="form-control" name="city_id" onchange="getLocality(this.value)" required>
						        	<option value="">Select City</option>
						        	@foreach($city as $cty)
						        	<option value="{{$cty->id}}">{{$cty->name}}</option>
						        	@endforeach
						        </select>
						  	</div>
				  		</div>
				  		<div class="col-lg-6">
				  			<div class="form-group mt-4">
				  				<label for="exampleInputEmail1">Locality</label>
			        			<select class="localitylist" name="locality">
			        				
			        			</select>
				  			</div>
				  		</div>
				  	</div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-dark">Store</button>
			      </div>
			  	</form>

		    </div>
		  </div>
		</div>

@endsection

