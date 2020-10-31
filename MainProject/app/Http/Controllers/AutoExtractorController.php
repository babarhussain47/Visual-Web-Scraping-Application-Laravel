<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extractor;
class AutoExtractorController extends Controller
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
			$extractor = Extractor::where(['ext_url' => $_GET['url'] ,'user_id' => '1'])->first();
			
			if(count($extractor) == 0 )
			{
				$extractor = new Extractor();
				$extractor->ext_url = $_GET['url'];
				$extractor->ext_data = json_encode(array());
				$extractor->user_id = '1';
				$extractor->save();
			}
			return view('extractors.auto')->with(['url' => $_GET['url'] ,'extractor' => $extractor]); 
		}
        return 'NO URL PROVIDED';
    }
	
	
	public function temporarySessionGenerator()
    {
        
    }
}
