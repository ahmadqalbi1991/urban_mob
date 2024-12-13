<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<h3>Booking Accepted!</h3>
	<p>Hi 
		@if($data && $data->user)
	        {{$data->user?$data->user->name:''}}
	    @else
	        'No Name'
	    @endif
	    ,
	</p>
	<p>Thankyou for placing a service request with Urbanmop.</p>
	<p>Your Booking has been accepted and a professional will be assigned to you one hours prior to your scheduled time.</p>
	<div style="text-align: center;">
		<a href="http://urbanmop.com/">
		<p style="background-color: green;
	    color: white;
	    padding: 6px;
	    width: 30%;
	    text-align: center;">VIEW BOOKING ON URBANMOP</p></a>
	</div>
	<p>Request Details</p>
	<p>Date : <b>{{ $data->date?date('d F Y', strtotime($data->date)):''}}</b></p>
	<p>Time : <b>{{$data->slot?$data->slot->name:''}}</b></p>
</body>
</html>