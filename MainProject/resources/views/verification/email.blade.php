@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<!-- Job application card start -->
			<div class="card">
				<div class="card-header">
					<h2>
                                Email Verification
                                <small>To Avail any service you need to verify your email address.</small>
					</h2>
				</div>
				<div class="card-block">
					<div class="j-wrapper j-wrapper-640"> 
					 <div class="body">
						@if(Auth::user()->email_verified)
							<div class="alert alert-success">
                                <strong>Congrats!</strong> Your email address is verified now..
                            </div>
							@if(!Auth::user()->phone_verified)
								<div class="alert alert-danger">
                                <strong>Oh snap!</strong> Your phone number {{Auth::user()->phone}} is still not verified, Please verify it by <a href="/verify/phone">Verify Phone</a>.
                            </div>
							@endif
						@else
							<div class="alert alert-danger">
                                <strong>Oh snap!</strong> Your email address is not verified Please click on link received on {{Auth::user()->email}}.
                            </div>
							 <div class="row clearfix">
							<form method="POST" action="{{route('send_email_verification')}}" >
                                {{csrf_field()}}
                                    <div class="form-group {{ $errors->has('verification_code') ? ' has-error' : '' }}">
                                        <div class="form-line">
										Did not received verification code.
                                            
                                        </div>
                                    </div>
                                <button type="submit" onclick="hideFn(this)" id="btnsubmit" class="btn btn-primary m-t-15 waves-effect pull-left">Resend Now</button>
                                <label id="sending" class="label label-info m-t-15 waves-effect pull-left" style="display:none;">Sending...</label>
							 </form>
							 
						@endif                   
                        </div>
                    </div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
