

<div class="float-left">
	<span>Customer:</span>{{$customer->name}} <br>
	<span>Contact:</span>{{$customer->phone}}
</div>
<div class="float-right">
	<span>Bill:</span>{{$request->month}}-{{$request->year}}
</div>
<table class="table table-striped table-bordered table-sm">
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
                <td>{{$final_amount}}</td>
            </tr>
		@endif
	</tbody>
</table>
