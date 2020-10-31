<?php namespace App\Lib;

use Illuminate\Http\Request; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Verify;
use App\VerifyUser;
use Mail;
use App\Mail\Verification;
class SendSMS
{
	function sig($route) 
	{	
		// Client Secret Key 
		$client_secret = 'cs_efa1f90236b8fa98acd92808d7ca3fc813e866855894dd70d3aafafccea50a29';
		// Client Key 
		$client_key = 'ck_f641de8368d99dce320da723da77b6b5676bdd68cbf51cd100717abe655df9c9';
		$salt = md5($route);
		$output = md5($client_secret.$salt.$client_key);
		return $output;
	}
	
	public function getAccessToken()
	{
		$link = "http://api.bulksmswebapi.com/web-api/v1/auth/validate";
		$parms = "?grant_type=access_token&client_key=ck_f641de8368d99dce320da723da77b6b5676bdd68cbf51cd100717abe655df9c9&sig=".$this->sig('/web-api/v1/auth/validate');
		
		$client = new Client(); //GuzzleHttp\Client
		$url = $link.$parms;
		$result = $client->get($url);
		$response =  $result->getBody();
		$response = json_decode($response);
		return $response->access_token;
	}
	public function sendVerifyCode($to_number)
	{
		// Don't Encrypt message data in this method
		
		$new_verify = new Verify();
		$new_verify->phone = $to_number;
		$verify_code = mt_rand(1000000, 9999999);
		$new_verify->code = $verify_code;
		$new_verify->save();
		
		$uid = 1 ;
		$sender_number = "03339493373";
		$sender_name = "Aimfox Verify";
		$type = "Verify";
		$message_body = "Your Aimfox IT Solutions Verification code is: ".$verify_code.".<br>Please don't share this code with any one.";
		if(preg_match('/^(03[0-4][0-9])[0-9]{7}$/',$to_number))
			{
				return $this->sendSMS($message_body,$to_number);
			}
			
		$res = array('type' => 'success','response'=>'Message Sent Success!');	
		return $res;

	}
	
	  public function sendSMS($message_body,$to)
    {	
		$message_body = str_replace('\n','<br>',$message_body);
		$link = "http://api.bulksmswebapi.com/web-api/v1/user/send-sms";
		$contact = json_encode(array("1" => $to));
		$token = $this->getAccessToken();
		$parms = "?grant_type=send_sms&access_token=$token&send_type=number&message_body=$message_body&contacts=$contact";
		$url = $link.$parms;
		$client = new Client(); //GuzzleHttp\Client
		$result = $client->get($url);
		return $result->getBody();
    }

	 public function sendVerifyEmail($user_id,$email)
	{
		
		$token = str_random(40);
        $verifyUser = VerifyUser::create([
            'user_id' => $user_id,
            'token' => $token
        ]);
 
        Mail::to($email)->send(new Verification($token));
		
		return array("success","Verification Code Sent successfully !");
	}
}