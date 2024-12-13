<div class="card-header">
    WOULD YOU LIKE US TO BRING A CLEANING MATERIALS??
</div>
<div class="card-body" style="padding-top: 0px;">
    <div class="form-check form-check-inline">
        <input class="form-check-input yes" type="radio" name="materialscharge" onclick="calculate_material('Yes')" id="mater1" value="Yes" required>
        <label class="form-check-label" for="mater1">Yes</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input no" type="radio" name="materialscharge" onclick="calculate_material('No')" id="mater2" value="No" required>
        <label class="form-check-label" for="mater2">No</label>
    </div>
    <div class="mt-2">Materials Charge AED {{$peramt}}</div>
    <input type="hidden" name="material_charge" class="material_charge" value="{{$peramt}}">
</div>
