@extends('web.layout.header')
@section('title','My Profile')
@section('content')

<main>
    <section class="pt-50  p-relative">
        <div class="container">
            <div class="card">
	            <div style="p-1" class="text-center">
	   
	               	<h5 class="mt-40">Personal Information</h5>
	            	<p class="mt-10">We just need a bit of information about you. Don't worry. This will only take a minute and will help us personalize your experience.</p>
	   				<form action="{{ route('update.profile') }}" method="POST" enctype="multipart/form-data">
         			@csrf
         				<input type="hidden" name="data_from" value="my_profile">
		            	<div class="text-center" >
		             		@if(Auth::user()->profile)
		                      <img src="{{ url('uploads/user/'.Auth::user()->profile) }}" alt="{{Auth::user()->name}}" class="w-60px h-60px" >
		                    @else
		                      <img src="{{ url('web/assets/img/home page images/profile picture.jpg') }}" alt="{{Auth::user()->name}}">
		                    @endif
		             	</div>
					   <div class="row d-flex justify-content-center">
						<div class="col-md-4">
		               	<div  class="text-left">
		               		<div class="mt-10" >
		                   		<label for="exampleInputEmail1">Profile</label>
		                   		<br/>
		                  		<input type="file" name="profile" class="form-control">
		                	</div>

		                	<div class="mt-10" >
		                   		<label for="exampleInputEmail1">Name</label>
		                   		<br/>
		                  		<input  type="text" name="name" value="{{$user->name}}" placeholder="john Doe" class="form-control" required>
		                	</div>

		                	<div class="mt-10" >
		                   		<label for="exampleInputEmail1">Phone</label>
		                   		<br/>
		                  		<input  type="text" value="{{$user->phone}}" placeholder="520000000" class="form-control" readonly>
		                	</div>
		   
		                	<div class="mt-10">
		                   		<label for="exampleInputEmail1">Email</label>
		                   		<br/>
		                  		<input type="email" name="email" value="{{$user->email}}" placeholder="john-doe@Gmail.Com" class="form-control" required>
		                	</div>
		   
		                	<div class="mt-10">
		                   		<label for="exampleInputEmail1">Date of Birth</label>
		                   		<br/>
		                  		<input type="date" name="date" placeholder="" value="{{$user->DOB}}" class="form-control">
		                	</div>
		   
			                <div class="mt-10">
			                  <label for="exampleInputEmail1">Gender</label><br/>
			                  <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" name="gender" {{Auth::user()?Auth::user()->gender=='Male'?"checked":'':''}} value="Male">
			                  <label class="form-check-label" for="inlineRadio1">Male</label>
			                  <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" name="gender" {{Auth::user()?Auth::user()->gender=='Female'?"checked":'':''}} value="Female">
			                  <label class="form-check-label" for="inlineRadio2">Female</label>
			                </div>
		   
		               		<div class="text-center mt-3 mb-4">
		                		<button type="submit"  class="urban_btn button-btn" >Update Profile</button>
		                	</div>
		   
		                </div></div> 
</div>
	                </form>      
	            </div>
         	</div> 
      	</div>
    </section>
</main>
	
@endsection