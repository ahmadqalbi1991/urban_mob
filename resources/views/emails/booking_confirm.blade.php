<h4>
	Booking Confirmation with 
	
	@if($data && $data->user) 
		{{$data->user?$data->user->name:''}}
	@endif

	for 

	@if($data && $data->service) 
		{{$data->service?$data->service->name:''}}
	@endif

</h4>
<p>
	Thanks for responding. Your booking has been confirmed with 
	@if($data && $data->user) 
		{{$data->user?$data->user->name:''}}
	@endif

	for 

	@if($data && $data->service) 
		{{$data->service?$data->service->name:''}}
	@endif
</p>
<p>If you have not accepted this booking or for any assistance contact UrbanMop helpline at 0526188291 / 0585814007 or send email at partners@urbanmop.com before 4 hours of scheduled booking time.</p>