<?php

namespace App\Http\Controllers;
use App\Handyimport\HTTPRequest;
use Illuminate\Http\Request;
use App\Extractor;
use App\Logs;
use App\HiPages;
use App\HiPagesD;
use App\ExtractorAnalytic;
use Mail;
use App\Mail\HandyImportExtractedData;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
class CustomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	private $h;
	private $todayDate;
	private $category;
	private $subrub;
	private $state;
    public function __construct()
    {
		set_time_limit(0);
		ini_set('memory_limit', '3G');
		ini_set("log_errors", 1);
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		$this->h = new HTTPRequest("",false);
		$this->todayDate = date('Y-m-d');
    }
	
   public function traverseBusinessDataAPI()
   {
		$st = time();
		$client = $this->h->guzzleClient;//,'demolition','painters','tilers']
		$hipagesd = HiPagesD::where('last_traversed','<>','2019-06-17')->get();
		$lp = 1 ;  
		
	   foreach($hipagesd as $h)
			{
				$keyId1 = $h->category."/".$h->state."/".$h->subrub."/1";
				$keyId2 = $h->category."/".$h->state."/".$h->subrub."/2";
				$keyId3 = $h->category."/".$h->state."/".$h->subrub."/3";
				$keyId4 = $h->category."/".$h->state."/".$h->subrub."/4";
				$keyId5 = $h->category."/".$h->state."/".$h->subrub."/5";

					$api_url1 = "https://hipages.com.au/api/directory/sites?suburb=$h->subrub&state=$h->state&category=$h->cat_id&page=1&perpage=10&code=$h->code";
					$api_url2 = "https://hipages.com.au/api/directory/sites?suburb=$h->subrub&state=$h->state&category=$h->cat_id&page=2&perpage=10&code=$h->code";
					$api_url3 = "https://hipages.com.au/api/directory/sites?suburb=$h->subrub&state=$h->state&category=$h->cat_id&page=3&perpage=10&code=$h->code";
					$api_url4 = "https://hipages.com.au/api/directory/sites?suburb=$h->subrub&state=$h->state&category=$h->cat_id&page=4&perpage=10&code=$h->code";
					$api_url5 = "https://hipages.com.au/api/directory/sites?suburb=$h->subrub&state=$h->state&category=$h->cat_id&page=5&perpage=10&code=$h->code";
					
					$promises[$keyId1] = $client->getAsync($api_url1);
					$promises[$keyId2] = $client->getAsync($api_url2);
					$promises[$keyId3] = $client->getAsync($api_url3);
					$promises[$keyId4] = $client->getAsync($api_url4);
					$promises[$keyId5] = $client->getAsync($api_url5);
					
					if($lp++ == 50)
					{  						
						// Wait on all of the requests to complete. Throws a ConnectException
						// if any of the requests fail
						$responses = Promise\unwrap($promises);

						// Wait for the requests to complete, even if some of them fail
						$responses = Promise\settle($promises)->wait();
						$lp = 0;
						// You can access each response using the key of the promise
						foreach($responses as $key=>$result)
						{
							  if($result['state'] === 'fulfilled')
							{
								$response = $result['value'];
								if($response->getStatusCode() == 200)
								{
									echo $key."\n";
									$this->visitBusiness($response->getBody(),$key);
									unset($promises[$key]);
								}
								else{
									echo $response->getStatusCode();
								}
							}
							else if($result['state'] === 'rejected'){ // notice that if call fails guzzle returns is as state rejected with a reason.

								echo 'ERR: ' . $result['reason'];
							}
							else{
								echo 'ERR: ';
							}
						}
						//sleep(1);
						$lp = 1;						
					} 
				
				// if($key == 1000)
					// break;
			$h->last_traversed = $this->todayDate;	
			$h->save();	
			}
			
		//HiPages::where('last_checked_at','<>',$this->todayDate)->select('last_checked_at','removed_at')->update(['removed_at' => $this->todayDate]);	
		$this->traverseBusinessPhoneAPI();
		$this->traverseBusinessMemberAPI();
		$et = time();
		$xtractorAnalytic = new ExtractorAnalytic();
		$xtractorAnalytic->ext_id = 0;
		$xtractorAnalytic->total_requests = ($et-$st);
		$xtractorAnalytic->save();
		
		echo "\nOver all time to fetch ".count((array) $hipagesd)." businesses ".($et-$st)."\n";
}  
   public function traverseBusinessPhoneAPI()
   {
	   $st = time();

		$client = $this->h->guzzleClient;
	   $hipagesd = HiPages::where('contactNo',NULL)->select('contactNo','contactKey')->get();
	   $lp = 1 ;  
	   foreach($hipagesd as $key=>$h)
			{
					$api_url = "https://hipages.com.au/api/directory/phone?number=".$h->contactKey;	//	
					$promises[$h->contactKey] = $client->getAsync($api_url);
					
				if($lp == 250 || $lp == count((array)$hipagesd))
				{
						echo "P ".$key."\n";				
						// Wait on all of the requests to complete. Throws a ConnectException
						// if any of the requests fail
						$responses = Promise\unwrap($promises);

						// Wait for the requests to complete, even if some of them fail
						$responses = Promise\settle($promises)->wait();
						$lp = 0;
						// You can access each response using the key of the promise
						foreach($responses as $key=>$result)
						{
							  if($result['state'] === 'fulfilled')
							{
								$response = $result['value'];
								if($response->getStatusCode() == 200)
								{
									
									$phoneJson = json_decode($response->getBody(),true);
									if(isset($phoneJson['phone']))
										{
											HiPages::where('contactKey',"".$key)->select('contactNo','contactKey')->update(['contactNo' => "".$phoneJson['phone']]);
										}
									unset($promises[$key]);
								}
								else{
									echo $response->getStatusCode();
								}
							}
							else if($result['state'] === 'rejected'){ // notice that if call fails guzzle returns is as state rejected with a reason.

								echo 'ERR: ' . $result['reason'];
							}
							else{
								echo 'ERR: ';
							}
						}
						//sleep(1);
						$lp = 0; 
						
				}
				$lp++;
				// if($key == 1000)
					// break;
				
			}	
		$et = time();
		echo "\nPhone ".count((array)$hipagesd)." update time ".($et-$st)."\n";
}

  public function traverseBusinessMemberAPI()
   {
	   $st = time();

		$client = $this->h->guzzleClient;
	   $hipagesd = HiPages::where('added_at',NULL)->select('added_at','siteKey')->get();
	   $lp = 1 ;  
	   foreach($hipagesd as $key=>$h)
			{
				/*$dd = explode("/",$h->added_at);
				if(isset($dd[2]))
				{
					echo $h->siteKey."\n";	
					HiPages::where('siteKey',"".$h->siteKey)->select('siteKey','added_at')->update(['added_at' => $dd[2]."-".$dd[1]."-".$dd[0]]);
				}*/
				
				$api_url = "https://hipages.com.au/enquire/".$h->siteKey;	//	
				
				$promises[$h->siteKey] = $client->getAsync($api_url);
				
				if($lp == 150 || $lp == count((array)$hipagesd))
				{
						//echo "P ".$key."\n";				
						// Wait on all of the requests to complete. Throws a ConnectException
						// if any of the requests fail
						$responses = Promise\unwrap($promises);

						// Wait for the requests to complete, even if some of them fail
						$responses = Promise\settle($promises)->wait();
						$lp = 0;
						// You can access each response using the key of the promise
						foreach($responses as $key=>$result)
						{
							  if($result['state'] === 'fulfilled')
							{
								$response = $result['value'];
								if($response->getStatusCode() == 200)
								{
									
									//echo $key." \n";
									$phoneJson = $response->getBody();
									if(preg_match("/\{\"joined\"\:\"(.*?)\"\,/",$phoneJson,$matches))
										{
											//print_r($matches);
											if(isset($matches[1]))
											{
												echo $key." ".$matches[1]."\n";
												HiPages::where('siteKey',"".$key)->select('siteKey','added_at')->update(['added_at' => "".substr($matches[1],0,10)]);
											}
											
										}
									unset($promises[$key]);
								}
								else{
									echo "Error: ".$response->getStatusCode()."\n";
								}
							}
							else if($result['state'] === 'rejected'){ // notice that if call fails guzzle returns is as state rejected with a reason.

								echo 'ERR: ' . $result['reason'];
							}
							else{
								echo 'ERR: ';
							}
						}
						//sleep(1);
						$lp = 0;
						//break;
				}
				$lp++ ;
				
			}
		//$this->sendMail($this->todayDate);	
		$et = time();
		echo "\nAdded on ".count((array)$hipagesd)." update time ".($et-$st)."\n";
}

public function saveFile($date,$all=false)
{
	if(!$all)
		$hipagesd = HiPages::orderBy('added_at','desc')->where('added_at',$date)->get();
	else
		$hipagesd = HiPages::orderBy('added_at','desc')->get();
	//print_r($ext_data_csv['data']);
	$path =  public_path()."/tmp/file_".$date.".csv";
	$file = fopen($path,'wb');
	
	fputcsv($file,array("siteName","serviceArea","contactNo","Joined Date","Crawled Date","removed_at","last_checked_at","siteKey"));
	
	foreach($hipagesd as $da)
	{
		fputcsv($file,array($da->siteName,$da->serviceArea,$da->contactNo,$da->added_at,$da->created_at,$da->removed_at,$da->last_checked_at,$da->siteKey));
	}
	fclose($file);
	
	echo "<a href=/hipages.com.au/public/tmp/file_".$date.".csv> Download </a>";
	
}
public function sendMail($date,$all=false)
{
	/**
	* Send data to the email of the user from here
	*/
	$email1 = "stuarthawking@gmail.com";
	$email2 = "info@aimfox.net";
	if(!$all)
		$hipagesd = HiPages::orderBy('added_at','desc')->where('added_at',$date)->get();
	else
		$hipagesd = HiPages::orderBy('added_at','desc')->get();
	//print_r($ext_data_csv['data']);
	$path =  public_path()."/tmp/file_".rand(10000,99999).".csv";
	$file = fopen($path,'wb');
	
	fputcsv($file,array("siteName","serviceArea","contactNo","Joined Date","Crawled Date","removed_at","last_checked_at","siteKey"));
	
	foreach($hipagesd as $da)
	{
		fputcsv($file,array($da->siteName,$da->serviceArea,$da->contactNo,$da->added_at,$da->created_at,$da->removed_at,$da->last_checked_at,$da->siteKey));
	}
	fclose($file);
	try{
		Mail::to($email1)->send(new HandyImportExtractedData($path));
		Mail::to($email2)->send(new HandyImportExtractedData($path));
	}catch(Swift_RfcComplianceException $e)
	{
		
	}
	unlink($path);
	
}
   /*
    Public function only
   */
   
   public function hipagesCount(Request $request)
   {
	   //$today = HiPagesD::where('last_traversed',$this->todayDate)->count()
	   if($request->filter)
	   {
			$data =  HiPages::where('added_at',$request->filter)->paginate(50);
			$data->withPath('?filter='.$request->filter);
			$b_count = HiPages::where('added_at',$request->filter)->count();
			 echo "Count Suburbs x Categories Purpose : ".HiPagesD::where('last_traversed',$request->filter)->count()."<br>";
			
	   }
	   else
	   {
		   $b_count = HiPages::count();
			$data =  HiPages::orderBy('added_at','desc')->paginate(50);
			echo "Count Suburbs x Categories Purpose : ".HiPagesD::count()."<br>";
	   }
	   
	   echo "Total Businesses: ".$b_count."<br>";
	  // echo "Today Listing estimated Businesses: ".$today."<br>";
	  
	    echo "<br><br>";

	   echo $data->links();
	      echo "<br>";
	  foreach($data as $d)
	  {
		  echo "Business Joined <b>".$d->added_at."</b><br>"; 
		  echo "Business Name <b>".$d->siteName."</b><br>";  
		  echo "Business Added in Database <b>".$d->created_at."</b><br>";
		  echo "Business Removed <b>".$d->removed_at."</b><br>";
		  echo "Business Address <b>".$d->serviceArea."</b><br>";
		  echo "Business Contact <b>".$d->contactNo."</b><br>";
		  echo "Business Site Key <b>".$d->siteKey."</b><br><br>";
	  }
	
	if($request->email)
	{
	  	/**
		* Send data to the email of the user from here
		*/
		if($request->filter)
			$this->sendMail($request->filter);
		else
			$this->sendMail($request->filter,true);
	}
	  
   }
   
 public function visitBusiness($businesses,$key)
	{
			$businesses = json_decode($businesses,true);
			
			/*$et = time();
			
			echo "api request load time ".($et-$st)."<br>";
			*///echo "\n\napi_link $api_link\n\n";
			//echo "\nLoading Page $page\n\n";
			
			foreach($businesses as $business)
			{
				//echo "".$business['siteName']."\n";
				$siteName = '';$serviceArea = '';$contactNo = '';
				if(isset($business['siteName']))
				{
					
					$siteName = $business['siteName'];
				}
				
				if(isset($business['serviceArea']))
				{
					
					$serviceArea = $business['serviceArea'];
				}
				
				$contactkey = 0;	
				if(isset($business['phone']) && $business['phone'] != '')
				{
					
					$contactkey = $business['phone'];
				}
				if(isset($business['mobile']) && $business['mobile'] != '')
				{
					
					$contactkey = $business['mobile'];
				}
				$siteKey = '';
				if(isset($business['siteKey']) && $business['siteKey'] != '')
				{
					
					$siteKey = $business['siteKey'];
				}
				
				//$st = time(); 
				
				
			try{
					$hipages = HiPages::where(['siteKey'=>$siteKey])->get();
					if(count((array)$hipages) == 0)
					{
						$hipages = new HiPages();
						$hipages->siteName = $siteName;
						$hipages->serviceArea = $serviceArea;
						$hipages->contactKey = $contactkey;
						$hipages->siteKey = $siteKey;
						$hipages->created_at = $this->todayDate;
						$hipages->last_checked_at = $this->todayDate;
						$hipages->save();
					}
					else  
					{
						$hipages[0]->last_checked_at = $this->todayDate;
						$hipages[0]->save();
					} 
				}catch(Exception $e)
				{
					$logs = new Logs();
					$logs->error = $e->getMessage();
					$logs->message = substr($e->getTraceAsString(),0,500);
					$logs->url = $this->h->url;
					$logs->fn = "dbEntry";
					$logs->save();
				}
			//	echo "siteName ".$siteName."\n";
			/*	echo "serviceArea <b>".$serviceArea."</b><br>";
				echo "contactNo <b>".$contactNo."</b><br>";
			*/	
				/*$et = time();
				echo "phone number load time ".($et-$st)."<br>";
				*/
			}
			//break;
		/*
				echo "CAT $cat_id<br>";
				echo "CODE $code<br>";
				echo "state_key $state_key<br>";
				echo "subrub_key $subrub_key<br>";
				echo "totalCount $totalCount<br>";
		*/
	}

/*
	* Function used to process the initial response in order to get the codes to get main data
	*	
	*/
   public function processResponse($body,$cat,$state,$subrub)
   {
	   $cat_id = false; $code = '';
		if(preg_match("/\{\"category_id\"\:(.*?)\}/",$body,$matches))
		{
			$cat_id = $matches[1];		
		}
		if(preg_match("/\"code\"\:\"(.*?)\"\,\"loading\"/",$body,$matches))
		{
			$code = $matches[1];			
		}
		if(preg_match("/\"totalCount\"\:(.*?)\,\"/",$body,$matches))
		{
			$totalCount = $matches[1]/2;			
		}
		$pages = ceil($totalCount/10);
		$hiPagesD = HiPagesD::where(['state'=>$state,'subrub'=>$subrub,'code'=>$code,'cat_id'=>$cat_id,'category'=>$cat])->count();
		if($hiPagesD == 0)
		{
			$hiPagesD = new HiPagesD();
			$hiPagesD->state = $state;
			$hiPagesD->subrub = $subrub;
			$hiPagesD->code = $code;
			$hiPagesD->cat_id = $cat_id;
			$hiPagesD->category = $cat;
			$hiPagesD->totalCount = $totalCount;
			$hiPagesD->save();
		}
   }
   	/*
	* Function used to getnitial response in order to get the codes to get main data
	*	
	*/
   public function test()
   {
		$st = time();

		$client = $this->h->guzzleClient;//'handyman','landscaper_gardener','plumbers','electricians','roofing','cleaning_services','excavation','plastering','concretors','demolition','painters','tilers'
		$cats = array();
		// not done ,
		$hipagesd = HiPagesD::where('category','builders')->orderBy('id','DESC')->limit(36)->get();
		
		$promises = array();
		$lp = 0 ;
		$hst = array();
		foreach($cats as $cat)
		{	
			foreach($hipagesd as $key=>$h)
			{
				try{
				
				$promises[$cat."/".$h->state."/".$h->subrub."/".$key] = $client->getAsync("https://hipages.com.au/find/$cat/".$h->state."/".$h->subrub);
				//$promises[$cat."/".$h->state."/".$h->subrub."/".$key] = $client->getAsync("https://bestquoteszone.com/top-10");
				if($lp++ == 49)
				{
						// Wait on all of the requests to complete. Throws a ConnectException
						// if any of the requests fail
						$responses = Promise\unwrap($promises);

						// Wait for the requests to complete, even if some of them fail
						$responses = Promise\settle($promises)->wait();
						$lp = 0;
						// You can access each response using the key of the promise
						foreach($responses as $key=>$result)
						{
							  if($result['state'] === 'fulfilled')
							{
								$response = $result['value'];
								if($response->getStatusCode() == 200)
								{
									$explode = explode("/",$key);
									echo $key."\n";
									$this->processResponse($response->getBody(),$explode[0],$explode[1],$explode[2]);
									unset($promises[$key]);
								}
								else{
									echo $response->getStatusCode();
								}
							}
							else if($result['state'] === 'rejected'){ // notice that if call fails guzzle returns is as state rejected with a reason.

								echo 'ERR: ' . $result['reason'];
							}
							else{
								echo 'ERR: ';
							}
						}
						//sleep(1);
						$lp = 0;
						
				}
				//if($key == 1000)
						//break;
				}catch(ExecuteRequestAsync $e)
				{
					echo $e->getMessage();break;
				}	
				
			}
			//break;
		}
		
		
		$et = time();
		echo "\nOver all time ".($et-$st)."\n";
	   
   }
   
	
   /*
     old one no need for now
   */
   
   /*
   public function hipages()
   {
	   $st = time();
				//'builders','carpenters',
		$cats = array('handyman','landscaper_gardener','painters','plumbers','electricians','tilers','roofing','cleaning_services','excavation','plastering','concretors','demolition');
		
		$hipagesd = HiPagesD::where('category','builders')->get();
		foreach($cats as $cat)
		{	
			foreach($hipagesd as $key=>$h)
			{
				
				$link = "https://hipages.com.au/find/$cat/".$h->state."/".$h->subrub;
				
				echo $key." $link "."\n";
				
					$this->h->url = $link;
					$res = $this->h->getBodyGuzzle();
					$cat_id = false;
					if(preg_match("/\{\"category_id\"\:(.*?)\}/",$this->h->body,$matches))
					{
						$cat_id = $matches[1];		
					}
					if(preg_match("/\"code\"\:\"(.*?)\"\,\"loading\"/",$this->h->body,$matches))
					{
						$code = $matches[1];			
					}
					if(preg_match("/\"totalCount\"\:(.*?)\,\"/",$this->h->body,$matches))
					{
						$totalCount = $matches[1]/2;			
					}
					$pages = ceil($totalCount/10);
					$hiPagesD = HiPagesD::where(['state'=>$h->state,'subrub'=>$h->subrub,'code'=>$code,'cat_id'=>$cat_id,'category'=>$cat])->count();
					if($hiPagesD == 0)
					{
						$hiPagesD = new HiPagesD();
						$hiPagesD->state = $h->state;
						$hiPagesD->subrub = $h->subrub;
						$hiPagesD->code = $code;
						$hiPagesD->cat_id = $cat_id;
						$hiPagesD->category = $cat;
						$hiPagesD->totalCount = $totalCount;
						$hiPagesD->save();
					}
			}
			break;
		}
		/*
		foreach($cats as $cat)
		{
			$this->category = $cat;
			$this->h->url = "https://hipages.com.au/find/".$cat;
			try{
			$res = $this->h->getBodyGuzzle();
			
			//echo $cat,"\n";

			//;
			if($this->h->body)
				$this->states($this->h->body);
			}catch(Exception $e)
			{
				$logs = new Logs();
				$logs->error = $e->getMessage();
				$logs->message = substr($e->getTraceAsString(),0,500);
				$logs->url = $this->h->url;
				$logs->fn = "hipages_main_loop";
				$logs->save();
			}
			//break;
		}
	*//*
		$withoutphones = HiPages::where('contactNo','')->select('contactNo','contactKey')->get();
		foreach($withoutphones as $withoutphone){
			$phoneJson = json_decode($this->h->getContentOnly("https://hipages.com.au/api/directory/phone?number=".$withoutphone->contactKey),true);	//	
			
				if(isset($phoneJson['phone']))
				{
					$contactNo = $phoneJson['phone'];
					HiPages::where('contactKey',$withoutphone->contactKey)->select('contactNo','contactKey')->update(['contactNo' => $contactNo]);
				}
		}
		*/
	//	HiPages::where('last_checked_at','<>',$this->todayDate)->select('last_checked_at','removed_at')->update(['removed_at' => $this->todayDate]);
/*
		$et = time();
		echo "\nOver all time ".($et-$st)."\n";
   }*/
   /*
   public function states($body)
   {
	//   echo "states\n";
	   foreach($body->find(".izGsUU a") as $state)
	   {
		   $link = "https://hipages.com.au".$state->href;
		   //echo  "\n".$link,"\n\n";
		   $this->suburbs($link);
	   } 
	}
	
	public function suburbs($link)
	{
		//echo "suburbs\n";
		$this->h->url = $link;
		try{
		$res = $this->h->getBodyGuzzle();
		$body= $this->h->body;
		foreach($body->find(".lgwsyM a") as $subrubkey=>$subrub)
	   {
		   if($subrubkey >19)
		   {
			  // if($subrubkey == 19)
			//	   break;
			   $link2 = "https://hipages.com.au".$subrub->href;
			   //echo  "\n".$link2,"\n\n";
			   $keys = explode("/",$subrub->href); 
				if(isset($keys[3]) && isset($keys[4]))
				{
					$this->state = $keys[3];
					$this->subrubKey = $keys[4];
					$this->visitBusiness($link2);
				}
		   }
	   } 
	   }catch(Exception $e)
			{
				$logs = new Logs();
				$logs->error = $e->getMessage();
				$logs->message = substr($e->getTraceAsString(),0,500);
				$logs->url = $this->h->url;
				$logs->fn = "subrubs";
				$logs->save();
			} 
	} 
	*/
	

}
