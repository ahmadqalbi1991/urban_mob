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
                                    <div class="brand-logo text-center">
                                        <img class="rounded-circle" src="{{asset('images/logo.png')}}" width="125" height="125" alt="logo">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                                <form action="{{route('reset.password.process')}}" method="post">
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control rounded-0 bg-transparent" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="email" autofocus>
                                        @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="password" class="form-control rounded-0 bg-transparent" name="password"  placeholder="Password"  autocomplete="email" autofocus>
                                        @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="password" class="form-control rounded-0 bg-transparent" name="password_confirmation"  placeholder="Confirm Password"  autocomplete="email" autofocus>
                                        @if ($errors->has('password_confirmation'))
                                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <button class="btn btn-primary btn-block border-0" type="submit">Reset Password</button>
                                </form>
                            </div>
                            <div class="card-footer text-center border-0 pt-0">
                                <h6><a href="{{url('admin/login')}}">Login?</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- #/ container -->
@endsection      

       