<!-- <label for="exampleInputEmail1">Locality</label>
<br/>
<select class="form-control select2" name="locality">
	<option value="">Select Locality</option>
	@foreach($locality as $val)
	<option value="{{$val->id}}">{{$val->name}}</option>
	@endforeach
</select> -->
<option value="">Select Locality</option>
<option value="">None</option>
@foreach($locality as $val)
<option value="{{$val->id}}">{{$val->name}}</option>
@endforeach