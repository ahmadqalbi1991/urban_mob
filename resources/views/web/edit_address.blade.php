@extends('web.layout.header')
@section('title','My Address')

@section('content')

<main>
   
    <section class="pt-50 p-relative">           
               
        <div class="card registration-form2">
        	<div class="card">
				<div class="card-header">
				    <strong>Edit Address</strong>
				</div>
				<form action="{{ url('update/address') }}" method="POST" enctype="multipart/form-data">
         		@csrf
					<input type="hidden" name="id" value="{{$address->id}}">
					      <div class="card-body">
					        <div class="col-lg-12 mt-10">
			            		<label for="exampleInputEmail1">Current Location</label>
								  <br/>
		                	
								<div class="input-group mb-3">
									
								  <input type="text" class="form-control live_address" list="browsers" value="{{$address->address}}" placeholder="Search Current Location" onkeypress="getLiveAddres(this.value)" name="address" required>
								  <datalist id="browsers">
											  
									</datalist>
								  <div class="input-group-append">
								    <button class="btn btn-warning" type="button" onclick="getLetLong()">My Location</button>
								  </div>
								  
								</div>

			            	</div>
						  	<br>
						  	<?php 
						  	$map['latitude'] = $address->latitude;
						  	$map['longitude'] = $address->longitude;
						  	 ?>
						  	@include('web.map',$map)
						  
						  	<div class="form-group mt-4">
						        <label for="exampleInputEmail1">Address Type</label>
						        <div>
					               <input class="" type="radio" id="inlineRadio1" name="address_type" value="Home" {{$address->address_type=='Home'?'checked':''}}>
					               <label class="" for="inlineRadio1">Home</label>
					               <input class="" type="radio" id="inlineRadio2" name="address_type" value="Office" {{$address->address_type=='Office'?'checked':''}}>
					               <label class="" for="inlineRadio2">Office</label>
					               <input class="" type="radio" id="inlineRadio3" name="address_type" value="Work" {{$address->address_type=='Work'?'checked':''}}>
					               <label class="" for="inlineRadio3">Work</label>
				               	</div>
						  	</div>
						  	<div class="form-group mt-4">
						        <label for="exampleInputEmail1">Flot/Office No.</label>
						        <input type="text" placeholder="Enter Flot/Office No." value="{{$address->flat_no}}" name="flat_no" class="form-control">
						  	</div>
						  	<div class="form-group mt-4">
						        <label for="exampleInputEmail1">Building Name</label>
						        <input type="text" placeholder="Building Name" name="building" value="{{$address->building}}" class="form-control">
						  	</div>
						  	<div class="row">
						  		<div class="col-lg-6">
						  			<div class="form-group mt-4 mb-4">
								        <label for="exampleInputEmail1">City</label>
								        <select class="form-control select2" name="city_id" onchange="getLocality(this.value)">
								        	<option value="">Select City</option>
								        	@foreach($city as $cty)
								        	<option value="{{$cty->id}}" {{$address->city_id==$cty->id?'selected':''}}>{{$cty->name}}</option>
								        	@endforeach
								        </select>
								  	</div>
						  		</div>
						  		<div class="col-lg-6">
						  			<div class="form-group mt-4">
					        			<label for="exampleInputEmail1">Locality</label>
								        <select class="form-control select2 localitylist" name="locality">
								        	<option value="">Select Locality</option>
								        	@foreach(App\Locality::where('city_id',$address->city_id)->get() as $loca)
								        	<option value="{{$loca->id}}" {{$address->locality==$loca->id?'selected':''}}>{{$loca->name}}</option>
								        	@endforeach
								        </select>
						  			</div>
						  		</div>
						  	</div>
					      </div>
					      <div class="text-center">
					        <button type="submit" class="btn btn-dark">Update</button>
					      </div><br>
			  	</form>
			</div>
		</div>
	</section>
</main>

@endsection
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvGq8LjejiKHaI5lPEUZVYbwOYSwhZMEs&libraries=places"></script>
<!-- <script src="script.js"></script> -->

@section('script')

	<script>
		function get_location(req) {
			jQuery.ajax({
	            headers: {
	                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	               },    
	            type: 'Post',
	            url: "{{ url('get/location') }}",
	            data: {
	            	req : req,
	            },
	            dataType: 'json',
	            success: function (data) {
	            	console.log(data);
	                          
	            },
	            error: function (data) {
	                console.log(data);
	            }
	      });
		}
	</script>
	
@endsection
