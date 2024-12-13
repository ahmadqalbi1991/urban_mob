<!-- Maid Service First Option -->
<option value="">Select Sub Category</option>
@foreach($main_attr_itms as $key => $attributeItems)
    @if($attributeItems->attributeItem)
    <option value="{{$attributeItems->id}}">{{$attributeItems->attributeItem->value}} (AED {{$attributeItems->attribute_price}})</option>
    @endif
@endforeach