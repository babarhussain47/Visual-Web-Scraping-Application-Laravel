@extends('layouts.app')

@section('content')

	<iframe id="extractor_builder_iframe" src="{{route('new_extractor_build',['id' => $id])}}" frameBorder='0' style="position:fixed;  top:6%; left:12.5%; bottom:0; right:0; width:87.5%; height:94%;"> 
	
	</iframe>

@endsection

@section("body-js")
<script>

startProcessing();
function startProcessing()
{
	Swal.fire({
  title: 'Processing',
  text: "We are working on visual builder, Please wait until we make it ready!",
  type: 'info',
  showCancelButton: false,
  showConfirmButton: false,
  allowOutsideClick: false
	});

}


function onLoadSuccess()
{
	Swal.fire({
  title: 'Processing done!',
  text: "Builer is ready",
  type: 'success',
  timer: 1000,
  showCancelButton: false,
  showConfirmButton: false,
  allowOutsideClick: false
	});
}
var chkReadyState = setInterval(function() {
    if (document.readyState == "complete") {
		onLoadSuccess();
        // clear the interval
        clearInterval(chkReadyState);
        // finally your page is loaded.
    }
}, 500);

</script>
@endsection