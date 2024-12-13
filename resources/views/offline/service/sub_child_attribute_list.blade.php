@foreach($params as $key => $value)
<tr>
    <td>
        <input type="checkbox" name="attribute_id[]" value="{{ $value['id'] }}" id="checkbox{{ $value['id'] }}" onclick="addToCart({{ $value['id'] }}, 'Normal')">
        <input type="hidden" name="main_sub_cat_id[]" class="main_sub_cat_id{{ $value['id'] }}" value="{{ $value['main_sub_cat_id'] }}">
    </td>
    <td> <b>{{$value['attributename']}}</b> </td>
    <td>AED {{ $value['attribute_price'] }}</td>
    <td>
        <div class="input-group mb-3 qty-w">
            <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" type="button" id="decrease{{ $value['id'] }}" onclick="decreaseValue({{ $value['id'] }})">-</button>
            </div>
            <input type="text" id="number{{ $value['id'] }}" value="1" class="form-control" name="qty[]" readonly>
            <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" type="button" id="increase{{ $value['id'] }}" onclick="increaseValue({{ $value['id'] }})">+</button>
            </div>
        </div>
    </td>
    <td>AED {{ $value['attribute_price'] }}</td>
</tr>
@endforeach