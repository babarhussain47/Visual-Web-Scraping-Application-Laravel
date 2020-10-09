<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Auth;
use App\Package;
use App\PackageSubscribed;
class PackageController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
	function getPackages()
	{
		$packages =  Package::get();
		
		return view('client.packages.list')->with("packages",$packages);
	}
	function getSubscriptions()
	{
		$packages =  PackageSubscribed::join("packages","p_id","=","sp_id")->where("u_id",Auth::user()->id)->get();
		
		return view('client.packages.subscriptions')->with("packages",$packages);
	}
	function orderPage(Request $request)
	{
		$package =  Package::where('p_id',$request->p_id)->first();
		
		return view('client.packages.order_page')->with("package",$package);
	}
	
}
