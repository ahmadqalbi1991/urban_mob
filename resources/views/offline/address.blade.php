<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('add.offline.address') }}" method="POST">
	        @csrf
                <div class="modal-body">
                    <div class="form-group">
		            	<label>Current Location</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control live_address" list="browsers" placeholder="Search Current Location" onkeypress="getLiveAddres(this.value)" name="address" required>
                            <datalist id="browsers">
                                        
                            </datalist>
                            <div class="input-group-append">
                            <button class="btn btn-warning getLetLong" type="button" onclick="getLetLong()">Search Location</button>
                            </div>
                            
                        </div>
                        <small class="text-danger addresserror"></small>
		            </div>
		            @include('offline.address.live_location')
		         	<br>
		         	<br>
		         	@include('offline.address.map')
                    <div class="form-group">
                        <label>Flat/Office No.</label>
                        <input type="text" name="flat_no" class="form-control" required placeholder="Flat/Office No.">
                    </div>
                    <div class="form-group">
                        <label>Building Name</label>
                        <input type="text" name="building" class="form-control" required placeholder="Building Name.">
                    </div>
                    <div class="form-group">
                        <label>Address Type</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="address_type" value="Home" checked>
                            <label class="form-check-label" for="inlineCheckbox1">Home</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="address_type" value="Office">
                            <label class="form-check-label" for="inlineCheckbox2">Office</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="address_type" value="Work">
                            <label class="form-check-label" for="inlineCheckbox3">Work</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <select class="form-control select2" name="city_id" onchange="getLocality(this.value)" required>
                            <option value="">Select City</option>
                            @foreach($city as $cty)
                            <option value="{{$cty->id}}">{{$cty->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Locality</label>
                        <select class="form-control select2 localitylist" name="locality">
                            <option value="">Select Locality</option>	               			
                        </select>	
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div> -->
