<?php namespace App\Handyimport;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use App\ErrorCode;
class PhoneExtractor
{

	/*
	* Constructor function
	*/
	public $html;
	public $phones;
	function __construct($html)
	{
		$this->html = $html;
		$this->phones = array();
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
		
		$patterns = array(
		'(((\+)|(00)){0,}((\s)|(-)){0,}((92)|(\((\+){0,1}92\))){0,})((\s)|(-)){0,}((\(0\)|(0)){0,1}((\s)|(-)){0,}3)[0-4][0-9]((\s)|(-)){0,}([0-9]){7}',
		'(((\+)|(00)){0,}((\s)|(-)){0,}((92)|(\((\+){0,1}92\))))((\s)|(-)){0,}((\(0\)|(0)){0,1}((\s)|(-)){0,}[0-9])[0-9]((\s)|(-)){0,}([0-9]){7,8}',
		'((\(0\)|(0)){1}((\s)|(-)){0,}[0-9])[1-9]((\s)|(-)){0,}([0-9]){7,8}',
		'[1]{3}((\s)|(-)){0,}([0-9]((\s)|(-)){0,}){6}',
		'(0)[8-9](00)((\s)|(-)){0,}([0-9]((\s)|(-)){0,}){5}'
		);
		 
		$regex = '/(' .implode('|', $patterns) .')/i'; 
		preg_match_all($regex,$this->html,$phones);
		$phones = $phones[0] ; 
		foreach($phones as $i=>$phone){
			$phone = str_replace(" ","",$phone);
			/*$phone = str_replace("-","",$phone);
			$phone = str_replace("(","",$phone);
			$phone = str_replace(")","",$phone);*/
			$this->phones[$i] = str_replace(" ","",$phone);
		}
		$this->phones = array_unique($this->phones) ; 
		return $this->phones;
		 
	}
	
}
	 // end of class


?>