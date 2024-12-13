<!-- Maid Service Second Option -->
<input type="hidden" name="service" value="maid">
@foreach(App\ServiceAttributeValue::where('ser_attr_val_item_id',$serviceItem->id)->get() as $key => $attributeItems)
	@if($attributeItems->attributeItem)
    <tr>
        <td>
            <input type="radio" name="attribute_id"  id="exampleRadios{{$key}}" onclick="calculateMaterial({{$attributeItems}})" value="{{ $attributeItems->id }}">
        </td>
        <th scope="row">{{$attributeItems->attributeItem->value}}</th>
        <td>{{ $attributeItems->attribute_price }}</td>
        <td>1</td>
        <td>{{ $attributeItems->attribute_price }}</td>
    </tr>
    @endif
@endforeach