<?php namespace App\Handyimport;


use KubAT\PhpSimple\HtmlDomParser;

use App\ErrorCode;

use App\Handyimport\HTTPRequest;
use App\Handyimport\ProcessCSS;
class ProcessDocument
{
	public $dom;
	protected $extractor;
	protected $html;
	public $bodyTag;
	public $headTag;
	protected $url;
	protected $tmp_url;
	
	protected $guzzleClient;
	protected $response;
	public $resp_loop;
	protected $unique_id;
	protected $new_dom;
	protected $auto_bot;
	protected $visual_builder;
	public $bot_data_tags;
	
	/*
	* Constructor function
	*/
	
	function __construct($url,$tmp_url,$extractor,$auto_bot = false,$visual_builder = true)
	{
		$this->tmp_url = $this->cleanBaseURL($tmp_url);		
		$this->url = $url;		
		$this->response = array();
		$this->unique_id = 5;
		$this->extractor = $extractor;
		$this->auto_bot = $auto_bot;
		$this->visual_builder = $visual_builder;
		$this->startProcessing();
	}
	
	/*
	*  getBody is a method used to fetch the html page and converted it to html dom
	*/
		public function cleanBaseURL($url)
    {
		$url = strtolower($url);
		//$url = str_replace("https://","",$url);
		//$url = str_replace("http://","",$url);
		
		if(substr($url,-1,1) == "/")
		{
			$url = substr($url,0,strlen($url)-1);
		}
		
        return $url;
    }
	
	function startProcessing()
	{
		
		try{
			$req = new HTTPRequest($this->tmp_url);
			
			$resp = json_decode($req->getBodyGuzzle(),true);
		
			
			if(isset($resp['response_code']) && $resp['response_code'] == '200')
			{
				$this->resp_loop['response_code'] = 200;
				$this->dom = $req->body;
				/*
				*  Parse the obtained html dom into body and head tags
				*/
				
				$this->parseHtmlDom();
	
			
				/*
				*  Traverse complete body tree to add classes and custom tags
				*/
				
				$this->traverseBodyTree($this->bodyTag);
				
				$this->addStylesAndScripts();
				
							// This only should work if there is no visual display
				if($this->visual_builder)
				{
					 $this->baseURLCheck();
					$css = new ProcessCSS($this->headTag,$this->bodyTag,$req->url_scheme,$this->url,$this->extractor->ext_id);
					$this->headTag = $css->head;
					 $this->bodyTag = $css->body;
				}
			}
			else
			{
				$this->resp_loop['response_code'] = 400;
			}
		
			return $this->bodyTag;  
			
		}
		catch(GuzzleException $e)
		{
			/*
			 * comment bellow line in production mode
			*/
			print_r($this->error($e));
			
			/*
			 * un-comment bellow line in production mode
			*/
			//$this->error($e);
		}
	}
	
	/*
	*  baseURLCheck is a method used to parse the return $this->dom from getBody function in order to start pre processing
	*/
		
	function baseURLCheck()
	{
		if(!$this->headTag->find('base')){
			$this->headTag->innertext = '<base  href="'.$this->url.'" target="_blank">'.$this->headTag->innertext;
		}
		if(1)
		{	/*
			$var = '
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>';
			$this->bodyTag->outertext = $this->bodyTag->makeup() . $this->bodyTag->innertext . $var . '</body>';*/
			
		}
	}
	/*
	*  parseHtmlDom is a method used to parse the return $this->dom from getBody function in order to start pre processing
	*/
		
	function addStylesAndScripts()
	{
		$host = "https://hi.babar.work";
		$tmp_ext_data['column_1']['data'] = array();

		$var = '' ;
		
		if($this->auto_bot)
		{
			$tmp_ext_data = json_decode($this->extractor->ext_data,true);
			foreach($tmp_ext_data as $x=>$column_x)
			{
				$tmp_ext_data[$x]["data"] = $column_x["data"]["p1"];
			// if(isset($tmp_ext_data['column_1']['data']['p1']))
				// $tmp_ext_data['column_1']['data'] = $tmp_ext_data['column_1']['data']['p1'];
			// else
				// $tmp_ext_data['column_1']['data'] = array();
			}
			if(count((array)$tmp_ext_data) == 0)
			{
				$tmp_ext_data['column_1']['data'] = array();
			}
			$var.='<script>
			var auto_bot_mode = true;
			var bot_data_extracted = '.json_encode($tmp_ext_data).';
			var bot_data_extracted_bot = '.$this->extractor->ext_bot.';
			</script>';
		}
		else
		{
			$var.='<script>var auto_bot_mode = false;</script>';
		}
		/*
		$var .= '<script>
		if (typeof(jQuery) == "undefined") {  
			document.write("");
		}
		</script>';
		*/
		$var .= ' 
		
		<div id="hi-7256577-load-data"></div>' ;
		$var .= '

		<div id="carrier_9493373_float">
		</div>
		
		
		
		<script>
		
		var ext_id = '.$this->extractor->ext_id.';
		var ext_draft = '.$this->extractor->ext_draft.';
		var csrf_token = "'.csrf_token().';"
		
		</script>
		<div id="add_the_csrf_token"></div>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script class="hi_null" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script  class="hi_null" src="'.$host.'/theme/files/assets/pages/edit-table/jquery.tabledit.js"></script>
		<script  class="hi_null" src="'.$host.'/theme/custom/js/manual/dynamic_processor.js"></script> 
		<script  class="hi_null" src="'.$host.'/theme/custom/js/manual/data_mp.js"></script>
		<script  class="hi_null" src="'.$host.'/theme/custom/js/manual/data_db.js"></script>
		';
		
		if($this->auto_bot)
		{
			$this->bot_data_tags = json_decode($this->extractor->ext_bot,true);			
		}
		
		$var2 = '
		<link href="'.$host.'/theme/custom/css/manual/data_mp.css" rel="stylesheet" type="text/css" media="all">
		<link href="'.$host.'/theme/custom/css/manual/data_db.css" rel="stylesheet" type="text/css" media="all">
		';
		$this->bodyTag->outertext = $this->bodyTag->makeup() . $this->bodyTag->innertext . $var . '</body>';
		$this->headTag->outertext = $this->headTag->makeup() . $this->headTag->innertext . $var2 . '</head>';
	}
	/*
	*  parseHtmlDom is a method used to parse the return $this->dom from getBody function in order to start pre processing
	*/
		
	function parseHtmlDom()
	{
		/*
		* While parsing we check if the html contains body and head if yes return other wise return with empty tag
		* to avoid any error
		**/
		
		
		if($this->dom->find('head',0))
			$this->headTag = $this->dom->find('head',0);
		else
			$this->headTag = str_get_html('<head></head>');
		
		if($this->dom->find('body',0))
			$this->bodyTag =$this->dom->find('body',0);
		else
			$this->bodyTag = str_get_html('<body>Body Not Found</body>');
		
		return $this->success("HI_103","data parse done");
	}
	
	/*
	*  success method return a generated response for success response
	*/
		
	function success($c,$m)
	{
		
		$this->response['response_type'] 	=  "success";
		$this->response['response_code'] 	=  $c;
		$this->response['response_message'] =  $m;
				
		return $this->response;		
	}
	/*
	*  error method check the current error by its code and return a generated response
	*/
		
	function error($e)
	{
		if($e->getResponse())
			{
				$this->error = ErrorCode::where(['error_code' => $e->getResponse()->getStatusCode()])->first();
				if(count((array)$this->error) > 0)
				{
					$this->response['response_type'] 	=  "error";
					$this->response['response_code'] 	=  $this->error->error_code;
					$this->response['response_message'] =  $this->error->error_message_default;
				}
				else
				{
					$this->response['response_type'] 	=  "error";
					$this->response['response_code'] 	=  "HI_100";
					$this->response['response_message'] =  "something went wrong";
				}
			}
			else
			{
				$this->response['response_type'] 	=  "error";
				$this->response['response_code'] 	=  "HI_101";
				$this->response['response_message'] =  "invalid url provided or url could not found";
			}
			
		return $this->response;		
	}
	
	
	/*
	*  traverseBodyTree is a method that traverse all the elements in dom of body...
	*/
		
	function traverseBodyTree($tags)
	{
		if(count($tags->find('*')) == 0 || $tags->tag == 'p') 
		{
			/*
			if(!preg_match('/(io_text)/',$tags->parent()->getAttribute('class')))
			{
				$class = $tags->parent()->getAttribute('class').' io_text ';
				$tags->parent()->setAttribute('class',$class);
			}
			*/
			$tags = $this->processInnerTag($tags);
			
		}
		else
		{
			foreach($tags->find('*') as $i=>$tag)
				{
					
					
					
					//echo "<font color=red><h3>".$tag->tag,"</h3></font> => ";
					foreach ($tag->getAllAttributes() as $attr => $value)
					{
						//echo $attr," = ",$value,"<br>";
					}
					
					/*
					*  Handling all href link in tag <a>
					*/
					
					if($tag->tag == "a")
						{
							$tag->setAttribute("onclick","return false;");
							$tag->setAttribute("ondblclick","location=this.href;");
							$tag->setAttribute("href","#");
						}
						
					/*
					*  skip the un neccessory tags <script>
					*/
						
					if($tag->tag != "script")
						{
							//if(in_array($tag->tag, $features_tags) )
							$this->traverseBodyTree($tag);
						}	
							
				}
		}
	}

	
	function processInnerTag($tag) 
	{
		$class = "io_text";
		
		if($tag->innertext != '' && $tag->tag != "body")
		{
			$tag->setAttribute('data-hi_id','hieid'.$this->unique_id++);
			$tag->setAttribute('class',$class);
		}
		else
		{
			if($tag->parent()->tag != 'body' )
			{
				$tag->parent()->setAttribute('class',$class);
				$tag->parent()->setAttribute('data-hi_id','hieid'.$this->unique_id++);
			}
		}
		$explode = explode(' ',$tag->innertext);
		$n_explode = array();
		foreach($explode as $exp)
		{
			if($tag->parent()->tag != 'button' )
			{
				
					$n_explode[]  = "<span class='io_text'>".$exp."</span>";
				
			}
		}
		//$tag->innertext = implode(' ',$n_explode);
		//echo "<br><br><b><font color=red>$tag->plaintext</font></b><br><br>";
		return $tag;
	}
	
} // end of class


?>