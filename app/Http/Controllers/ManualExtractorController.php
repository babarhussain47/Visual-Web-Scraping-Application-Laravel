<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extractor;
class ManualExtractorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		 if(isset($_GET['url']))
		{
			$url = $this->cleanBaseURL($_GET['url']);
			$extractor = Extractor::where(['ext_url' =>  $url,'user_id' => '1'])->first();
			
			if(count($extractor) == 0 )
			{
				$extractor = new Extractor();
				$extractor->ext_url = $url;
				$extractor->ext_data = json_encode(array());
				$extractor->user_id = '1';
				$extractor->save();
			}
			return view('extractors.manual')->with(['url' => $url ,'extractor' => $extractor]); 
		}
        return 'NO URL PROVIDED'; 
    }
    public function test()
    {
		if(isset($_GET['url']))
		{
			$url = $this->cleanBaseURL($_GET['url']);
			$extractor = Extractor::where(['ext_url' => $url ,'user_id' => '1'])->first();
			
			if(count($extractor) == 0 )
			{
				 return 'Extractor not found';
			}
			return view('extractors.manual-test')->with(['url' => $url,'extractor' => $extractor]); 
		}
        return 'NO URL PROVIDED';
    }
	
	
	public function temporarySessionGenerator()
    {
        
    }
	
	public function cleanBaseURL($url)
    {
		$url = strtolower($url);
		$url = str_replace("https://","",$url);
		$url = str_replace("http://","",$url);
		
		if(substr($url,-1,1) == "/")
		{
			$url = substr($url,0,strlen($url)-1);
		}
		
        return $url;
    }
}
