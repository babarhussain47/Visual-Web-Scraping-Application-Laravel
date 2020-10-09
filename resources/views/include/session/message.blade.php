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
	$message = "Something went wrong please check!";
	$type = 'error';
	$has_message = true;
}


if($has_message)
{
?>
<div class="row">
	<div class="col-sm-12 col-md-12 col-xl-12">
		<div class="alert alert-@if($type=='error')danger @else{{$type}} @endif background-{{$type}}">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<i class="icofont icofont-close-line-circled text-white"></i>
			</button>
			<strong><?php echo $message;?></strong>
		</div>
	</div>
</div>

@section('body-js')
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
	
@endsection
	
<?php } ?>