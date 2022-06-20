
<!-- BEGIN: Head-->
@extends('Auth.layout.master')

@section('content')

<!-- BEGIN: Body-->


<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-v2">
                    <div class="auth-inner hello row m-0">

                        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                                <img style="box-shadow: 5px 10px 8px 10px #d3d1d1;border-radius: 0px 150px " class="img-fluid" src="images/Employee.jpg" width="100%" height="100%" alt="Login V2" /></div>
                        </div>
                        <!-- /Left Text-->
                        <!-- Login-->
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                <img style="margin-left: 30%" class="img-fluid" src="images/mainLogo.ico" width="20%" height="100%" alt="Login V2" />
<br><br>
                                <h2 class="card-title fw-bold mb-1">Welcome to Human Resources Managment System! 👋</h2>
                                <p class="card-text mb-2">Please sign-in to your account and start new adventure</p>
                                <form class="auth-login-form mt-2" action="{{ route('auth.login.action') }}" method="POST">
                                   @csrf
                                   @if(session()->has('message'))
                                   <span style="color: green">{{ session('message') }}</span>
                                    @endif
                                   @if(session()->has('verified'))
                                        <span style="color: green">{{ session('verified') }}</span>
                                   @endif
                                    <div class="mb-1">
                                        <label class="form-label" for="login-email">Email</label>
                                        <input class="form-control" id="login-email" type="text" name="email" placeholder="john@example.com" aria-describedby="login-email" autofocus="" tabindex="1" />
                                    </div>
                                    <div class="mb-1">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="login-password">Password</label><a href="{{ route('auth.forget-pass') }}"><small>Forgot Password?</small></a>
                                        </div>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input class="form-control form-control-merge" id="password" type="password" name="password" placeholder="············" aria-describedby="login-password" tabindex="2" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>

                                    <button type="submit"  class="btn btn-primary w-100" tabindex="4"> {{ __('Login') }}</button>
                                </form>
                                <p class="text-center mt-2"><span>New on our platform?</span><a href="{{ route('auth.register') }}"><span>&nbsp;Create an account</span></a></p>

                            </div>
                        </div>
                        <!-- /Login-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</body>
<!-- END: Body-->
@endsection
