<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Auth;
use App\VerifyUser;
use App\Lib\SendSMS;
use App\Mail\Verification;
use Mail;
class VerifyController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
	function showPhoneVerify(Request $request)
	{
		return view('verification.phone');
	}
	
	public function sendVerifyPhone()
	{
		$already_sent = DB::table('verify')
				    ->where('phone', '=', Auth::user()->phone)
    				    ->first();
		if(count($already_sent) > 0){

					$date = substr($already_sent->created_at,0,10);
					$tme = substr($already_sent->created_at,11,19);
					list($year,$month,$day) = explode('-', $date);
					list($hr,$min,$sec) = explode(':', $tme);
					$time = mktime($hr,$min,$sec,$month,$day,$year);
					$now = time();
					$difference = $now - $time;
					$minutes = intval($difference / 60);

					 if($minutes >= 0 && $minutes < 5)
					{
						$mss = "Code already sent please wait ".(4-$minutes)." minutes and ".((($minutes*60)+60)-($difference))." secs to request new verification code.";
						return redirect()->back()->with("error",$mss);
					}
					else
					{
						DB::table('verify')->where('phone', '=',  Auth::user()->phone)->delete();
					}
				}
			$new_message = new SendSMS();
			$new_message->sendVerifyCode(Auth::user()->phone);
		
		return redirect()->back()->with("success","Verification Code Sent successfully !");
	}
	
	
    public function isValidPhoneCode(Request $request)
	{
		$get_code = DB::table('verify')->where([['code', '=', $request->verification_code],    ['phone', '=', Auth::user()->phone]])->first();
		if(count($get_code) > 0){
       		Auth::user()->phone_verified = true;
			Auth::user()->save();
		if(Auth::user()->email_verified && Auth::user()->phone_verified)
				{
					Auth::user()->active = 1;
					Auth::user()->save();
				}
			return redirect()->back()->with("success","Phone Verifed Successfully !");
		}
		else
			return redirect()->back()->with("error","Invalid Verify Code !"); 	
	}
	
	function showEmailVerify(Request $request)
	{
		return view('verification.email');
	}
	
    public function isEmailValid($token)
	{

		$verifyUser = VerifyUser::where('token', $token)->first();
			if(isset($verifyUser)){
				$user = Auth::user();
				if(!$user->email_verified) {
					$user->email_verified = 1;
					$user->save();
					if($user->email_verified)
					{
						$user->active = 1;
						$user->save();
					}
					return redirect('verify/email')->with("success","Successfully Verified !");
				}
		}
		return redirect('/verify/email')->with("error","Invalid Token Provided Please Retry !");

	}
	
	 public function sendVerifyEmail()
	{
		
		$token = str_random(40);
        $verifyUser = VerifyUser::create([
            'user_id' => Auth::user()->id,
            'token' => $token
        ]);
 
        Mail::to(Auth::user()->email)->send(new Verification($token));
		
		return redirect()->back()->with("success","Verification Code Sent successfully !");
	}
}
