@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<!-- Job application card start -->
			<div class="card">
				<div class="card-header">
								<h2>
                                Mobile Number Verification
                                <small>Please verifiy your phone number.</small>
                            </h2>
				</div>
				<div class="card-block">
					<div class="j-wrapper j-wrapper-640"> 
					
                        <div class="body">
						@if(Auth::user()->phone_verified)
							<div class="alert alert-success">
                                <strong>Congrats!</strong> Your phone is verified now..
                            </div>
							@if(!Auth::user()->email_verified)
								<div class="alert alert-danger">
                                <strong>Oh snap!</strong> Your email is still not verified, Please verify it by <a href="/verify/email">Verify Email</a>.
                            </div>
							@endif
						@else
							<div class="alert alert-danger">
                                <strong>Oh snap!</strong> Your phone is not verified Please enter verification code received on {{Auth::user()->phone}}.
                            </div>
						  <div class="row clearfix">	
							<div class="col-md-12">
								<form method="POST" action="{{route('match_verification_phone')}}" >
									{{csrf_field()}}
									
										<div class="form-group {{ $errors->has('verification_code') ? ' has-error' : '' }}">
											<div class="form-line">
											 <div class="col-md-6">
											Already received code please enter here
											
												<input type="text" class="form-control" name ="verification_code" placeholder="547885" value="{{ old('verification_code') }}"   tabindex="1">
												
														<span class="help-block">
														@if ($errors->has('verification_code'))
															<strong>{{ $errors->first('verification_code') }}</strong>
														
														@endif
														</span>
													
											</div> 
											<div class="col-md-4"> 
											<button type="submit" onclick="hideFn(this)" id="btnsubmit" class="btn btn-success m-t-15 waves-effect pull-left">Verify Now</button>
											<label id="sending" class="label label-info m-t-15 waves-effect pull-right" style="display:none;">Sending...</label>
										</div>
											</div>
										</div>
										
								 </form>
							</div> 
							 </div>
							 <div class="row clearfix">
							 <div class="col-md-12">
								<form method="POST" action="{{route('send_phone_verification')}}" >
									{{csrf_field()}}
										<div class="form-group {{ $errors->has('verification_code') ? ' has-error' : '' }}">
										
											<div class="form-line">
											Did not received verification code request new code.
												
											</div>
										</div>
									<button type="submit" onclick="hideFn(this)" id="btnsubmit" class="btn btn-primary m-t-15 waves-effect pull-left">Resend Now</button>
									<label id="sending" class="label label-info m-t-15 waves-effect pull-left" style="display:none;">Sending...</label>
								 </form>
							 </div>
						@endif                   
                        </div>
                    </div>
					</div>
				</div>
			</div>
		</div>
	</div>


@endsection
