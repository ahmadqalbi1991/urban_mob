@php $grand_total = '0'; $main_total = '0'; @endphp
@foreach($attribute_items as $key => $item)
<tr>
    <th scope="row"><button class="btn btn-danger btn-sm" type="button" onclick="removeAttr({{ $item->id }})">X</button></th>
    <th scope="row">{{ $item->attribute_name }}</th>
    <th scope="row">{{ $item->attribute_item_name }}</th>
    <td>AED {{ $item->attribute_price }}</td>
    <td>{{ $item->attribute_qty }}</td>
    <td class="text-right">AED {{ $item->attribute_price*$item->attribute_qty }}</td>
</tr>
@php 
    $grand_total += $item->attribute_price*$item->attribute_qty; 
@endphp
@endforeach
<?php $off_line_booking = App\OfflineBooking::find(Session::get('off_line_booking_id')); ?>
@if($off_line_booking && $off_line_booking->material_status=='Apply')
<tr class="text-right">
    <td colspan="5">Material Charge</td>
    <td>AED {{$off_line_booking?$off_line_booking->material_charge:''}}</td>
</tr>
@php 
    $grand_total += $off_line_booking?$off_line_booking->material_charge:'0'; 
@endphp
@endif

<tr class="text-right">
    <td colspan="5">Sub Total</td>
    <td>AED {{$grand_total}}</td>
</tr>