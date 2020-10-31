<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Handy Import | Data Extractor | Data Minner | Data Mining and Extraction</title>
	<link rel="shortcut icon" href="{{asset('theme/files/assets/images/small-logo.png')}}" type="image/x-icon" />
    <!-- Styles -->
    <link type="text/css" href="{{ asset('theme/files/bower_components/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
	
    <!-- themify-icons line icon -->
	
    <link type="text/css" href="{{ asset('theme/files/assets/icon/themify-icons/themify-icons.css') }}" rel="stylesheet">
    <!-- ico font -->
	
    <link type="text/css" href="{{ asset('theme/files/assets/icon/icofont/css/icofont.css') }}" rel="stylesheet">
    <!-- feather Awesome -->
	
    <link type="text/css" href="{{ asset('theme/files/assets/icon/feather/css/feather.css') }}" rel="stylesheet">
    <!-- jpro forms css -->

	
    <link type="text/css" href="{{ asset('theme/files/assets/icon/feather/css/feather.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('theme/files/assets/css/style.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('theme/files/assets/css/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
	
	    <!-- notify js Fremwork -->
    <!--link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.brighttheme.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.buttons.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.history.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.mobile.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/pnotify/notify.css')}} "-->

	@yield('head-css')
	@yield('head-js')
	
</head>
<body class="">
     <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
			
			@include('include.plugins.preloader')
			 <!-- Load Preloader Plugin here -->
				<!-- top nav here-->
				@include("include.navigation.nav")
		<div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    @include("include.sidebar.index")
					<!--side bar code her-->
					<div class="pcoded-content">
                        <div class="pcoded-inner-content">
							<div class="main-body">
                                <div class="page-wrapper">
									@include('include.session.message')
                                    <div class="page-body">
                                        
										@yield('content')
                                    
									</div>
                                </div>

                                <div id="styleSelector">

                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>			
		</div>
    </div>

	    <!-- Required Jquery -->
	<script type="text/javascript" src="{{asset('theme/files/bower_components/jquery/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/jquery-ui/js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/popper.js/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/bootstrap/js/bootstrap.min.js')}}"></script>
	

	
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="{{asset('theme/files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>
    <!-- modernizr js -->
    <!--script type="text/javascript" src="{{asset('theme/files/bower_components/modernizr/js/modernizr.js')}}"></script>
    <!-- Chart js -->
    <!--script type="text/javascript" src="{{asset('theme/files/bower_components/chart.js/js/Chart.js')}}"></script>
	
	
	    <!-- pnotify js >
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.desktop.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.buttons.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.confirm.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.callbacks.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.animate.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.history.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.mobile.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.nonblock.js')}} "></script-->
	
	
    <!-- amchart js >
    <script src="{{asset('theme/files/assets/pages/widget/amchart/amcharts.js')}}"></script>
    <script src="{{asset('theme/files/assets/pages/widget/amchart/serial.js')}}"></script>
    <script src="{{asset('theme/files/assets/pages/widget/amchart/light.js')}}"></script-->
    <script src="{{asset('theme/files/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/assets/js/SmoothScroll.js')}}"></script>
    <script src="{{asset('theme/files/assets/js/pcoded.min.js')}}"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
	
	@yield('body-js')
	
    <!-- custom js -->
    <script src="{{asset('theme/files/assets/js/vartical-layout.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/assets/js/script.js')}}"></script>

		

	
</body>
</html>
