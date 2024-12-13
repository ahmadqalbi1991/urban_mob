@extends('web.layout.header')
@section('title','Contact Support')

@section('meta_tags')
<meta name="description" content="Do you have an inquiry we can help with? Do you have some feedback for us? Just fill out the form and we'll do our very best to get back to you right away!">
@endsection

@section('content')
	<section class="pt-50  p-relative">
      	

    	
     	<div class="container mt-40" >
    
      		<h3>Contact Information</h3>  
      		<p>Please provide Your Information.</p>
		    <form class="text-start" action="{{url('contact/support')}}" method="POST">
		    	 @csrf
		      	<div class="row">
		            <div class="col-6">
		            	<input type="text"  class="form-control bg-white mb-4" name="name" placeholder="Name" required>
		           	</div>

		           	<div class="col-6">
		            	<input type="email"  class="form-control bg-white mb-4" name="email" placeholder="E-mail" required>
		           	</div>

		           <div class="col-6">
		            <input type="text"  class="form-control bg-white mb-4" name="address" placeholder="Address">
		           </div>
		       
		            <div class="col-6">
		            <input type="text"  class="form-control bg-white mb-4" name="phone" placeholder="Phone Number" required>
		           </div>

		           <div class="col-6">
		            <input type="text"  class="form-control bg-white mb-4" name="whatsapp" placeholder="WhatsApp Number">
		           </div>
		        </div>

		        <div class="row">
		            <div class="col-12 fs-15 ml-1">
		                <label for="comment">Comment:</label>
		                <textarea class="form-control" id="comment" name="comment"></textarea>
		              </div>
		        </div>
		        
				<div class="row justify-content-center">
				<div class="col-sm-4">
		            <button class="urban_btn mt-30" type="submit">Submit</button>
</div>
</div>
		    </form>
      </div>

	</section>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<h5 class="modal-title" id="exampleModalLabel">Question</h5>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      	</div>
	      	<form action="{{ url('store/question') }}" method="POST" id="enquiry_form">
			@csrf
		      	<div class="modal-body">
		        	<div class="mb-3">
				    	<label for="exampleInputEmail1" class="form-label">Mobile No. :</label>
				    	<input type="text" name="mobileno" class="form-control bg-white mobileno mb-4" placeholder="Enter Mobile Number" required>
		      			<p class="text-danger error"></p>
			  		</div>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		        	<button type="button" class="btn btn-primary" onclick="submitForm()">Submit</button>
		      	</div>
		  	</form>
	    </div>
  	</div>
</div>
@endsection

@section('script')
<script>
function submitForm() {
	var phone = jQuery('.mobileno').val();
	 if(phone.length > 9 && phone.length < 11){
	 	jQuery('#enquiry_form').submit();
	 } else {
	 	jQuery('.error').text('Please Enter Valid Number');
	 }
}
</script>
@endsection