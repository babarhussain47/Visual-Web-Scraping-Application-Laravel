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
    <link type="text/css" href="{{ asset('theme/files/assets/icon/feather/css/feather.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('theme/files/assets/css/style.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('theme/files/assets/css/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
	
	@yield('head-css')
	@yield('head-js')
	
</head>
<body class="">
	
			@include('include.plugins.preloader')
			@yield('content')

	    <!-- Required Jquery -->
	<script type="text/javascript" src="{{asset('theme/files/bower_components/jquery/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/jquery-ui/js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/popper.js/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="{{asset('theme/files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="{{asset('theme/files/bower_components/modernizr/js/modernizr.js')}}"></script>
    <!-- Chart js -->
    <script type="text/javascript" src="{{asset('theme/files/bower_components/chart.js/js/Chart.js')}}"></script>
    <!-- amchart js -->
    <script src="{{asset('theme/files/assets/pages/widget/amchart/amcharts.js')}}"></script>
    <script src="{{asset('theme/files/assets/pages/widget/amchart/serial.js')}}"></script>
    <script src="{{asset('theme/files/assets/pages/widget/amchart/light.js')}}"></script>
    <script src="{{asset('theme/files/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/assets/js/SmoothScroll.js')}}"></script>
    <script src="{{asset('theme/files/assets/js/pcoded.min.js')}}"></script>
    <!-- custom js -->
    <script src="{{asset('theme/files/assets/js/vartical-layout.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/assets/pages/dashboard/custom-dashboard.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/assets/js/script.min.js')}}"></script>

		
	@yield('body-js')
</body>
</html>
