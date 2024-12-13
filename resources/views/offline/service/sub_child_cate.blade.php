<option value="">Select Child Category</option>
@foreach($sub_child_cate as $key => $child_cate)
    @if($child_cate->child_category)
    <option value="{{$child_cate->child_category_id}}">{{$child_cate->child_category->name}}</option>
    @endif
@endforeach