@extends('Auth.layout.master')

@section('content')


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
                    <div class="auth-inner row m-0">

                        <!-- Left Text-->
                        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                                <img style="box-shadow: 5px 10px 8px 10px #d3d1d1;border-radius: 0px 150px " class="img-fluid" src="images/Employee.jpg" width="100%" height="100%" alt="Login V2" /></div>
                        </div>
                        <!-- /Left Text-->
                        <!-- Register-->
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                <img style="margin-left: 30%;" class="img-fluid" src="images/mainLogo.ico" width="20%" height="100%" alt="Login V2" />
                                {{-- <br><br> --}}
                                <h3 style="height: 0px;padding-bottom:10px" class="card-title fw-bold mb-1">Adventure starts here 🚀</h3>
                                <form class="auth-register-form mt-2" action=" {{ route('auth.register.action')}}" method="POST" autocomplete="off" >
                                    @csrf
                                    @if(session()->has('message'))
                                        <span style="color: green">{{ session('message') }}</span>
                                    @endif
                                    <div class="mb-1">
                                        <label class="form-label" for="register-username">Full Name</label>
                                        <input class="form-control" id="register-username" type="text" name="name" value="{{ old('full_name') }}" placeholder="Full Name" aria-describedby="register-username" autofocus="" tabindex="1" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="register-email">Email</label>
                                        <input class="form-control" id="register-email" type="text" name="email" value="{{ old('email') }}" placeholder="example@example.com" aria-describedby="register-email" tabindex="2" />
                                    </div>
                                    {{-- <div class="col-md-12 mb-1">
                                        <label class="form-label" for="select2-basic">Verification Questions</label>
                                        <select class="select2 form-select" name="verification_question" id="select2-basic" tabindex="3">
                                            <option disabled selected>Select</option>
                                            @foreach ($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->question_en }}</option>
                                            @endforeach

                                        </select>
                                    </div> --}}
                                    {{-- <div class="mb-1">
                                        <label class="form-label" for="register-username">Verification Answer</label>
                                        <input class="form-control" id="register-username" type="text" name="verification_answer" value="{{ old('verification_answer') }}"  aria-describedby="register-username" autofocus="" tabindex="4" />
                                    </div> --}}
                                    <div class="mb-1">
                                        <label class="form-label" for="register-password">Password</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input class="form-control form-control-merge" id="register-password" type="password" name="password" value="{{ old('password') }}" placeholder="············" aria-describedby="register-password" tabindex="5" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="register-password">Password Confirmation</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input class="form-control form-control-merge" id="register-password" type="password" value="{{ old('password_confirmation') }}" name="password_confirmation" placeholder="············" aria-describedby="register-password" tabindex="6" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="mb-1">

                                    </div>
                                    <button class="btn btn-primary w-100" tabindex="8">Sign up</button>
                                </form>
                                <p class="text-center mt-2"><span>Already have an account?</span><a href="{{ route('login') }}"><span>&nbsp;Sign in instead</span></a></p>


                            </div>
                        </div>
                        <!-- /Register-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

</body>
@endsection
