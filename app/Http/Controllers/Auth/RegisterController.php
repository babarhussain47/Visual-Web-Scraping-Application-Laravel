<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Lib\SendSMS;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		
        return Validator::make($data, [
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'country' => 'required|string|max:30',
            'state' => 'required|string|max:20',
            'city' => 'required|string|max:50',
            'zip' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|regex:/(^[+0-9]{8,20}$)/|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
		$new_message = new SendSMS();
		
        $user =  User::create([
			'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'country' => $data['country'],
            'state' => $data['state'],
            'zip' => $data['zip'],
            'city' => $data['city'],
            'street' => $data['street_address'],
            'password' => bcrypt($data['password']),
        ]);
		
		$message_body = "Dear ".$user->first_name.",<br>Assalam O Alaikum!<br>Thanks for joining AIMFOX IT SOLUTIONS (SMC-PVT) LTD NETWORK.<br>Your account for handyimport.io is created.
";
		
		
		$new_message->sendVerifyCode($user->phone);
		$new_message->sendSMS($message_body,$user->phone);
		$new_message->sendVerifyEmail($user->id,$user->email);
		// send welcome email and message
		//verification email and message
		
        return $user;
    }
}
