
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">

                    <div class="navbar-logo">
                        <a class="mobile-menu" id="mobile-collapse" href="#!">
                            <i class="feather icon-menu"></i>
                        </a>
                        <a href="/">
                            <img class="img-fluid" src="{{asset('theme/files/assets/images/logo.png')}}" alt="Theme-Logo" />
                        </a>
                        <a class="mobile-options">
                            <i class="feather icon-more-horizontal"></i>
                        </a>
                    </div>

                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li class="header-search">
                                <div class="main-search morphsearch-search">
                                    <div class="input-group">
                                        <span class="input-group-addon search-close"><i class="feather icon-x"></i></span>
                                        <input type="text" class="form-control">
                                        <span class="input-group-addon search-btn"><i class="feather icon-search"></i></span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()">
                                    <i class="feather icon-maximize full-screen"></i>
                                </a>
                            </li>
							@if(!Auth::user()->phone_verified)
								<li>
									<a href="{{route('show_phone_verify')}}" onclick="">
										Verify Phone
									</a>
								</li>
							@endif
							@if(!Auth::user()->email_verified)
								<li>
									<a href="{{route('show_email_verify')}}" onclick="">
										Verify Email
									</a>
								</li>
							@endif
							
                        </ul>
                        <ul class="nav-right">
                              
							@include("include.navigation.user")
							
                        </ul>
                    </div>
                </div>
            </nav>
