			
					<li class="user-profile header-notification">
                               
                                <div class="dropdown-primary dropdown">
                                    
                                    <div class="dropdown-toggle" data-toggle="dropdown">
                                        <img src="{{asset('theme/files/assets/images/avatar-4.jpg')}}" class="img-radius" alt="User-Profile-Image">
                                        <span>
										<!-- User Name -->
										{{Auth::user()->first_name}} {{Auth::user()->last_name}}
										</span>
                                        <i class="feather icon-chevron-down"></i>
                                    </div>
                                    <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                        <li>
                                            <a href="#!">
                                                <i class="feather icon-settings"></i> Settings
                                            </a>
                                        </li>
                                        <li>
                                            <a href="user-profile.html">
                                                <i class="feather icon-user"></i> Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a href="email-inbox.html">
                                                <i class="feather icon-mail"></i> My Messages
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                                <i class="feather icon-log-out"></i> Logout    
												<form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
													{{ csrf_field() }}
												</form>
												
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                            </li>