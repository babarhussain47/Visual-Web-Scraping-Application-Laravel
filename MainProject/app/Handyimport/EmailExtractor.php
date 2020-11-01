<?php namespace App\Handyimport;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use KubAT\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use App\ErrorCode;
use App\Handyimport\Ping;
class EmailExtractor
{

	/*
	* Constructor function
	*/
	public $html;
	public $emails;
	function __construct($html)
	{
		$this->html = $html;
		$this->emails = array();
		$this->find();
	}

	/*
	*  parseHtmlDom is a method used to parse the return $this->dom from getBody function in order to start pre processing
	*/
			/*
	*	Traverse all kind of link css files only tags
	*/
	function find()
	{
		
		$patterns = array('/[0-9a-zA-Z_\-\.]{1,}@[a-zA-Z_\-]+?\.[a-zA-Z\.]{2,}/');
		preg_match_all($patterns[0],$this->html,$emails);
		$emails = array_unique($emails[0]) ; 
		//print_r($emails);
		
		foreach($emails as $i=>$email){
			$email_parse = explode("@",$email);
			$this->emails[$i]['e'] = $email;
			$this->emails[$i]['v'] = $this->ping($email_parse[1]);
		}
		return $this->emails;
		
	}
	
	// Function to check response time

	function ping($host, $port =80, $timeout = 5) {
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
	
	
} // end of class


?>