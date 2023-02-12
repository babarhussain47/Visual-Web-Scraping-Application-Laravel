<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extractor;
use App\UserActivity;
use App\ExtractorAnalytic;
use Auth;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	 
	 public function getAnalytics()
    {
		//->select(DB::raw('YEAR(date) as year'), DB::raw('sum(amount) as total'))
   // ->groupBy(DB::raw('YEAR(date)') )
		$extractorAnalytic = ExtractorAnalytic::where('user_id',Auth::user()->id)->
		select(DB::raw('DATE(created_at) as created_at'),
		DB::raw('sum(rows_extracted) as rows_extracted'),
		DB::raw('sum(total_requests) as total_requests'),
		DB::raw('sum(column_extracted) as column_extracted'),
		DB::raw('count(column_extracted) as counts'))
		 ->groupBy(DB::raw('DATE(created_at)') )
		->get();
		return $extractorAnalytic ;
	}
    public function test_graphs()
    {
		$data['total_extractors'] = Extractor::where(['user_id' => Auth::user()->id])->count();
		$data['auto_run'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','<>','no_repeat')->count();
		$data['auto_run_daily'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','daily')->count();
		$data['auto_run_weekly'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','weekly')->count();
		$data['auto_run_monthly'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','monthly')->count();
		$data['auto_run_yearly'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','yearly')->count();
		$data['draft_extractors'] = Extractor::where('user_id',Auth::user()->id)->where('ext_draft',1)->count();
		
		$data['user_activities'] = UserActivity::where(['act_type'=>0,'user_id' => Auth::user()->id])->limit(4)->orderBy('created_at','desc')->get();
		$data['user_activities_login'] = UserActivity::where(['act_type'=>1,'user_id' => Auth::user()->id])->limit(4)->orderBy('created_at','desc')->get();
		
        return view('test')->with('data',$data);
    }
    public function index()
    {
		$data['total_extractors'] = Extractor::where(['user_id' => Auth::user()->id])->count();
		$data['auto_run'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','<>','no_repeat')->count();
		$data['auto_run_daily'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','daily')->count();
		$data['auto_run_weekly'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','weekly')->count();
		$data['auto_run_monthly'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','monthly')->count();
		$data['auto_run_yearly'] = Extractor::where('user_id',Auth::user()->id)->where('ext_run_type','yearly')->count();
		$data['draft_extractors'] = Extractor::where('user_id',Auth::user()->id)->where('ext_draft',1)->count();
		
		$data['user_activities'] = UserActivity::where(['act_type'=>0,'user_id' => Auth::user()->id])->limit(4)->orderBy('created_at','desc')->get();
		$data['user_activities_login'] = UserActivity::where(['act_type'=>1,'user_id' => Auth::user()->id])->limit(4)->orderBy('created_at','desc')->get();
		
        return view('home')->with('data',$data);
    }
}
