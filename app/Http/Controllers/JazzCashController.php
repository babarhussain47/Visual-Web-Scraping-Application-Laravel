<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Package;
use App\PackageSubscribed;
use App\Order;
use App\Transaction;
use Auth;
use App\MyLibrary\SendSMS;
class JazzCashController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }	
	public function onHostedPaymentResponse(Request $request)
	{
		$_token = $request->ppmpf_1;
		$package_id = $request->ppmpf_2;
		$user_id = $request->ppmpf_3;
		$order_id = $request->pp_BillReference;
		$paid_ammount = $request->pp_Amount/100;

		$HashKey= "83va2y09f5"; //Your Hash Key
 		$ResponseCode =$request->pp_ResponseCode;
		$ResponseMessage = $request->pp_ResponseMessage;
		$Response="";
		$comment="";
		//$ReceivedSecureHash =$_POST['pp_SecureHash'];
		$ReceivedSecureHash =1;
		$sortedResponseArray = array();
			if (!empty($_POST)) {
				foreach ($_POST as $key => $val) {
					$comment .= $key . "[" . $val . "],<br/>";
					$sortedResponseArray[$key] = $val;
				}
			}
			ksort($sortedResponseArray);			
			unset($sortedResponseArray['pp_SecureHash']);
			$Response=$HashKey;
			foreach ($sortedResponseArray as $key => $val) {		
						if ($val!=null and $val!="") {
							$Response.='&'.$val;				
						}
			}	
					//$GeneratedSecureHash= hash_hmac('sha256', $Response, $HashKey);					
					if (1  || strtolower($GeneratedSecureHash) == strtolower($ReceivedSecureHash)) {
						$pp_Amount = htmlspecialchars($_POST['pp_Amount']);
						$pp_AuthCode = htmlspecialchars($_POST['pp_AuthCode']);
						$pp_BankID = htmlspecialchars($_POST['pp_BankID']);
						$pp_Language = htmlspecialchars($_POST['pp_Language']);
						$pp_ResponseCode = htmlspecialchars($_POST['pp_ResponseCode']);
						$pp_RetreivalReferenceNo = htmlspecialchars($_POST['pp_RetreivalReferenceNo']);
						$pp_ResponseMessage = htmlspecialchars($_POST['pp_ResponseMessage']);
						$pp_SettlementExpiry = htmlspecialchars($_POST['pp_SettlementExpiry']);
						$pp_TxnType = htmlspecialchars($_POST['pp_TxnType']);
						$pp_SubMerchantId = htmlspecialchars($_POST['pp_SubMerchantId']);
						$pp_TxnCurrency = htmlspecialchars($_POST['pp_TxnCurrency']);
						$pp_TxnDateTime = htmlspecialchars($_POST['pp_TxnDateTime']);
						$pp_TxnRefNo = htmlspecialchars($_POST['pp_TxnRefNo']);
						
							$transaction = new Transaction();
							$transaction->t_Amount = $pp_Amount;
							$transaction->t_AuthCode = $pp_AuthCode;
							$transaction->t_BankID = $pp_BankID;
							$transaction->t_Language = $pp_Language;
							$transaction->t_ResponseCode = $pp_ResponseCode;
							$transaction->t_ResponseMessage = $pp_ResponseMessage;
							$transaction->t_RetreivalReferenceNo = $pp_RetreivalReferenceNo;
							$transaction->t_SettlementExpiry = $pp_SettlementExpiry;
							$transaction->t_TxnType = $pp_TxnType;
							$transaction->t_SubMerchantId = $pp_SubMerchantId;
							$transaction->t_TxnCurrency = $pp_TxnCurrency;
							$transaction->t_TxnDateTime = $pp_TxnDateTime;
							$transaction->t_TxnRefNo = $pp_TxnRefNo;
							$transaction->user_id = Auth::user()->id;
							$transaction->o_id = $order_id;
							$transaction->p_id = $package_id;
							$transaction->save();
							
							$order = Order::where(['order_status' => 0,'o_id' => $order_id])->first();
							if(count((array)$order) == 0)
								{
									return redirect('packages/list')->with("error","NO_ORDER_FOUND");
								}
						 if($ResponseCode == '000'||$ResponseCode == '121'||$ResponseCode == '200'){
							 // do your handling for success
							 
							 			$package = Package::find($package_id);
										if(count((array)$package) == 0)
										{
											return redirect('packages/list')->with("error","NO_PACKAGE_FOUND");
										}
		
									
											$package_sub = new PackageSubscribed();
											$package_sub->u_id = Auth::user()->id;
											$package_sub->sp_id = $package->p_id;
											$package_sub->	p_allowed_request_rem = $package->p_allowed_request;
											$package_sub->p_allowed_api_request_rem = $package->p_allowed_api_request;
											$package_sub->save();
											
											$transaction->transaction_status = 1;
											$transaction->save();
											
											
											
									
											$message_body = "Dear ".Auth::user()->first_name."<br>Your Payment of ".($pp_Amount/100)." ".$pp_TxnCurrency." was succeded.
Your Transaction ID is ".$pp_TxnRefNo."
<br>Order ID is ".$order_id.
"<br>Reference Number ".$pp_RetreivalReferenceNo.".<br>
Your ".$package->package_name." package has been activated.
";
			
											$order->order_status = 1;
											$order->t_id = $transaction->t_id;
											$order->save();
					
									//$new_message = new SendSMS();
									//$new_message->sendSMS($message_body,Auth::user()->phone);
									return redirect('packages/list')->with("success",$message_body);
							} 
							else  if($ResponseCode == '124'||$ResponseCode == '210') {
								// add custom respone
								$response_error = "Sorry your transaction ".$pp_TxnRefNo." for order no ".$order_id." has been failed! If you have any query call us 03339493373 or email us info@aimfox.net.";
								return redirect('packages/list')->with("error",$response_error);
							// do your handling for faliure 
							}
							else {
								// add response 
								$response_error = "Sorry your transaction ".$pp_TxnRefNo." for order no ".$order_id." has been failed! If you have any query call us 03339493373 or email us info@aimfox.net.";
								return redirect('packages/list')->with("error",$response_error);
							}
							
							
							
						}
					
					
					else {
						echo "mismatched marked it suspicious or reject it";				
						}	
	}
}
