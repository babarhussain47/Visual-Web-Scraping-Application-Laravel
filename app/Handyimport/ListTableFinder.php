<?php namespace App\Handyimport;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use App\ErrorCode;
class ListTableFinder
{

	/*
	* Constructor function
	*/

	public $body;
	protected $css_url;
	function __construct($head,$body)
	{
		$this->body = $body;
	}

	/*
	*  parseHtmlDom is a method used to parse the return $this->dom from getBody function in order to start pre processing
	*/
	
} // end of class


?>