<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HandyImport | Import Unstructured website to structured data</title>
<link rel="shortcut icon" href="{{asset('theme/files/assets/images/small-logo.png')}}" type="image/x-icon" />
    
    <!-- Styles -->
    <link type="text/css" href="{{ asset('theme/files/bower_components/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('theme/files/assets/icon/feather/css/feather.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('theme/files/assets/css/style.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('theme/files/assets/css/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
	
	
	    <!-- notify js Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.brighttheme.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.buttons.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.history.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/pnotify/css/pnotify.mobile.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/pnotify/notify.css')}} ">

	
    <link type="text/css" href="{{ asset('theme/files/assets/pages/multi-step-sign-up/css/reset.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('theme/files/assets/pages/multi-step-sign-up/css/style.css') }}" rel="stylesheet">


</head>
<body class="multi-step-sign-up">
	<?php


	$has_message = false;
	$type = 'primary';
	$message = "Notification test";
if(session('primary'))
{
	$message = session('primary');
	$has_message = true;
}
else if(session('success'))
{
	$message = session('success');
	$type = 'success';
	$has_message = true;
}
else if(session('info'))
{
	$message = session('infos');
	$type = 'info';
	$has_message = true;
}
else if(session('error'))
{
	$message = session('error');
	$type = 'error';
	$has_message = true;
}

if(count($errors) > 0)
{
	$message = 'Please fix the following errors:-<br>' ;
	foreach($errors->all() as $i => $error)
	{
		$message .= ($i+1).". ".$error."<br>";
	}
	$type = 'error';
	$has_message = true;
}

?>
    @include('include.plugins.preloader')

	
	 <!-- Pre-loader end -->
    <form id="msform"  method="POST" action="{{ route('register') }}">
		{{csrf_field()}}
        <!-- progressbar -->
        <ul id="progressbar">
            <li class="active">Account Setup</li>
            <li>Personal Details</li>
            <li>Final Step</li>
        </ul>
		
        <!-- fieldsets -->
        <fieldset>
            <img class="logo" src="theme/files/assets/images/logo.png" alt="logo.png">
            <h2 class="fs-title">Sign up</h2>
            <h3 class="fs-subtitle">Please enter your account details</h3>
			
			<div class="form-group form-primary {{ $errors->has('email') ? ' has-error' : '' }}">
				<input type="text" name="email" class="form-control"  value="{{old('email')}}" placeholder="Email Address">
				<span class="form-bar"></span>
				
				@if ($errors->has('email'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('email') }}</p>
					</span>
				@endif
				
			</div>
			
			<div class="form-group form-primary {{ $errors->has('phone') ? ' has-error' : '' }}">
				<input type="text" name="phone" class="form-control"  value="{{old('phone')}}" placeholder="Phone Number">
				<span class="form-bar"></span>
				
				@if ($errors->has('phone'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('phone') }}</p>
					</span>
				@endif
				
			</div>
			
			<div class="form-group form-primary {{ $errors->has('password') ? ' has-error' : '' }}">
				<input type="password" name="password" class="form-control"  value="{{old('password')}}" placeholder="Password">
				<span class="form-bar"></span>
				
				@if ($errors->has('password'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('password') }}</p>
					</span>
				@endif
				
			</div>
			
			
			<div class="form-group form-primary {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
				<input type="password" name="password_confirmation" class="form-control"  value="{{old('password_confirmation')}}" placeholder="Retype Password">
				<span class="form-bar"></span>
				
				@if ($errors->has('password_confirmation'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('password_confirmation') }}</p>
					</span>
				@endif
				
			</div>
			
			<button type="button" name="next" class="btn btn-primary next" value="Next">Next</button>
			
			<br>
			<br>
		<p class="text-inverse text-left">Already registered?<a href="/login"> <b class="f-w-600">Login Here </b></a></p>
        </fieldset>
		
		
		<fieldset>
            <img class="logo" src="theme/files/assets/images/logo.png" alt="logo.png">
            <h2 class="fs-title">Sign up</h2>
            <h3 class="fs-subtitle">Enter your personal information</h3>
			
			<div class="form-group form-primary {{ $errors->has('first_name') ? ' has-error' : '' }}">
				<input type="text" name="first_name" class="form-control"  value="{{old('first_name')}}" placeholder="First Name">
				<span class="form-bar"></span>
				
				@if ($errors->has('first_name'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('first_name') }}</p>
					</span>
				@endif
				
			</div>
			
			<div class="form-group form-primary {{ $errors->has('last_name') ? ' has-error' : '' }}">
				<input type="text" name="last_name" class="form-control"  value="{{old('last_name')}}" placeholder="Last Name">
				<span class="form-bar"></span>
				
				@if ($errors->has('last_name'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('last_name') }}</p>
					</span>
				@endif
				
			</div>
			
			<div class="form-group form-primary {{ $errors->has('street_address') ? ' has-error' : '' }}">
				<input type="text" name="street_address" class="form-control"  value="{{old('street_address')}}" placeholder="Street Address">
				<span class="form-bar"></span>
				
				@if ($errors->has('street_address'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('street_address') }}</p>
					</span>
				@endif
				
			</div>
			
			<div class="form-group form-primary {{ $errors->has('city') ? ' has-error' : '' }}">
				<input type="text" name="city" class="form-control"  value="{{old('city')}}" placeholder="City">
				<span class="form-bar"></span>
				
				@if ($errors->has('city'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('city') }}</p>
					</span>
				@endif
				
			</div>
			
            <button type="button" name="previous" class="btn btn-inverse btn-outline-inverse previous" value="Previous">Previous</button>
			<button type="button" name="next" class="btn btn-primary next" value="Next">Next</button>
        </fieldset>
		
		<fieldset>
            <img class="logo" src="theme/files/assets/images/logo.png" alt="logo.png">
            <h2 class="fs-title">Sign up</h2>
            <h3 class="fs-subtitle">Country and State </h3>
			
			<div class="form-group form-primary {{ $errors->has('state') ? ' has-error' : '' }}">
				<input type="text" name="state" class="form-control"  value="{{old('state')}}" placeholder="State">
				<span class="form-bar"></span>
				
				@if ($errors->has('state'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('state') }}</p>
					</span>
				@endif
				
			</div>
			
			<div class="form-group form-primary {{ $errors->has('zip') ? ' has-error' : '' }}">
				<input type="text" name="zip" class="form-control"  value="{{old('zip')}}" placeholder="Zip/Postal Code">
				<span class="form-bar"></span>
				
				@if ($errors->has('zip'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('zip') }}</p>
					</span>
				@endif
				
			</div>
			
			<div class="form-group form-primary {{ $errors->has('country') ? ' has-error' : '' }}">
				<input type="text" name="country" class="form-control"  value="{{old('country')}}" placeholder="Country">
				<span class="form-bar"></span>
				
				@if ($errors->has('country'))
					<span class="messages">
						<p class="text-danger error">{{ $errors->first('country') }}</p>
					</span>
				@endif
				
			</div>
			
            <button type="button" name="previous" class="btn btn-inverse btn-outline-inverse previous" value="Previous">Previous</button>
			<input type="submit" class="btn btn-primary next" value="Register"/>
        </fieldset>
    </form>

	
	
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
			    <!-- pnotify js -->
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.desktop.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.buttons.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.confirm.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.callbacks.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.animate.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.history.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.mobile.js')}} "></script>
    <script type="text/javascript" src="{{asset('theme/files/bower_components/pnotify/js/pnotify.nonblock.js')}} "></script>

	
    <script src="{{asset('theme/files/assets/js/vartical-layout.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/assets/pages/dashboard/custom-dashboard.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/assets/js/script.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/files/assets/pages/multi-step-sign-up/js/main.js')}}"></script>
<?php
if($has_message)
{
?>
	<script>
	  // Custom top position
	   var stack_custom_top = {"dir1": "down", "dir2": "right", "push": "top", "spacing1": 1};
	   var message = "<?php echo $message;?>";
	  show_stack_custom_top("<?php echo $type;?>");
    function show_stack_custom_top(type) {
        var opts = {
            title: "Over here",
            text: message,
            width: "100%",
            cornerclass: "no-border-radius",
            addclass: "stack-custom-top bg-primary",
            stack: stack_custom_top
        };
        switch (type) {
            case 'error':
            opts.title = "Oh No";
            opts.text = message;
            opts.addclass = "stack-custom-top bg-danger";
            opts.type = "error";
            break;

            case 'info':
            opts.title = "Info";
            opts.text = message;
            opts.addclass = "stack-custom-top bg-info";
            opts.type = "info";
            break;

            case 'success':
            opts.title = "Success";
            opts.text = message;
            opts.addclass = "stack-custom-top bg-success";
            opts.type = "success";
            break;
        }
        new PNotify(opts);
    }
	</script>

	
<?php } ?>
</body>
</html>
