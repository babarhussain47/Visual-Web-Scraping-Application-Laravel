<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extractor;
use App\Handyimport\HTTPRequest;
use App\Handyimport\Ping;
use App\Handyimport\PackageDetail;
use Auth;
use App\User;
use App\ExtractorAnalytic;
use Illuminate\Support\Facades\Storage;
use App\Handyimport\ProcessDocument;
use App\Handyimport\ProcessDocumentDOM;
use App\Mail\HandyImportExtractedData;
use KubAT\PhpSimple\HtmlDomParser;
use Mail;
use App\UserActivity;
class UserExtractorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	protected $httpRequest;
    public function __construct()
    {
		$this->httpRequest = new HTTPRequest('');
        $this->middleware('auth');
    }

    /**
     * Show the new Extractor Creating page.
     */
	 
	 
   function addNewShow()
   {
	   return view('client.extractor.new');
   }
   
    /**
     * Show the new Extractors list.
     */
	 
	 
   function listExtractors(Request $request)
   {
	    
		if(isset($request->t) && $request->t == "all")
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id ])->get();
		}
		else if(isset($request->t) && $request->t == "draft")
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id,'ext_draft' => 1 ])->get();
		}
		else if(isset($request->t) && $request->t == "active")
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id,'ext_draft' => 0 ])->get();
		}
		else if(isset($request->t) && $request->t == "auto")
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id])->where('ext_run_type','<>','no_repeat')->get();
		}
		else if(isset($request->r) && $request->r == "daily")
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id])->where('ext_run_type','=','daily')->get();
		}
		else if(isset($request->r) && $request->r == "weekly")
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id])->where('ext_run_type','=','weekly')->get();
		}
		else if(isset($request->r) && $request->r == "monthly")
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id])->where('ext_run_type','=','monthly')->get();
		}
		else if(isset($request->r) && $request->r == "yearly")
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id])->where('ext_run_type','=','yearly')->get();
		}
		else
		{
			$extractors = Extractor::where(['user_id' => Auth::user()->id])->get();
		}
	   return view('client.extractor.list')->with(["extractors" => $extractors]);
   }
   
   
    /**
     * Save Settings.
     */
	 
	 
   
   function extractorSettingsSave(Request $request,$id)
   {
	    $extractor = Extractor::where(['ext_id' =>  $id,'user_id' => Auth::user()->id])->first();
			   
			   if(count((array)$extractor) > 0)
			   { 
					//$extractor->ext_url = $url;
					$extractor->ext_urls = $request->extractor_urls;
					$extractor->ext_desc = $request->extractor_desc;
					$extractor->ext_name = $request->extractor_name;
					$extractor->post_url = $request->post_url;
					if($request->post_url_en)
						$extractor->post_url_en = $request->post_url_en;
					else
						$extractor->post_url_en = '';
					if($request->repeater == "no_repeat")
					{
						$extractor->ext_run_type = "no_repeat";
						$extractor->ext_time = "";
						$extractor->ext_date = "";
						$extractor->ext_day = "";
						$extractor->ext_month = "";
					}
					else
					{
						$time_in_24_hour_format  = date("H:i", strtotime($request->time_selector));
						$extractor->ext_run_type = $request->repeater;
						$extractor->ext_time = $time_in_24_hour_format;
						$extractor->ext_date = $request->date_selector;
						$extractor->ext_day = $request->day_selector;
						$extractor->ext_month = $request->month_selector;
					}
					
					/*$extractor->ext_data = json_encode(array());
					$extractor->ext_bot = json_encode($ext_bot);
					$extractor->user_id = Auth::user()->id;*/
					$extractor->save();
					
					$userActivity = new UserActivity();
					$userActivity->ext_id = $id;
					$userActivity->user_id = Auth::user()->id;
					$userActivity->act_desc = "Extractor (".$extractor->ext_name.") Settings Updated!";
					$userActivity->save();
					
			   }
	   return redirect()->back()->with('success',"Extractor Settings Saved");
   }
   function extractorSettings2($id)
   {
		$extractor = Extractor::where(['ext_id' =>  $id])->first();
	   if(count((array)$extractor) == 0)
	   {
			return redirect("/user/extractor/new")->with('error','Something went wrong');   
	   }
	   return view('client.extractor.setting2')->with(['extractor' => $extractor]); 
   }
   function extractorSettings($id) 
   {
		$extractor = Extractor::where(['ext_id' =>  $id])->first();
	   if(count((array)$extractor) == 0)
	   {
			return redirect("/user/extractor/new")->with('error','Something went wrong');   
	   }
	   return view('client.extractor.setting')->with(['extractor' => $extractor]); 
   }
   
   function extractorDataPage($id)
   {
	   $extractor = Extractor::where(['ext_id' =>  $id])->first();
	   if(count((array)$extractor) == 0)
	   {
			return redirect("/user/extractor/new")->with('error','Something went wrong');   
	   }
	   return view('client.extractor.data')->with(['extractor' => $extractor]); 
   }
   
   function activateExtractor(Request $request,$id,$user_id)
   {
	    if(isset($request->_token) && $request->_token == csrf_token())
	   {
		   $extractor = Extractor::where(['ext_id' =>  $id,'user_id' => $user_id])->first();
		   if(count((array)$extractor) > 0)
		   {
			    $extractor->ext_draft = 0; 
				if($request->ext_draft && $request->ext_draft == 0)
					 $extractor->ext_draft = 1; 
			  $extractor->save();
			  
			  
					$userActivity = new UserActivity();
					$userActivity->ext_id = $id;
					$userActivity->user_id = $user_id;
					$userActivity->act_desc = "Extractor (".$extractor->ext_name.") Set to Activated!";
					$userActivity->save();
			  
			  return "Draft to Active Success";
		   }
	   }
	   
   }
   
   function extractorData(Request $request,$id,$user_id)
   {
	   if(isset($request->_token) && $request->_token == csrf_token() || $request->_token == "hi_cron_job")
	   {
			   $extractor = Extractor::where(['ext_id' =>  $id,'user_id' => $user_id])->first();
		   if(count((array)$extractor) == 0)
		   {
				return redirect("/user/extractor/new")->with('error','Something went wrong');   
		   }
		   $array_ext_data = json_decode($extractor->ext_data,true);

		   $original_data = array();
			
			$count_arr = 0;
			foreach($array_ext_data as $key => $col)
			{
				//$column_data[$key] = array();
				
					$count_arr = 0;
					$tmp_data = array();
				foreach($col['data'] as $page){
					foreach($page as $key_ii => $pp)
					{
						$count_arr++;
						$tmp_data[] = $pp;
					}
				}
				$original_data[$key] = $tmp_data;
			}
			$response_data = array();
				for($k =0 ;$k < $count_arr ; $k++)
				{
					
					$column_data = array();
					
					foreach($original_data as $key_data=>$column)
					{	
						$column_data[] = $column[$k];
					}
					$response_data[] = $column_data;
				}
			$response_data1["data"] = $response_data;
			
			
				$userActivity = new UserActivity();
				$userActivity->ext_id = $id;
				$userActivity->user_id = $user_id;
				$userActivity->act_desc = "Extractor (".$extractor->ext_name.") Data Retrived!";
				$userActivity->save();
			
		   return  json_encode($response_data1,false); 
	   }
	   else
	   {
		   return "404 ERROR";
	   }
   }
   function addNew(Request $request)
   {
	    $this->validate($request,array(
		'extractor_name' => 'required|min:3|max:100',
		'extractor_url' => 'required|min:3',
		));
		
		
		$packageDetail = new PackageDetail();
		
		if(!$packageDetail->isUserAllowedToCreateNewExtractor())
		{
			return redirect()->back()->with('error',"You have reach the limit to add extractors, Please subscribe to new package or renew or upgrade subscription");	
		}
		
		$url = $this->cleanBaseURL($request->extractor_url);
		
		$httpRequest = new HTTPRequest($url);
		$resp = json_decode($httpRequest->getBodyGuzzle(),true);
			if(isset($resp['response_code']) && $resp['response_code'] == '200')
			{
				$ext_bot = array("column_1" => array("name"=> "Column 1","bot" => array()));
	   
			   $extractor = Extractor::where(['ext_url' =>  $url,'user_id' => Auth::user()->id])->first();
			   
			   if(count((array)$extractor) == 0)
			   { 
					$extractor = new Extractor();
					$extractor->ext_url = $url;
					$extractor->ext_urls = '{"url":"","params":{},"data":{}}';
					$extractor->ext_name = $request->extractor_name;
					$extractor->ext_data = json_encode(array());
					$extractor->ext_bot = json_encode($ext_bot);
					$extractor->user_id = Auth::user()->id;
					$extractor->save();
					
				$userActivity = new UserActivity();
				$userActivity->ext_id = $extractor->ext_id;
				$userActivity->user_id = Auth::user()->id;
				$userActivity->act_desc = "New Extractor (".$extractor->ext_name.") added!";
				$userActivity->save();

			   }
			   return redirect("/user/extractor/$extractor->ext_id/builder");
			}
		return redirect()->back()->with('error',"Something is wrong with URL ($url), Please double check");	
	   
   }
   public function buildExtractorShow($id)
   {
	   return view('client.extractor.builder')->with(['id' => $id]);
   }
   
   public function buildExtractorjQuery(Request $request)
   {
	   $extractor = Extractor::where(['ext_id' =>  $request->ext_id])->first();
	   $processDocument = new ProcessDocumentDOM($request->html,$extractor,true);
	   $fileContents = 
			"<!DOCTYPE html>
			<html>".
			$processDocument->headTag.
			$processDocument->bodyTag.
			"</html>";
		return  $fileContents;	
   }
   
   public function deleteExtractor(Request $request)
   {
	   if($request->_token == csrf_token())
	   {
		   $extractor = Extractor::where(['ext_id' =>  $request->ext_id,'user_id' => Auth::user()->id])->first();
	   
		   if(count((array)$extractor) > 0)
		   {
			   
				$userActivity = new UserActivity();
				$userActivity->ext_id = $request->ext_id;
				$userActivity->user_id = Auth::user()->id;
				$userActivity->act_desc = "Extractor (".$extractor->ext_name.") Deleted!";
				$userActivity->save();
			   $extractor->delete();
			   $myFile = "public/extractors/Ext_".$request->ext_id."_".Auth::user()->id.".html";
			   Storage::delete($myFile);
			   Storage::deleteDirectory("public/tmp/Ext_".$request->ext_id."_".Auth::user()->id);
			   
			   

			   return "EXTRACTOR_DELETED";
		   }
		   else
		   {
			   return "NO_EXTRACTOR_FOUND";
		   }
	   }
	   else
	   {
		   return "INVALID_REQUEST";
	   }
		
   }
   
   public function alreadySavedData($extractor,$html)
   {
	   $tmp_ext_data = json_decode($extractor->ext_data,true);
		foreach($tmp_ext_data as $x=>$column_x)
		{
			$tmp_ext_data[$x]["data"] = $column_x["data"]["p1"];
		}
		if(count((array)$tmp_ext_data) == 0)
		{
			$tmp_ext_data['column_1']['data'] = array();
		}
		$var = '';
		$var.='<script>
		 auto_bot_mode = true;
		 bot_data_extracted = '.json_encode($tmp_ext_data).';
		 bot_data_extracted_bot = '.$extractor->ext_bot.';
		</script>';
		
		$array_bot_data = json_decode($extractor->ext_bot,true);
			$data_of_unique_ids = [];

			foreach($array_bot_data as $ar)
			{
				if(isset($ar['bot'])){
				foreach($ar['bot'] as $b){
				$data_of_unique_ids[] = $b;
				}}
				
			}
			$test = '<script>
			';
			 foreach($data_of_unique_ids as $ii){
				$test .= '$("[data-hi_id='.$ii.']").removeClass("io_text");
				';
				$test .= '$("[data-hi_id='.$ii.']").addClass("io_text_saved");
				';
			 }
			$test .= "
			
			
			
			</script>";
			
		$var .= $test;
		
		$html->find('body',0)->outertext = $html->find('body',0)->makeup() . $html->find('body',0)->innertext . $var . '</body>';
		return $html;
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

	public function getDataSaveUrl($url)
	{
		$this->httpRequest->url = $url;
		$tmp_file_body_load = "public/extractors/ext_".rand(1000,9999)."_1.html";
		$resp = json_decode($this->httpRequest->getBodyGuzzle(),true);
		
		if(isset($resp['response_code']) && $resp['response_code'] == '200')
			{
				$fileContentsBodyLoad = 
						"<!DOCTYPE html>
						<html>".
						$this->httpRequest->body.
						"</html>";
				Storage::put($tmp_file_body_load, $fileContentsBodyLoad);
				$tmp_lnk_body_load = url(str_replace("public","storage",$tmp_file_body_load));
				return array(
				'storage_link' => $tmp_file_body_load,
				'public_link' => $tmp_lnk_body_load
				
				);
			}
			else
			{
				return array("storage_link"=>"ERROR");
			}
		
	}
	
   public function buildExtractor($id)
   {
	   define('MAX_FILE_SIZE', 6000000); 
	   $extractor = Extractor::where(['ext_id' =>  $id])->first();
	   if(count((array)$extractor) == 0)
	   {
			return view("/errors/404")->with('error','Something went wrong or Extractor not found');   
	   }
		$reply_link = $this->getDataSaveUrl($extractor->ext_url);
		
		if($reply_link['storage_link'] != "ERROR")
			{
				
			   $tmp_file = "public/extractors/ext_".$extractor->ext_id."_".Auth::user()->id.".html";
			   $file_obj_exist = Storage::exists($tmp_file);
			   if(!$file_obj_exist)  
			   //if(1)  
			   {
					$url =$this->httpRequest->url;
					$processDocument = new ProcessDocument($url,$reply_link['public_link'],$extractor,true);
					// Check if any data already exist then embed in the html body
					
					$fileContents = '
					<!--  modal start -->
<div id="modal_extractors" class="modal fade" role="dialog" style="z-index:9999999;">
	<div class="modal-dialog modal-lg" style="width:1366px;">
		<div class="login-card card-block login-card-modal">
			<div class="md-float-material">
			   
				<div class="card m-t-15">
					<div class="auth-box card-block">
					<div class="row m-b-20">
						<div class="col-md-12">
							<h3 class="text-center txt-primary" id="title_of_module">{TITLE}</h3>
							<button class="btn btn-danger" data-dismiss="modal">X</button>
						</div>
					</div>
					<hr/>
						<div id="body_of_module">	
						</div>
				</div>
				</div>
			</div>
			<!-- end of form -->
		</div>
	</div>
</div>
<!-- modal end-->
					'.
					"<!DOCTYPE html>
					<html lang='en'>".
					$processDocument->headTag.
					$processDocument->bodyTag.
					"</html>";
					Storage::put($tmp_file, $fileContents);
			   }
			   $ext_body = Storage::get($tmp_file);   
			   
			   $html = HtmlDomParser::str_get_html($ext_body);
			   
			  
			   
			   if($html)
			   {
				   if($html->find("#add_the_csrf_token"))
				   {
					   $html->find('#add_the_csrf_token', 0)->innertext = '
					   <script>
					   csrf_token = "'.csrf_token().'";
					   
					   var html_body_obj = document.body;
					  traverseTagsJavaScript(html_body_obj);
					   
					  function traverseTagsJavaScript(tags)
					  {  
						   for (let i = 0; i < tags.childNodes.length; i++) {
								console.log( document.body.childNodes[i] ); // Text, DIV, Text, UL, ..., SCRIPT
							}
					  }		  
					   </script>
					   ';
				   }
				   Storage::delete($reply_link['storage_link']);
				   
				   
				$userActivity = new UserActivity();
				$userActivity->ext_id = $id;
				$userActivity->user_id = Auth::user()->id;
				$userActivity->act_desc = "Visual Builder for (".$extractor->ext_name."-".$extractor->ext_id.") Opened!";
				$userActivity->save();
				
				return $html;
				   return $this->alreadySavedData($extractor,$html);
				}
				else
				{
					return view("/errors/404")->with('error','Parsing Error, Please Contact us about this issue.');
				}
			}
			else
			{
				return view("/errors/404")->with('error','Error with url exit code '.$resp['response_code']); 
			}
				
	   /*if ($html->find("#hi-7256577-load-data"))
	   {
		   echo "Find";
	   }*/
//	   return $ext_body;
	   
	   //return view('client.extractor.build')->with(['url' => $extractor->ext_url ,'extractor' => $extractor]); 
   }
   
   
	public function listActivities($type)
	{
		if($type == "user")
			$user_activities = UserActivity::where(['act_type'=>0,'user_id' => Auth::user()->id])->orderBy('created_at','desc')->paginate(30);
		else if($type == "login")
			$user_activities = UserActivity::where(['act_type'=>1,'user_id' => Auth::user()->id])->orderBy('created_at','desc')->paginate(30);
		else
			$user_activities = UserActivity::where(['user_id' => Auth::user()->id])->orderBy('created_at','desc')->paginate(30);
        return view('client.extractor.user_activities')->with('user_activities',$user_activities);
	}
   
	function runExtractor(Request $request,$id)
	{
		
		$packageDetail = new PackageDetail();
		
		$r_counts = $packageDetail->allowed_requests;
		
		if($r_counts <= 0)
		{
			return "NO_PACKAGE";
		}
		
		define('MAX_FILE_SIZE', 6000000);
		ignore_user_abort(true);
		if(isset($request->_token) && $request->_token == csrf_token() || $request->_token == "hi_cron_job")
		{
			if($request->_token == "hi_cron_job" && !$packageDetail->isAutoScheduleAllowed)
			{
				return "AUTO_SCHEDULED_NOT_ENABLED";
			}
			$extractor = Extractor::where(['ext_id' => $id])->first();

			if(count((array)$extractor) > 0 && $extractor->ext_draft == 0)
			{
				
				$json_data = array();
				
				$reply_link = $this->getDataSaveUrl($extractor->ext_url);
		
				$url =$this->httpRequest->url;
				if($reply_link['storage_link'] != "ERROR")
					{
						$r_counts--;
				$obj = new ProcessDocument($url,$reply_link['public_link'],$extractor,true,false);  
				$col_count = 0;$row_count = 0;$total_requests = 1;
				foreach($obj->bot_data_tags as $col_name => $tmp_li)
				{
					$col_count++;
					$col_data  = array();
					foreach($tmp_li['bot'] as $bx){
						
						$b = "[data-hi_id=".$bx."]";
						$col_data[$bx] = strip_tags($obj->bodyTag->find($b,0));
						$row_count++;
					}
					$json_data[$col_name]["data"]["p1"] = $col_data;
				}
				
				Storage::delete($reply_link['storage_link']);
				
				$urls_data = json_decode($extractor->ext_urls,true);
				
				
				foreach($urls_data["data"] as $key=>$value)
				{
					for($i=$urls_data["data"][$key][0];$i<=$urls_data["data"][$key][2];$i += $urls_data["data"][$key][1])
					{
						$url = str_replace("[[".$urls_data["params"][$key]."]]",$i,$urls_data["url"]);
						//echo $url;
						if($r_counts <= 0)
							{
								$packageDetail->updatePackage($packageDetail->allowed_requests,0);
								return "Run Completed or Interupted PACKAGE ENDED after ".$packageDetail->allowed_requests." Requests";
							}
						
						
						$reply_link = $this->getDataSaveUrl($extractor->ext_url);
		
						$url =$this->httpRequest->url;
						if($reply_link['storage_link'] != "ERROR")
							{
								$r_counts--;
								$obj = new ProcessDocument($url,$reply_link['public_link'],$extractor,true,false); 
								$total_requests++;
								if($obj->resp_loop['response_code'] == 200)
								{
									foreach($obj->bot_data_tags as $col_name => $tmp_li)
									{
										$col_data  = array();
										foreach($tmp_li['bot'] as $bx){
											
											$b = "[data-hi_id=".$bx."]";
											$col_data[$bx] = strip_tags($obj->bodyTag->find($b,0));
											$row_count++;
										}
										$json_data[$col_name]["data"]["p$i"] = $col_data;
									}
								}
								else
									break;
								Storage::delete($reply_link['storage_link']);
							}
					}
				}
				$extractorAnalytic = new ExtractorAnalytic();
				$extractorAnalytic->ext_id = $id;
				$extractorAnalytic->user_id = $extractor->user_id;
				$extractorAnalytic->column_extracted = $col_count;
				$extractorAnalytic->total_requests = $total_requests;
				$extractorAnalytic->rows_extracted = $row_count;
				$extractorAnalytic->save();
				$extractor->ext_data = json_encode($json_data);
				$extractor->save();
				$packageDetail->updatePackage($total_requests,0);
				$user = User::where('id',$extractor->user_id)->first();
				echo "Data Extracted and sent to your email ".$user->email;
				
				/**
				* Check if user demanded to send data to website 
				*/
				
				$ext_data_csv = $this->extractorData($request,$extractor->ext_id,$extractor->user_id);
				if($extractor->post_url_en == "on" && $packageDetail->isPostAllowed)
				{
					$m = new HTTPRequest($extractor->post_url);
					$resp_post = $m->postData($ext_data_csv);
					
					echo " and also to your website ".$extractor->post_url." response ($resp_post)";
				}
				
				$userActivity = new UserActivity();
				$userActivity->ext_id = $extractor->ext_id;
				$userActivity->user_id =$extractor->user_id;
				$userActivity->act_desc = "Extractor (".$extractor->ext_name.") run!";
				$userActivity->save();
				
				
				/**
				* Send data to the email of the user from here
				*/
				if($packageDetail->isEmailAllowed)
				{
					$ext_data_csv = json_decode($ext_data_csv,true);
					//print_r($ext_data_csv['data']);
					$path =  public_path()."/tmp/file_".rand(10000,99999).".csv";
					$file = fopen($path,'wb');
					foreach($ext_data_csv['data'] as $da)
					{
						fputcsv($file,$da);
					}
					fclose($file);
					Mail::to(Auth::user()->email)->send(new HandyImportExtractedData($path));
					unlink($path);
				}
				
			}
			}
			else if($extractor->ext_draft == 1)
			{
				echo "Draft extractor can't be run. Activate to run.";
			}
			else
			{
				echo "Invalid extractor or extractor not found.";
			}
		}
		else
		{
			echo "Invalid request";
		}
	}
	 
	function runExtractorOld(Request $request,$id)
	{
		define('MAX_FILE_SIZE', 6000000);
		ignore_user_abort(true);
		if(isset($request->_token) && $request->_token == csrf_token() || $request->_token == "hi_cron_job")
		{
			$extractor = Extractor::where(['ext_id' => $id])->first();

			if(count((array)$extractor) > 0 && $extractor->ext_draft == 0)
			{
				
				$json_data = array();
				
				$reply_link = $this->getDataSaveUrl($extractor->ext_url);
		
				$url =$this->httpRequest->url;
				if($reply_link['storage_link'] != "ERROR")
					{
				$obj = new ProcessDocument($url,$reply_link['public_link'],$extractor,true,false);  
				$col_count = 0;$row_count = 0;$total_requests = 1;
				foreach($obj->bot_data_tags as $col_name => $tmp_li)
				{
					$col_count++;
					$col_data  = array();
					foreach($tmp_li['bot'] as $bx){
						
						$b = "[data-hi_id=".$bx."]";
						$col_data[$bx] = strip_tags($obj->bodyTag->find($b,0));
						$row_count++;
					}
					$json_data[$col_name]["data"]["p1"] = $col_data;
				}
				
				Storage::delete($reply_link['storage_link']);
				
				$urls_data = json_decode($extractor->ext_urls,true);
				
				
				foreach($urls_data["data"] as $key=>$value)
				{
					for($i=$urls_data["data"][$key][0];$i<=$urls_data["data"][$key][2];$i += $urls_data["data"][$key][1])
					{
						$url = str_replace("[[".$urls_data["params"][$key]."]]",$i,$urls_data["url"]);
						//echo $url;
						$reply_link = $this->getDataSaveUrl($extractor->ext_url);
		
						$url =$this->httpRequest->url;
						if($reply_link['storage_link'] != "ERROR")
							{
								$obj = new ProcessDocument($url,$reply_link['public_link'],$extractor,true,false); 
								$total_requests++;
								if($obj->resp_loop['response_code'] == 200)
								{
									foreach($obj->bot_data_tags as $col_name => $tmp_li)
									{
										$col_data  = array();
										foreach($tmp_li['bot'] as $bx){
											
											$b = "[data-hi_id=".$bx."]";
											$col_data[$bx] = strip_tags($obj->bodyTag->find($b,0));
											$row_count++;
										}
										$json_data[$col_name]["data"]["p$i"] = $col_data;
									}
								}
								else
									break;
								Storage::delete($reply_link['storage_link']);
							}
					}
				}
				$extractorAnalytic = new ExtractorAnalytic();
				$extractorAnalytic->ext_id = $id;
				$extractorAnalytic->user_id = $extractor->user_id;
				$extractorAnalytic->column_extracted = $col_count;
				$extractorAnalytic->total_requests = $total_requests;
				$extractorAnalytic->rows_extracted = $row_count;
				$extractorAnalytic->save();
				$extractor->ext_data = json_encode($json_data);
				$extractor->save();
				$user = User::where('id',$extractor->user_id)->first();
				echo "Data Extracted and sent to your email ".$user->email;
				
				/**
				* Check if user demanded to send data to website 
				*/
				
				$ext_data_csv = $this->extractorData($request,$extractor->ext_id,$extractor->user_id);
				if($extractor->post_url_en == "on")
				{
					$m = new HTTPRequest($extractor->post_url);
					$resp_post = $m->postData($ext_data_csv);
					
					echo " and also to your website ".$extractor->post_url." response ($resp_post)";
				}
				
				$userActivity = new UserActivity();
				$userActivity->ext_id = $extractor->ext_id;
				$userActivity->user_id =$extractor->user_id;
				$userActivity->act_desc = "Extractor (".$extractor->ext_name.") run!";
				$userActivity->save();
				
				/**
				* Send data to the email of the user from here
				*/
				
				$ext_data_csv = json_decode($ext_data_csv,true);
				//print_r($ext_data_csv['data']);
				$path =  public_path()."/tmp/file_".rand(10000,99999).".csv";
				$file = fopen($path,'wb');
				foreach($ext_data_csv['data'] as $da)
				{
					fputcsv($file,$da);
				}
				fclose($file);
				Mail::to(Auth::user()->email)->send(new HandyImportExtractedData($path));
				unlink($path);			
			}
			}
			else if($extractor->ext_draft == 1)
			{
				echo "Draft extractor can't be run. Activate to run.";
			}
			else
			{
				echo "Invalid extractor or extractor not found.";
			}
		}
		else
		{
			echo "Invalid request";
		}
	}
	
	function ping($hostt, $port =80, $timeout = 5) {
		$TMP = explode("/",$hostt);
		$host = $TMP[0];
		$ttl = 128;
		$ping = new Ping($host,$ttl, $timeout);
		$latency = $ping->ping();
		if ($latency !== false) {
			return true;
		}
		else {
			return false;
		}
}
}
