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
		<div class="col-sm-12">
			<!-- Job application card start -->
			<div class="card">
				<div class="card-header">
					<h5>Create New Extractor</h5>
					<span>Please provide basic information to proceed next</span>
				</div>
				<div class="card-block">
					<div class="j-wrapper j-wrapper-640">
						<form action="{{route('new_extractor_post')}}" method="post" class="j-pro" id="j-pro" novalidate>
							{{csrf_field()}}
							<!-- end /.header-->
							<div class="j-content">
							
								<!-- start extractor name -->
								<div class="j-row">
									<div class="j-span12 j-unit">
										<div class="j-input {{ $errors->has('extractor_name') ? ' j-error-view' : '' }}">
											<label class="j-icon-right" for="extractor_name">
												<i class="icofont icofont-text-width"></i>
											</label>
											<input type="text" id="extractor_name" name="extractor_name" value="{{old('extractor_name')}}" placeholder="Extractor Name">
											<span class="j-tooltip j-tooltip-right-top">Extractor Name (You can identify later)</span>
											@if ($errors->has('extractor_name'))
												<span class="j-error-view">{{ $errors->first('extractor_name') }}</span>
											@endif
										</div>
									</div>
								</div>
								<!-- end extractor name -->
								
								<!-- start extractor url -->
								<div class="j-row">
									<div class="j-span12 j-unit">
										<div class="j-input {{ $errors->has('extractor_url') ? ' j-error-view' : '' }}">
											<label class="j-icon-right" for="extractor_url">
												<i class="icofont icofont-link"></i>
											</label>
											<input type="text" id="extractor_url" name="extractor_url" value="{{old('extractor_url')}}" placeholder="http://example.com">
											<span class="j-tooltip j-tooltip-right-top">Base URL (main link on which you will build extractor)</span>
											@if ($errors->has('extractor_url'))
												<span class="j-error-view">{{ $errors->first('extractor_url') }}</span>
											@endif
										</div>
									</div>
								</div>
								<!-- end extractor url -->
								
								<!-- start response from server -->
								<div class="j-response"></div>
								<!-- end response from server -->
							</div>
							<!-- end /.content -->
							<div class="j-footer">
								<button type="submit" id="submit_btn" onClick="startProcessing()" class="btn btn-primary">Next...</button>
								<button type="reset" class="btn btn-default m-r-20">Reset</button>
							</div>
							<!-- end /.footer -->
						</form>
					</div>
				</div>
			</div>
			<!-- Job application card end -->
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
