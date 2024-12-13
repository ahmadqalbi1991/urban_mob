@extends('layouts.dashboard')

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

                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#profile">Profile Setting</a>

                                        </li>

                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#password">Change Password</a>

                                        </li>

                                    </ul>

                                    <div class="tab-content tab-content-default">

                                        <div class="tab-pane fade show active" id="profile" role="tabpanel">

                                            <form action="{{ route('profile.save') }}" method="post" id="admin_profile_setting" enctype="multipart/form-data">

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

                                                    <label>Profile</label>

                                                    <input type="file" class="form-control" name="profile" id="admin_logo">

                                                    <img id="pre_header_logo" class="mt-2" height="100">

                                                </div>


                                                @csrf

                                                <button type="submit" class="btn btn-primary">Update</button>

                                            </form>

                                        </div>

                                        <div class="tab-pane fade" id="password">

                                        <form action="{{ route('password.save') }}" method="post" id="password_setting">

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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 
<script type="text/javascript">
      
$(document).ready(function (e) {
 
   $('#pre_header_logo').hide();

   $('#admin_logo').change(function(){

    $('#pre_header_logo').show();
            
    let reader = new FileReader();
 
    reader.onload = (e) => { 
 
      $('#pre_header_logo').attr('src', e.target.result); 
    }
 
    reader.readAsDataURL(this.files[0]); 
   
   });

});
 
</script>     



       