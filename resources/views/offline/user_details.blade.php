    <div class="row">
    <input type="hidden" name="user_id" value="{{ $user ? $user->id : '' }}">  
    <div class="col-lg-4 mt-2"><b>Name </b></div>

    <div class="col-lg-8 mt-2"><input type="text" name="user_name" value="{{ $user ? $user->name : '' }}" class="form-control user_name" required></div>

    <div class="col-lg-4 mt-2"><b>Phone No. </b></div>

    <div class="col-lg-8 mt-2"><input type="text" name="user_phone" value="{{ $user ? $user->phone : '' }}" class="form-control" readonly></div>

    <div class="col-lg-4 mt-2"><b>Email </b></div>

    <div class="col-lg-8 mt-2"><input type="text" name="user_email" value="{{ $user ? $user->email : '' }}" class="form-control" required></div>

    <div class="col-lg-4 mt-2"><b>Address </b></div>

        <div class="col-lg-8 mt-2">
            <select name="address_id" class="form-control select2 address-select" required>
                <option value="">Select Address</option>
                @foreach($address as $add)
                <option value="{{ $add->id }}" {{$add->id==Session::get('off_line_address_id')?'selected':''}}>{{ $add->flat_no }}  ({{ $add->address_type }}) {{ $add->address }}</option>
                @endforeach
            </select>
        </div>

        <!-- <div class="col-lg-2 mt-2"><a href="javascript:" id="myBtn" title="Add Address" class="btn btn-outline-danger btn-sm"><i class="fa fa-plus mt-2"></i></a></div> -->

    </div>