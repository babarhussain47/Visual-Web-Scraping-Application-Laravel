@extends('layouts.app')

@section('head-css')

    <!-- jpro forms css -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/j-pro/css/demo.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/j-pro/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/j-pro/css/j-pro-modern.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/switchery/css/switchery.min.css')}}">
	



@endsection


@section('content')


<!-- Page body start -->
<div class="page-body">
	<div class="row">
		
		<div class="col-xl-12 col-md-12">
		<div class="card feed-card">
			<div class="card-header">
				<h5>User Activities</h5>
			
			</div>
			<div class="card-block">
				
				@foreach($user_activities as $activity)
					<div class="row m-b-30">
						<div class="col-auto p-r-0">
							<i class="feather icon-bell bg-simple-c-blue feed-icon"></i>
						</div>
						<div class="col">
							<h6 class="m-b-5">{{$activity->act_desc}} <span class="text-muted f-right f-13">{{$activity->created_at}}</span></h6>
						</div>
					</div>
				@endforeach
				<div class="row">
					<div class="col-xl-6 col-md-6">
						<div class="text-left">
						<a href="{{$user_activities->previousPageUrl()}}" class=" btn btn-primary">Prev</a>
						</div>
					</div>
					<div class="col-xl-6 col-md-6">
						<div class="text-right">
							<a href="{{$user_activities->nextPageUrl()}}" class=" btn btn-primary">Next</a>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	</div>
</div> 
@endsection
<!-- Page body end -->
@section("body-js")
<script>
function startProcessing()
{
	Swal.fire({
  title: 'Retriving Data',
  text: "We are retriving data for processing, please wait...",
  type: 'info',
  showCancelButton: false,
  showConfirmButton: false,
  allowOutsideClick: false
});
}
</script>
@endsection
