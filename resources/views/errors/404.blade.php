@extends('web.layout.header')
@section('title','Urbanmop | 404')
@section('content')



<div class="row">
	
	<div class="col-lg-3"></div>
	<div class="col-lg-6 text-center mt-4 mb-4">
		
		<h4 class="mb-4">We're sorry, we couldn't find what<br>you were looking for!</h4>

		<button class="btn btn-primary mt-4" type="button" id="head-call-second"><a href="{{ url('/') }}">Back to home</a></button>
		
	</div>

</div>



@endsection