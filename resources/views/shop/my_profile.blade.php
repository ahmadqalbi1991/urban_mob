@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">My Profile</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        
                    </div>
                </div>
            <div class="container-fluid">
                @include('flash_msg')
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8">
                        <div class="card">
                            <div class="card-body">
                            <div class="custom-tab-2">
                                    <ul class="nav nav-tabs nav-justified">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#shop">Shop Setting</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#profile">Profile Setting</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#password">Change Password</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content tab-content-default">
                                    <div class="tab-pane fade show active" id="shop" role="tabpanel">
                                            <form action="{{ route('my.shop.save') }}" method="post" id="shop_setting" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label>Shop Name<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="shop_name" placeholder="" value="@isset($shopDetail->shop_name){{ $shopDetail->shop_name }}@endisset">
                                                </div>
                                                <div class="form-group">
                                                    <label>Shop Email<span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="shop_email" placeholder="" value="@isset($shopDetail->shop_email){{ $shopDetail->shop_email }}@endisset">
                                                </div>
                                                <div class="form-group">
                                                    <label>Shop Phone<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="shop_phone" value="@isset($shopDetail->shop_phone){{ $shopDetail->shop_phone }}@endisset" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Shop Address<span class="text-danger">*</span></label>
                                                    <textarea class="form-control" rows="3" name="address">@isset($shopDetail->address){{ $shopDetail->address }}@endisset</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>City<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="city" value="@isset($shopDetail->city){{ $shopDetail->city }}@endisset" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Pincode<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="pincode" value="@isset($shopDetail->pincode){{ $shopDetail->pincode }}@endisset" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>GSTIN<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="GSTIN" value="@isset($shopDetail->GSTIN){{ $shopDetail->GSTIN }}@endisset" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>UPI<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="UPI" value="@isset($shopDetail->UPI){{ $shopDetail->UPI }}@endisset" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>QR</label>
                                                    <input type="file" class="form-control" name="QR" placeholder="">
                                                    @if(isset($shopDetail->QR) && !empty($shopDetail->QR))
                                                    <input type="hidden" class="form-control" name="qr_image" value="{{ $shopDetail->QR }}">
                                                    <img width="50" height="50" alt="QR" class="mr-3" src="{{QRImagePath($shopDetail->QR)}}">
                                                    @endif
                                                </div>
                                                <input type="hidden" class="form-control" name="id" value="@isset($shopDetail->id){{ $shopDetail->id }}@endisset">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel">
                                            <form action="{{ route('my.profile.save') }}" method="post" id="user_profile_setting">
                                                <div class="form-group">
                                                    <label>Name<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="name" placeholder="" value="{{ $user->name }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Email address<span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" placeholder="" value="{{ $user->email }}" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Phone<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Address<span class="text-danger">*</span></label>
                                                    <textarea class="form-control" rows="3" name="address">{{ $user->address }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>City<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="city" value="{{ $user->city }}" placeholder="">
                                                </div>
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="password">
                                        <form action="{{ route('my.password.save') }}" method="post" id="password_setting">
                                                <div class="form-group">
                                                    <label>Current Password<span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" placeholder="" name="current_password">
                                                </div>
                                                <div class="form-group">
                                                    <label >New Password<span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" placeholder="" name="new_password" id="new_password">
                                                </div>
                                                <div class="form-group">
                                                    <label >Confirm New Password<span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" placeholder="" name="confirm_new_password">
                                                </div>
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2"></div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

@endsection      

       