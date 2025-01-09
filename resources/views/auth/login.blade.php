@extends('auth.layout.web')
@section('content')
<!-- auth page content -->
<div class="auth-page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <div>
                        <a href="{{route('admin.home')}}" class="d-inline-block auth-logo">
                            <img src="{{asset('img/world_vision.png')}}" alt="" height="60">
                        </a>
                    </div>
                    <p class="mt-3 fs-15 fw-medium">World Vision Profile</p>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">

                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">Welcome Back !</h5>
                            <p class="text-muted">Sign in to continue to World Vision.</p>
                        </div>
                        <div class="p-2 mt-4">
                            <form method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input name="email" type="text"
                                        class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" autofocus
                                        placeholder="Enter Email" value="{{ old('email', null) }}">
                                    @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password-input">Password</label>
                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                        <input name="password" type="password" id="password-input"
                                            class="form-control pe-5 password-input {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            placeholder="Enter Password">
                                        @if($errors->has('password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                        @endif
                                        <button
                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                            type="button" id="password-addon"><i
                                                class="ri-eye-fill align-middle"></i></button>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" name="remember" type="checkbox"
                                        id="auth-remember-check" style="vertical-align: middle;" />
                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                </div>

                                <div class="mt-4">
                                    <button class="btn btn-success w-100" type="submit">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end auth page content -->

@endsection