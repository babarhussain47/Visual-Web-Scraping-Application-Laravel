@extends('layouts.app')

@section('content')
<?php
	use App\Order;
	use App\MyLibrary\SendSMS;
	
	$order = Order::where(['user_id' => Auth::user()->id,'order_status' => 0,'p_id' => $package->p_id])->first();
	
	$dolar_rate = json_decode(file_get_contents("https://free.currencyconverterapi.com/api/v6/convert?q=USD_PKR&compact=y&apiKey=073ca98ce20850f77cf5"),true);
	$dolar_rate = round($dolar_rate["USD_PKR"]["val"]);
	$send_sms = false;
	if(count($order) == 0)
	{
		$order = new Order();
		$send_sms = false;
	}
	$order->user_id = Auth::user()->id;
	$order->p_id = $package->p_id;
	$order->t_id = 0;
	$order->save();

	if($send_sms){
	$message_body = "Dear ".Auth::user()->first_name.",
We have Received Your order to subscribe ".$package->package_name.", Please make payment to continue.
Your Order Id is ".$order->o_id;
		$new_message = new SendSMS();
		$new_message->sendSMS(strip_tags($message_body),Auth::user()->phone);
	}
	
	
	// Mobilink Payment Method Integration	
		$MerchantID    = "MC7623"; //Your Merchant from transaction Credentials
		$Password      = "8v031w7y55"; //Your Password from transaction Credentials
		$ReturnURL     = "https://dev.handyimport.io/payment-success"; //Your Return URL
		$HashKey = "b1081z03b3";//Your HashKey integrity salt from transaction Credentials	
		$PostURL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform";
 
		date_default_timezone_set("Asia/karachi");
		$package_rate = $package->p_price*$dolar_rate;
		$Amount = $package_rate*100; //Last two digits will be considered as Decimal
		$BillReference = $order->o_id; // Order ID
		$Description = "Package Subscription ".$package->p_name;
		$Language = "EN";
		$TxnCurrency = "PKR";
		$TxnDateTime = date('YmdHis') ;
		$TxnExpiryDateTime = date('YmdHis', strtotime('+8 Days'));
		$TxnRefNumber = "TXN".date('YmdHis');
		$TxnRefNumber2 = "T".date('YmdHis');
		$TxnType = "DD";
		$Version = '1.1';
		$SubMerchantID = "";
		$DiscountedAmount = "";
		$DiscountedBank = "";
		$ppmpf_1=csrf_token();
		$ppmpf_2=$package->p_id;
		$ppmpf_3=Auth::user()->id;
		$ppmpf_4="";
		$ppmpf_5="";

		$HashArray=[$Amount,$BillReference,$Description,$DiscountedAmount,$DiscountedBank,$Language,$MerchantID,$Password,$ReturnURL,$TxnCurrency,$TxnDateTime,$TxnExpiryDateTime,$TxnRefNumber,$TxnType,$Version,$ppmpf_1,$ppmpf_2,$ppmpf_3,$ppmpf_4,$ppmpf_5];
		$HashArray1=[$Amount,$BillReference,$Description,$DiscountedAmount,$DiscountedBank,$Language,$MerchantID,$Password,$ReturnURL,$TxnCurrency,$TxnDateTime,$TxnExpiryDateTime,$TxnRefNumber,"MWALLET",$Version,$ppmpf_1,$ppmpf_2,$ppmpf_3,$ppmpf_4,$ppmpf_5];
		
		$SortedArray=$HashKey;
		for ($i = 0; $i < count($HashArray); $i++) { 
			if($HashArray[$i] != 'undefined' AND $HashArray[$i]!= null AND $HashArray[$i]!="" )
			{
				$SortedArray .="&".$HashArray[$i];
			}	
		}
		$SortedArray1=$HashKey;
		for ($i = 0; $i < count($HashArray1); $i++) { 
			if($HashArray1[$i] != 'undefined' AND $HashArray1[$i]!= null AND $HashArray1[$i]!="" )
			{
				$SortedArray1 .="&".$HashArray1[$i];
			}	
		}
		$Securehash = hash_hmac('sha256', $SortedArray, $HashKey); 	
		$Securehash1 = hash_hmac('sha256', $SortedArray1, $HashKey); 	
?>				
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
		
		
		<div class="row clearfix">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="card">
					<div class="card-header">
						<h2>
						Package Details
						</h2>		
					</div>
                        <div class="card-block">
                            
						   <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Price</th>
                                        <td> <span class="current-price-{{$i}}" id="cp">{{$package->p_price}}</span>
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
						   
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="card">
                        <div class="card-header">
						<h2>Online Payment</h2>
                        </div>
                        <div class="card-block">
								<form method="post" action="{{$PostURL}}"/>  					
								<!--  OK --><input type="hidden" name="pp_Version" value="{{$Version}}" /> 
								<!--  OK --><input type="hidden" id="pp_TxnType" name="pp_TxnType" value="{{$TxnType}}" />
								<!--  OK --><input type="hidden" name="pp_Language" value="{{$Language}}" />
								<!--  OK --><input type="hidden" name="pp_MerchantID" value="{{$MerchantID}}" />
								<!--  OK --><input type="hidden" name="pp_SubMerchantID" value="{{$SubMerchantID}}" />
								<!--  OK --><input type="hidden" name="pp_Password" value="{{$Password}}" />

								<!--  OK --><input type="hidden" name="pp_TxnRefNo" id="pp_TxnRefNo" value="{{$TxnRefNumber}}"/>
								<!--  OK --><input type="hidden" name="pp_Amount" value="{{$Amount}}" />
								<!--  OK --><input type="hidden" name="pp_TxnCurrency" value="{{$TxnCurrency}}"/>
								<!--  OK --><input type="hidden" name="pp_TxnDateTime" value="{{$TxnDateTime}}" />
								<!--  OK --><input type="hidden" name="pp_BillReference" value="{{$BillReference}}" />
								<!--  OK --><input type="hidden" name="pp_Description" value="{{$Description}}" />
								<input type="hidden" id="pp_DiscountedAmount" name="pp_DiscountedAmount" value="{{$DiscountedAmount}}">
								<input type="hidden" id="pp_DiscountBank" name="pp_DiscountBank" value="{{$DiscountedBank}}">
								<!--  OK --><input type="hidden" name="pp_TxnExpiryDateTime" value="{{$TxnExpiryDateTime}}" />
								<!--  OK --><input type="hidden" name="pp_ReturnURL" value="{{$ReturnURL}}" />
								<!--  OK --><input type="hidden" id="hash" name="pp_SecureHash" value="{{$Securehash}}" />
								<!--  OK --><input type="hidden" name="ppmpf_1" value="{{$ppmpf_1}}" />
								<!--  OK --><input type="hidden" name="ppmpf_2" value="{{$ppmpf_2}}" />
								<!--  OK --><input type="hidden" name="ppmpf_3" value="{{$ppmpf_3}}" />
								<!--  OK --><input type="hidden" name="ppmpf_4" value="{{$ppmpf_4}}" />
								<!--  OK --><input type="hidden" name="ppmpf_5" value="{{$ppmpf_5}}" />
								
								<input type="hidden" id="hash1" value="{{$Securehash}}" />
								<input type="hidden" id="hash2" value="{{$Securehash1}}" />
								<input type="hidden" id="pp_TxnRefNo00" value="{{$TxnRefNumber}}" />
								<input type="hidden" id="pp_TxnRefNo11" value="{{$TxnRefNumber2}}" />
								Select Option
								<select onchange="paymentMethod(this)">
									<option value="DD">Credit Card</option>
									<option value="MWALLET">JazzCash Account</option>
								</select>
								<button id="submit" class="btn btn-sm btn-success" onclick="$(this).hide()" type="submit">Pay Now</button>
							</form>
                        </div>
                    </div>
                </div>
            </div>
			
@endsection

@section('body-js')



    <!-- Input Mask Plugin Js -->
    <script src="{{asset('plugins/jquery-inputmask/jquery.inputmask.bundle.js')}}"></script>
<script>

$(function () {
    var selector = document.getElementById("ccNo");
	var im = new Inputmask("9999 9999 9999 9999", { placeholder: '____ ____ ____ ____' });
	im.mask(selector);
});

function paymentMethod(value)
{
	$("#pp_TxnType").val(value.value);
	if(value.value == "DD")
	{
		$("#pp_TxnRefNo").val($("#pp_TxnRefNo00").val());
		$("#hash").val($("#hash1").val());
	}
	else if(value.value == "MWALLET")
	{
		$("#pp_TxnRefNo").val($("#pp_TxnRefNo11").val());
		$("#hash").val($("#hash2").val());
	}

}
function convertCurrency(sel)
{
	var name = sel.value;
	var url="https://free.currencyconverterapi.com/api/v6/convert?q="+name+"&compact=y&apiKey=073ca98ce20850f77cf5";
	$.get(url,{},function(data){
		obj = data[name];
		
		var cp = $("#cp").text();
		var np = (obj.val)*cp;
		
		   np = Math.round(np);
		if(name == "USD_PKR")
		{
			
			$("#cp").text(np);
			$(".current-currency").text("PKR");
		}
		else
		{
			$("#cp").text(np);
			$(".current-currency").text("USD");
		}
	});
}
</script>
	
@endsection
