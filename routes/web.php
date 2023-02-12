<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Lib\SendSMS;
use App\Handyimport\HTTPRequest;
use Illuminate\Support\Facades\Storage;
Auth::routes();
use App\Extractor;
use App\Handyimport\ProcessDocument;
use JonnyW\PhantomJs\Client;

public function getDataSaveUrl($url)
	{
		$this->httpRequest->url = $url;
		$tmp_file_body_load = "public/extractors/ext_".rand(1000,9999)."_".Auth::user()->id.".html";
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

	
//public_path()
Route::get('/p', function(){
	$url = request()->url;
	$reply_link = $this->getDataSaveUrl($url);
		
	$url =$this->httpRequest->url;
	
	if($reply_link['storage_link'] != "ERROR")
		{
			echo $url . $reply_link['public_link'];
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
				{}
			//Storage::delete($reply_link['storage_link']);
		}

	return;
	$st = time()+microtime();
	$url = "https://www.google.com/flights?hl=en&gl=al#flt=/m/04jpl.KEF.2019-12-05*KEF./m/04jpl.2020-01-31;b:1;c:GBP;e:1;a:U2*U2;px:2;sd:1;t:f";
    if(isset($_GET['url']))
	{
		$url = $_GET['url'];
	}
	$h = new HTTPRequest($url);
	$tmp_file = "public/extractors/ext_x_1.html";
	
	$h->getBodyGuzzle();
	
		$fileContents = 
			"<!DOCTYPE html>
			<html>".
			$h->body.
			"</html>";
	Storage::put($tmp_file, $fileContents);
	$tmp_lnk = url(str_replace("public","storage",$tmp_file));

	 $extractor = Extractor::where(['ext_id' =>  23])->first();
	
	$processDocument = new ProcessDocument($tmp_lnk,$extractor,true);
	
	//print_r( $processDocument->resp_loop);
	$fileContents = 
			"<!DOCTYPE html>
			<html>".
			$processDocument->headTag.
			$processDocument->bodyTag.
			"</html>";
			
			echo $fileContents;
	$et = time()+microtime();
	
	echo $et-$st;
});
Route::get('/test-url', function(){
//print_r( session('current_resp','nothing'));
	//return;
	$httpRequest = new HTTPRequest($_GET['url']);
	echo $httpRequest->getContentOnly($_GET['url']);
})->name('test');

/**
* Used in builder for extractors
*/
Route::post('/update_extractors', 'DataController@dataUpdate');
Route::post('/update-column', 'DataController@columnUpdate');  

Route::get('/g/{id}', 'DataController@getExtractor');  

Route::get('/e/test', 'ManualExtractorController@test')->name('manual_extractor_show');
Route::get('/e/manual', 'ManualExtractorController@index')->name('manual_extractor_show');
Route::get('/e/auto', 'AutoExtractorController@index')->name('auto_extractor_show');


Route::get('/', 'HomeController@index')->name('dashboard');
Route::get('/home', 'HomeController@index')->name('home');

// Official Routes

Route::post('/user/extractor/test_gui','UserExtractorController@buildExtractorjQuery')->name('test_extractor_gui');

Route::get('/user/extractor/new', 'UserExtractorController@addNewShow')->name('new_extractor');
Route::post('/user/extractor/new', 'UserExtractorController@addNew')->name('new_extractor_post');
Route::get('/user/extractor/{id}/build', 'UserExtractorController@buildExtractor')->name('new_extractor_build');
Route::get('/user/extractor/{id}/builder', 'UserExtractorController@buildExtractorShow')->name('new_extractor_builder');


Route::get('/user/extractor/list', 'UserExtractorController@listExtractors')->name('list_extractors');
Route::get('/user/extractor/{id}/settings', 'UserExtractorController@extractorSettings')->name('extractor_settings');
Route::get('/user/extractor/{id}/settings2', 'UserExtractorController@extractorSettings2')->name('extractor_settings2');
Route::get('/user/extractor/{id}/settings/save', 'UserExtractorController@extractorSettingsSave')->name('extractor_settings_save');


Route::get('/user/extractor/{id}/run', 'UserExtractorController@runExtractor')->name('extractor_run');
Route::get('/user/extractor/{id}/data', 'UserExtractorController@extractorDataPage')->name('extractor_data');
Route::get('/user/extractor/{id}/ajax-data/{uid}', 'UserExtractorController@extractorData')->name('extractor_data_ajax');

Route::get('/user/extractor/{id}/activate/{uid}', 'UserExtractorController@activateExtractor')->name('activate_extractor');

Route::post('/user/extractor/delete', 'UserExtractorController@deleteExtractor')->name('delete_extractor');

Route::get('/activities/{type}', 'UserExtractorController@listActivities')->name('list_activities');
Route::get('/test_graphs', 'HomeController@test_graphs')->name('test_graphs');
Route::get('/getAnalytics', 'HomeController@getAnalytics')->name('getAnalytics');

Route::get('/user/application/list', 'UserAPIController@listApps')->name('list_apps');
Route::post('/user/application/list', 'UserAPIController@createApp')->name('add_app');
Route::post('/user/application/delete', 'UserAPIController@deleteApp')->name('delete_app');


Route::get('/verify/phone', 'VerifyController@showPhoneVerify')->name('show_phone_verify');
Route::post('/verify/phone', 'VerifyController@isValidPhoneCode')->name('match_verification_phone');
Route::post('/verify/phone/get', 'VerifyController@sendVerifyPhone')->name('send_phone_verification');

Route::get('/verify/email', 'VerifyController@showEmailVerify')->name('show_email_verify');;
Route::post('/verify/email', 'VerifyController@sendVerifyEmail')->name('send_email_verification');
Route::get('/verify/email/{token}', 'VerifyController@isEmailValid');

// Manually done jobs 

//Route::get('/e/hipages', 'CustomController@hipagesCount');


// Packages Management


Route::get('/subscriptions', 'PackageController@getSubscriptions')->name('getSubscriptions');
Route::get('/packages/list', 'PackageController@getPackages')->name('get_packages');
Route::get('/order-page', 'PackageController@orderPage')->name('order_page');
Route::post('/payment-success', 'JazzCashController@onHostedPaymentResponse')->name('on_jazzcash_response');
