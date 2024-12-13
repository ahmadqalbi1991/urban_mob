
@if($invoiceId)
<div class="alert alert-success">
  <strong>Success!</strong> Invoice Generated Now </a>.
</div>
@else
<div class="alert alert-danger">
    <strong>Error!</strong> Invoice Generation Failed, Try Later</a>.
  </div>
@endif

<table class="table table-striped table-bordered table-sm">
	<tbody>
		<tr>
			<td>Customer</td>
			<td>{{$customer->name}}</td>
		</tr>
		<tr>
			<td>Contact</td>
			<td>{{$customer->phone}}</td>
		</tr>
		<tr>
			<td>Billing</td>
			<td>{{$request->month}}-{{$request->year}}</td>
		</tr>
		<tr>
			<td>Invoice Amount</td>
			<td>{{$order->final_amount}}</td>
		</tr>
		<tr>
			<td>Invoice ID</td>
			<td>{{$invoiceId}}</td>
		</tr>
	</tbody>
</table>
