
@extends('layouts.app')

@section('content')


@if(count($packages) > 0)
		<!-- Masked Input -->
		<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="alert alert-success">

							Change Currency <small>All currency exchange rates updated every 30 minutes</small>
							<select onchange="convertCurrency(this)">
								<option value="PKR_USD"> USD </option>
								<option value="USD_PKR"> PKR </option>
							</select>
						</div>
                </div> 
		</div>
		
	<div class="row">
	
		<div hidden class="total_packages">{{count($packages)}}</div>
		@foreach($packages as $i=>$package)
		
		
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="card">
					<div class="card-header">
						<h2>
						{{$package->p_name}}
						</h2>		
					</div>
					<div class="card-block">
					
					<table class="table">
                                <tbody>
                                    <tr>
                                        <th>Price</th>
                                        <td> <span class="current-price-{{$i}}">{{$package->p_price}}</span>
										<span class="current-currency" id="currency"><b>USD</b><span></td>
                                    </tr>
									
                                    <tr>
                                        <th>Allowed Request</th>
                                        <td>{{$package->p_allowed_request}}</td>
                                    </tr>
									
									 <tr>
                                        <th>Allowed Extractor</th>
                                        <td>{{$package->p_allowed_extractors}}</td>
                                    </tr>
									
                                    <tr>
                                        <th>API</th>
                                        <td>@if($package->p_allowed_api)<label class="label label-success">Yes</label>@else<label class="label label-danger">NO</label>@endif</td>
                                    </tr>
									@if($package->p_allowed_api)
										<tr>
                                        <th>Allowed API Calls</th>
                                        <td>{{$package->p_allowed_api_request}}</td>
                                    </tr>
									@endif
									 <tr>
                                        <th>Data To Email </th>
                                       <td>@if($package->p_email_data)<label class="label label-success">Yes</label>@else<label class="label label-danger">NO</label>@endif</td>
                                    </tr>
									 <tr>
                                        <th>Data To Website </th>
                                       <td>@if($package->p_post_data)<label class="label label-success">Yes</label>@else<label class="label label-danger">NO</label>@endif</td>
                                    </tr>
									 <tr>
                                        <th>Auto Scheduler </th>
                                       <td>@if($package->p_auto_schedule)<label class="label label-success">Yes</label>@else<label class="label label-danger">NO</label>@endif</td>
                                    </tr>
									
									 <tr>
                                        <th>Total Column/Extractor</th>
                                        <td>{{$package->p_allowed_column}}</td>
                                    </tr>
									 <tr>
                                        <th>Total Rows/Extractor</th>
                                        <td>{{$package->p_allowed_row}}</td>
                                    </tr>
									 <tr>
                                        <th>Validity</th>
                                        <td>{{$package->p_validity}} Days</td>
                                    </tr>
                                </tbody>
					</table>
								<span class="btn btn-info" id="package_{{$package->p_id}}" onclick="submitForm(this)">Place Order</span>
							
							<?php ?>
								<form method="GET" action="{{route('order_page')}}" id="package_{{$package->p_id}}_form">
									{{csrf_field()}}
									<input type="hidden" value="{{$package->p_id}}" name="p_id">
								</form><?php ?>	
								
					</div>
				</div>
			</div>
        @endforeach
	</div>


@else
	<div class="alert alert-warning">
		<strong>Warning!</strong> Currently we are not offering any package yet, Please visit again.
		.
	</div>
@endif      
@endsection

	<script>
	
function convertCurrency(sel)
{
	var name = sel.value;
	
	var url="https://free.currencyconverterapi.com/api/v6/convert?q="+name+"&compact=y&apiKey=073ca98ce20850f77cf5";
	$.get(url,{},function(data){
		obj = data[name];
		var len = $(".total_packages").text();
		for(var i=0 ;i < len ;i++)
		{
			var cp = $(".current-price-"+i).text();
			
			var np = (obj.val)*cp;
			
			np = Math.round(np);
			if(name == "USD_PKR")
			{
				
				$(".current-price-"+i).text(np);
				$(".current-currency").text("PKR");
			}
			else
			{
				$(".current-price-"+i).text(np);
				$(".current-currency").text("USD");
			}
		}
	});
}
	
		function submitForm(obj)
		{
			$("#"+obj.id).hide();
			var id = obj.id+"_form";
			// Submit a form
			document.getElementById(id).submit();
		}
	</script>

@section('body-js')

@endsection