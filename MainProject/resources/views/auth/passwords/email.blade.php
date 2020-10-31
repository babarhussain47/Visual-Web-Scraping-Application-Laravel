@extends('layouts.auth')

@section('content')
 <!-- Pre-loader end -->
    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container-fluid">
            <div class="row">
			
                <div class="col-sm-12">
                    <!-- Authentication card start -->
    
                    <form class="md-float-material form-material" method="POST" action="{{ route('password.email') }}">
					
				
                        {{ csrf_field() }}
                        <div class="text-center">
                            <img src="{{asset('theme/files/assets/images/logo.png')}}" alt="logo.png">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
							@if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-left">Recover your password</h3>
                                    </div>
                                </div>
                                  <div class="form-group form-primary {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="text" name="email" class="form-control" required="" value="{{old('email')}}" placeholder="Email">
                                    <span class="form-bar"></span>
									
									@if ($errors->has('email'))
										<span class="messages">
											<p class="text-danger error">{{ $errors->first('email') }}</p>
										</span>
									@endif
									
									
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Reset Password</button>
                                    </div>
                                </div>
                                <p class="f-w-600 text-right">Back to <a href="/login">Login.</a></p>
                                <div class="row">
                                    <div class="col-md-10">
                                        <p class="text-inverse text-left m-b-0">
										
										</p>
                                        <p class="text-inverse text-left"><a href="/"><b class="f-w-600">Back to website</b></a></p>
                                    </div>
                                    <div class="col-md-2">
                                        <img width="45px" src="{{asset('theme/files/assets/images/small-logo.png')}}" alt="small-logo.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--<div class="login-card card-block auth-body mr-auto ml-auto">-->
                        <!--<form class="md-float-material form-material">-->
                            <!--<div class="text-center">-->
                                <!--<img src="../files/assets/images/logo.png" alt="logo.png">-->
                            <!--</div>-->
                            <!--<div class="auth-box">-->
                                <!---->
                            <!--</div>-->
                        <!--</form>-->
                        <!--&lt;!&ndash; end of form &ndash;&gt;-->
                    <!--</div>-->
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
