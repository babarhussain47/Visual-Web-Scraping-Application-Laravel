<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extractor;
use App\App;
use App\UserActivity;
use Auth;
class UserAPIController extends Controller
{
	 public function __construct()
    {
        $this->middleware('auth');
    }
	public function listApps()
	{
		$extractors = Extractor::select("ext_id","ext_name")->where("user_id",Auth::user()->id)->get();
		$apps = App::where("user_id",Auth::user()->id)->get();
		return view('client.api.list')->with(['extractors' => $extractors,'apps' => $apps]);
	}
	
	function deleteApp(Request $request){
		$app = App::where(["app_id" => $request->app_id,'user_id' => Auth::user()->id])->first();
		if(count((array)$app) > 0)
		{
			
			$userActivity = new UserActivity();
			$userActivity->user_id =Auth::user()->id;
			$userActivity->act_desc = "App (".$request->app_id.") deleted!";
			$userActivity->save();
			
			$app->delete();
			return "OK";
		}
		else	
			return "Not Deleted";
	}
	function createApp(Request $request){
		$this->validate($request,[
            'app_name' => 'required|min:3|max:30',
            'website' => 'required|min:3|max:30',
            'app_ext' => 'required|min:1|max:10',
        ]);
		
		
		$public_key = 'pk_'.bin2hex(openssl_random_pseudo_bytes(32)).'_hi';
		$private_key = 'sk_'.bin2hex(openssl_random_pseudo_bytes(32)).'_hi';	
		
		$new_app = new App();
		$new_app->app_name = $request->app_name;
		$new_app->app_website = $request->website;
		$new_app->ext_id = $request->app_ext;
		$new_app->user_id = Auth::user()->id;
		$new_app->public_key = $public_key;
		$new_app->private_key = $private_key;
		$new_app->save();
		
			$userActivity = new UserActivity();
			$userActivity->user_id =Auth::user()->id;
			$userActivity->act_desc = "New App (".$new_app->app_id.") added!";
			$userActivity->save();
		return 'Application Added';
	}
	
	
}