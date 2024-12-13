
<!DOCTYPE html>
<html>

<head>
   <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>

<div class="float-left">
	<img class="img-fluid" src="{{asset('images/logo.png')}}" alt="logo" style="height: 99px;">
</div>
<div class="float-right mt-3">
	<br>
	Invoice No:<b>#{{$invoice->id}}</b><br>
	Invoice Of: {{$invoice->month}}-{{$invoice->year}}
</div>
<div class="clearfix" style="clear: both;"></div>
<table class="table">
	<tr>
		<td style="width: 50%">
			<b>From:</b><br>
			<p>
				{{$invoice->vendor->name}} <br> 
				{{$invoice->vendor->address}} <br> 
				{{$invoice->vendor->city}} <br> 
				Email: {{$invoice->vendor->email}}<br> 
				Phone: {{$invoice->vendor->phone}}
			</p>
		</td>
		<td style="width: 50%">
			<b>To:</b><br>
			<p>
				{{$invoice->customer->name}} <br> 
				{{$invoice->customer->address}} <br> 
				{{$invoice->customer->city}} <br> 
				Email: {{$invoice->customer->email}}<br> 
				Phone: {{$invoice->customer->phone}}
			</p>
		</td>
	</tr>
</table>
<table class="table table-bordered table-sm">
	<thead>
		<tr>
			<th>#</th>
			<th>Brand</th>
			<th>Item</th>
			<th>Unit</th>
			<th>Qty</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		@if($orders)
			@php 
                $final_amount= 0;
            @endphp
			@foreach($orders as $key=>$value)
			@php 
                $final_amount += $value->total_amount;
            @endphp
			<tr>
				<td>{{$key+1}}</td>
				<td>{{$value->item_brand}}</td>
				<td>{{$value->item_name}}</td>
				<td>{{$value->item_unit}}</td>
				<td>{{$value->total_qty}}</td>
				<td>{{$value->total_amount}}</td>
			</tr>
			@endforeach
			<tr>
                <td colspan="5" class="text-right mr-2"><b>Total</b></td>
                <td>{{$final_amount}}  </td>
            </tr>
		@endif
	</tbody>
</table>
<h3 class="text-center">Thank you!</h3>
   

</body>

</html>
