<option value="">Select Sub Category</option>
@foreach($sub_cate as $key => $sub_c)
    @if($sub_c->sub_category)
    <option value="{{$sub_c->sub_category_id}}">{{$sub_c->sub_category->name}}</option>
    @endif
@endforeach