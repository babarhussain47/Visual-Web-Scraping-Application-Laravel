@extends('layouts.public')
<?php
use App\Package_Subscribed;
?>


@section('body-content')

@if(count($subscribed) > 0)

	<div class="row clearfix">
	

		@foreach($subscribed as $i=>$subsc)
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
						
						<?php
							$package = Package_Subscribed::where('packages.package_id',$subsc->package_id)
							->join('packages','packages.package_id','=','packages_subscribed.package_id')
							->where('user_id',Auth::user()->id)
							->first();
							
							$timestamp = strtotime($subsc->created_at);
							$currenttime = time();
							$current_period = $currenttime-$timestamp;
							$numDays = floor(abs($current_period)/60/60/24);
							try{
							$days_left = decrypt($package->package_expiry)-$numDays;
							}catch(Exception $e)
							{
								$days_left = 0;
							}
							if($days_left <= 0)
							{
								$days_left  = 0;
							}
						 
							try{
								echo decrypt($package->package_name) , " <b>",$subsc->subscription_id,"</b>";
							}
							catch(Exception $e)
							{
								echo $e->getMessage() ;
							}
						?>
										
						</h2>		
					</div>
					<div class="body">
					<table class="table table-hover table-bordered table-responsive">
                                <tbody>
                                    <tr>
                                        <th>Price</th>
                                        <td>
										<?php 
											try{
												echo decrypt($package->package_rate);
											}
											catch(Exception $e)
											{
												echo $package->package_rate ;
											}
										?>
										<span id="currency"><b>USD</b><span></td>
                                    </tr>
                                    <tr>
                                        <th>Messages Allowed</th>
                                        <td>
										<?php 
											try{
												echo decrypt($package->package_total_limit);
											}
											catch(Exception $e)
											{
												echo $package->package_total_limit ;
											}
										?>
										 SMS</td>
                                    </tr>
                                    <tr>
                                        <th>Daily Limit Remaining</th>
                                        <td>
										<?php 
											try{
												if(decrypt($subsc->expired) ==0)
												echo decrypt($subsc->package_limit_remaining);
												else 
													echo "0";
											}
											catch(Exception $e)
											{
												echo $subsc->package_limit_remaining ;
											}
										?>
										 SMS</td>
                                    </tr>
                                        <th>Total Limit Remaining</th>
                                        <td>
										<?php 
											try{
												if(decrypt($subsc->expired) ==0)
													echo decrypt($subsc->package_total_limit_remaining);
												else 
													echo "0";
											}
											catch(Exception $e)
											{
												echo $subsc->package_total_limit_remaining ;
											}
										?>
										 SMS</td>
                                    </tr>
                                    <tr>
                                        <th>Expiry</th>
										@if(decrypt($subsc->expired) == 1)
											<td><label class="label label-danger">Expired</label></td>
										@else	
											<td>{{$days_left}} Days Left</td>
										@endif
                                    </tr>
									
                                </tbody>
                            </table>
					</div>
				</div>
			</div>
        @endforeach
	</div>


@else
	<div class="alert alert-danger">
		<strong>Warning!</strong> Currently you are not subscribed to any package.
		.
	</div>
@endif      
@endsection
