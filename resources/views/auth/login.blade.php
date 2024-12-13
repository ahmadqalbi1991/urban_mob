@extends('layouts.admin')

@section('content')



    

    <div class="login-bg h-100">

        <div class="container h-100">

            <div class="row justify-content-center h-100">

                <div class="col-md-5">

                    <div class="form-input-content">

                        <div class="card card-login">

                            <div class="card-header">

                                <div class="position-relative  text-center w-100">

                                    <div class="brand-logo">

                                        <?php $setting = App\HomeSetting::first(); ?>
                                        @if($setting->admin_logo)
                                        <img src="{{asset('uploads/home/'.$setting->admin_logo)}}" width="190" height="125" alt="logo">
                                        @endif

                                    </div>

                                </div>

                            </div>

                            <div class="card-body">

                                @if(session()->has('error'))

                                <div class="alert alert-danger alert-dismissible">

                                <button type="button" class="close" data-dismiss="alert">&times;</button>

                                        {{ session()->get('error') }}

                                    </div>

                                @endif

                                <form action="{{url('/login')}}" method="POST">

                                    @csrf

                                    <div class="form-group mb-4">

                                        <input type="text" class="form-control rounded-0 bg-transparent @error('password') is-invalid @enderror" name="email" placeholder="Email/Phone" value="{{ old('email') }}" autocomplete="email" autofocus>

                                        @error('email')

                                            <span class="text-danger" role="alert">

                                                <strong>{{ $message }}</strong>

                                            </span>

                                        @enderror

                                    </div>

                                    <div class="form-group mb-4">

                                        <input type="password" class="form-control rounded-0 bg-transparent @error('password') is-invalid @enderror" name="password" placeholder="Password" autocomplete="current-password">

                                        @error('password')

                                            <span class="text-danger" role="alert">

                                                <strong>{{ $message }}</strong>

                                            </span>

                                        @enderror

                                    </div>

                                    <!-- <div class="form-group ml-3 mb-5">

                                        <input  type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="label-checkbox ml-2 mb-0" for="checkbox1">Remember Password</label>

                                    </div> -->

                                    <button class="btn btn-primary btn-block border-0" type="submit">Login</button>

                                </form>

                            </div>

                            <div class="card-footer text-center border-0 pt-0">

                                <h6><a href="{{url('/forgot-password')}}">You Forgot Password?</a></h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    

    <!-- #/ container -->

@endsection      



       