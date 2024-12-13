
<p>Dear Partner,</p>
<p>
	Your booking with 
	<b>
		@if($data && $data->user)
			{{$data->user->name}}
		@endif
	</b>
	for 
	<b>
		@if($data && $data->service)
		 	{{$data->service->name}}
		@endif
	</b>
	has been cancelled by the 

	@if($data->vendor && $data->vendor->seller)
	    {{ $data->vendor->seller->company_name }}
	@endif

	With Booking ID

	<b> {{$data->tran_id}}</b>

</p>

<p>Thanks!</p>

<p>Urbanmop</p>