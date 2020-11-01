<?php

namespace App\Handyimport;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use KubAT\PhpSimple\HtmlDomParser;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

use JonnyW\PhantomJs\Client as PJsClient;
class HTTPRequest 
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	 public $url;
	 public $body;
	 public $url_scheme;
	 public $guzzleClient;
	 
    public function __construct($url,$redirect = true)
    {
		if($redirect)
		{
			$this->guzzleClient = new Client([
			'headers' => [
			'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
						],
			'allow_redirects' => ['max' => 50000,'track_redirects' => $redirect],
			]); //GuzzleHttp\Client a http client to make http requests
		}
		else
		{
			$this->guzzleClient = new Client([
			'headers' => [
			'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
			'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
			'if-none-match' => 'W/"2ecb8-tMJ2H8flyLAMahRdumaf3EOynKI"',
						],
			'allow_redirects' => false
			]); //GuzzleHttp\Client a http client to make http requests

		}
		$this->url = $url;
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	public function  getContentOnly($url)
	{
		try{
			$response = $this->guzzleClient->get($url); // using the http client we are making get request to fetch the body of html page
			$content =  $response->getBody();
			return $content;
		}
		catch(ClientException $e)
		{
			return "error";
		}
		catch(GuzzleException $e)
		{
			return "error";
		}
	}
    public function getBodyGuzzle()
    {
		$stack = \GuzzleHttp\HandlerStack::create();
		$lastRequest = null;
		$stack->push(\GuzzleHttp\Middleware::mapRequest(function (\Psr\Http\Message\RequestInterface $request) use(&$lastRequest) {
			$lastRequest = $request;
			return $request;
		}));

		$this->guzzleClient = new Client([
		'headers' => [
		'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
					],
		'handler' => $stack,
		'allow_redirects' => ['track_redirects' => true],
		]); //GuzzleHttp\Client a http client to make http requests
		
		try{
			$response = $this->guzzleClient->get($this->url); // using the http client we are making get request to fetch the body of html page
			$response_code =  $response->getStatusCode(); 
			$this->url = $lastRequest->getUri()->__toString();
			$this->url_scheme = $lastRequest->getUri()->getScheme();
			
			if($this->url_scheme == "")
			{
				$this->url_scheme = "http";
			}
			
			$xmlBody =  $response->getBody()->getContents();
			
			$this->body = HtmlDomParser::str_get_html($xmlBody);  // Making dom html 
			//$this->body = $html->load($xmlBody);       // geting html body
			if($this->body)
			{
				return json_encode(array('response_type' => 'success','response_code' => '200','response_message' => 'OK'));
			}
			else
			{
				return json_encode(array('response_type' => 'error','response_code' => '200_A','response_message' => 'Something went wrong'));
			}
			
		}
		catch(GuzzleException $e)
		{
			return json_encode(array('response_type' => 'error','response_code' => '404','response_message' => 'Invalid URL or Address Provided','error_message' => $e->getMessage()));
		}
    }
    public function getBody()
    {
		$client = PJsClient::getInstance();
		$client->getEngine()->setPath('/var/www/clients/client1/web16/web/public/bin/phantomjs');  

		$client->isLazy();		
		/** 
		 * @see JonnyW\PhantomJs\Http\Request
		 **/
		$request = $client->getMessageFactory()->createRequest($this->url, 'GET');
		$request->setTimeout(5000); 
		// Adding Headers to the request
		
		$request->addHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36');
		
		
		if(substr($this->url,0,5) == "https")
		{
			$this->url_scheme = "https";
		}
		else
			$this->url_scheme = "http";
		/** 
		 * @see JonnyW\PhantomJs\Http\Response 
		 **/
		$response = $client->getMessageFactory()->createResponse();
		
		// Send the request
		$client->send($request, $response);
		
		if($response->getStatus() === 200) {
			$this->body = HtmlDomParser::str_get_html($response->getContent());
			return json_encode(array('response_type' => 'success','response_code' => '200','response_message' => 'OK'));	
		}
		else
		{
			return json_encode(array('response_type' => 'error','response_code' =>  $response->getStatus(),'response_message' => 'Something went wrong'));
		}
    }
    public function postData($data)
    {
		$params = [
		'headers' => [
		'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
		
		//'Content-Type' => 'application/x-www-form-urlencoded',
					],
		'allow_redirects' => ['track_redirects' => true],
		];
		$this->guzzleClient = new Client($params); //GuzzleHttp\Client a http client to make http requests
		//$this->url = 'http://httpbin.org/post' ;
		try{
			$response = $this->guzzleClient->post($this->url,[
			'form_params' => [$data]]); // using the http client we are making get request to fetch the body of html page
			$response_code =  $response->getStatusCode(); 
			$xmlBody =  $response->getBody();
			$this->body = HtmlDomParser::str_get_html($xmlBody);
			return $body;
			
		}
		catch(GuzzleException $e)
		{
			return json_encode(array('response_type' => 'error','response_code' => '404','response_message' => 'Invalid URL or Address Provided','error_message' => $e->getMessage()));
		}
    }

}
