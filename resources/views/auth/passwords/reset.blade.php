@extends('layouts.auth')

@section('content')

    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <form class="md-float-material form-material" method="POST" action="{{ route('login') }}">
					
                        {{ csrf_field() }}

                        <div class="text-center">
                            <img src="{{asset('theme/files/assets/images/logo.png')}}" alt="logo.png">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center txt-primary">Password Reset</h3>
                                    </div>
                                </div>
                                <div class="row m-b-20">
								
                                </div>
                                <p class="text-muted text-center p-b-5">Enter new password to continue</p>
								
                                <div class="form-group form-primary {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="text" name="email" class="form-control" required="" value="{{old('email')}}" placeholder="Email">
                                    <span class="form-bar"></span>
									
									@if ($errors->has('email'))
										<span class="messages">
											<p class="text-danger error">{{ $errors->first('email') }}</p>
										</span>
									@endif
									
									
                                </div>
								
                                <div class="form-group form-primary {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input type="password" name="password" class="form-control" required="" placeholder="Password">
                                    <span class="form-bar"></span>
									
									@if ($errors->has('password'))
										<span class="messages">
											<p class="text-danger error">{{ $errors->first('password') }}</p>
										</span>
									@endif
                                </div>
								
                                <div class="form-group form-primary {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <input type="password" name="password_confirmation" class="form-control" required="" placeholder="Password Confirmation">
                                    <span class="form-bar"></span>
									
									@if ($errors->has('password_confirmation'))
										<span class="messages">
											<p class="text-danger error">{{ $errors->first('password_confirmation') }}</p>
										</span>
									@endif
                                </div>
								
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">LOGIN</button>
                                        
                                    </div>
                                </div>
                                <p class="text-inverse text-left">Login now<a href="/login"> <b class="f-w-600">Click here </b></a></p>
                                <p class="text-inverse text-left">Don't have an account?<a href="/register"> <b class="f-w-600">Register here </b></a>for free!</p>
                            </div>
                        </div>
                    </form>
                        <!-- end of form -->
                    </div>
                    <!-- Authentication card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>

@endsection



@section("head-css")

<!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/icon/themify-icons/themify-icons.css')}}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/icon/icofont/css/icofont.css')}}">


@endsection
