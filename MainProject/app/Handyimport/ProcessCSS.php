<?php namespace App\Handyimport;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use App\ErrorCode;
use Auth;
use App\Handyimport\HTTPRequest;
class ProcessCSS
{

	/*
	* Constructor function
	*/
	public $head;
	public $body;
	public $httpRequest;
	protected $css_url;
	protected $base_url;
	protected $scheme;
	protected $ext_id;
	protected $storage_path;
	function __construct($head,$body,$scheme,$base_url,$ext_id)
	{
		$this->head = $head;
		$this->body = $body;
		$this->base_url = $base_url;
		$this->scheme = $scheme;
		$this->ext_id = $ext_id;
		$this->css_url = array();
		
		$this->httpRequest = new HTTPRequest("");
		$this->storage_path = 'tmp/Ext_'.$this->ext_id."_".Auth::user()->id;
		Storage::makeDirectory("public/".$this->storage_path);
		
		$this->traverseCSSFiles();
		$this->traverseIMGFiles();
		$this->traverseJSBodyFiles();
	//	$this->traverseJSHeadFiles();
	}

	/*
	*  parseHtmlDom is a method used to parse the return $this->dom from getBody function in order to start pre processing
	*/
			/*
	*	Traverse all kind of link css files only tags
	*/
	
	
	function traverseCSSFiles()
	{ 	
		foreach($this->head->find('link') as $loop=>$link)  
		{
			if(isset($link->href)&& preg_match('/(\.css)/',$link->href))
			{//$this->scheme."://".
				$url =$link->href;
			 	if(!preg_match("/((http:)|(https:))/",$url))
				{
					if($url[0] == "/" && $url[1] != "/")
					{
						$url = $this->getDomainOnly($this->base_url)."".str_replace("../","",$url);
					}
					else if($url[1] != "/")
					{
						$url = $this->base_url."/".str_replace("../","",$url);
					}
					else if($url[1] == "/")
					{
						$url = str_replace("../","",substr($url,2));
					}
				}
				$temp_link = $url; 
				$content = $this->httpRequest->getContentOnly($url);
				if($content != 'error')
				{
					$temp_link = $this->missingDependencies($url,$content,'css');
					$this->head = str_replace($link->href,$temp_link,$this->head);
				}
			}
			
		}
		return $this->head;
	}
	
	
	
	function traverseIMGFiles()
	{ 
	
		foreach($this->body->find('img') as $loop=>$link)  
		{
			if(isset($link->src)&& preg_match('/((\.jpg)|(\.png)|(\.bmp)|(\.jpeg)|(\.gif))/',$link->src))
			{
				$url =$link->src;
				if(!preg_match("/((http:)|(https:))/",$url))
				{
					if($url[0] == "/" && $url[1] != "/")
					{
						$url = $this->getDomainOnly($this->base_url)."".str_replace("../","",$url);
					}
					else if($url[1] != "/")
					{
						$url = $this->base_url."/".str_replace("../","",$url);
					}
					else if($url[1] == "/")
					{
						$url = str_replace("../","",substr($url,2));
					}
				}
				$content = $this->httpRequest->getContentOnly($url);
				//$this->missingDependencies($url,$content);
				//$temp_link = $this->saveFileToCache($content,'css');
				if($content != 'error')
				{
					$temp_link = $this->missingDependencies($url,$content,'jpg');
					$this->body = str_replace($link->src,$temp_link,$this->body);
				}
			}
		}
		return $this->body;
	}
	function traverseJSBodyFiles()
	{ 
		$this->body = HtmlDomParser::str_get_html($this->body);
	
		foreach($this->body->find('script') as $loop=>$link)  
		{
			if(isset($link->src)&& preg_match('/((\.js))/',$link->src))
			{
				$url =$link->src;
				if(!preg_match("/((http:)|(https:))/",$url))
				{
					if($url[0] == "/" && $url[1] != "/")
					{
						$url = $this->getDomainOnly($this->base_url)."".str_replace("../","",$url);
					}
					else if($url[1] != "/")
					{
						$url = $this->base_url."/".str_replace("../","",$url);
					}
					else if($url[1] == "/")
					{
						$url = str_replace("../","",substr($url,2));
					}
				}
				$content = $this->httpRequest->getContentOnly($url);
				
				if($content != 'error')
				{
					//$this->missingDependencies($url,$content);
					$temp_link = $this->saveFileToCache($content,'js');
					//$temp_link = $this->missingDependencies($url,$content,'js');
					$this->body = str_replace($link->src,$temp_link,$this->body);
				}
			}
		}
		return $this->body;
	}
	function traverseJSHeadFiles()
	{ 
		$this->head = HtmlDomParser::str_get_html($this->head);
		
		foreach($this->head->find('script') as $loop=>$link)  
		{
			if(!$link->prop('className')){
				if(isset($link->src)&& preg_match('/((\.js))/',$link->src))
				{
					$url =$link->src;
					if(!preg_match("/((http:)|(https:))/",$url))
					{
						if($url[0] == "/" && $url[1] != "/")
						{
							$url = $this->scheme."://".$this->getDomainOnly($this->base_url)."".str_replace("../","",$url);
						}
						else if($url[1] != "/")
						{
							$url = $this->scheme."://".$this->base_url."/".str_replace("../","",$url);
						}
						else if($url[1] == "/")
						{
							$url = str_replace("../","",substr($url,2));
						}
					}
					$content = $this->httpRequest->getContentOnly($url);
					if($content != 'error')
					{
						//$this->missingDependencies($url,$content);
						$temp_link = $this->saveFileToCache($content,'js');
						//$temp_link = $this->missingDependencies($url,$content,'js');
						$this->head = str_replace($link->src,$temp_link,$this->head);
					}
				}
			}	
			
		}
		return $this->head;
	}
	
	
	function saveFileToCache($content,$type)
	{
		if($content == "error")
			return "error";
		$file_name = $this->storage_path."/".bin2hex(openssl_random_pseudo_bytes(10)).".".$type;
		Storage::put('public/'.$file_name, $content);
		return asset('storage/'.$file_name);
	}
	

	function missingDependencies($link,$content,$type)
	{
		// match all dependencies
		preg_match_all("/(url\()(.*?)(\))/",$content,$matches);
		// if there exist any dependencies traverse through
		foreach($matches[2] as $match)
		{
			// check if it need any change if its complete url skip it
			if(!preg_match("/(http\:\/\/)/",$match) && !preg_match("/(https\:\/\/)/",$match))
			{
				$replace = $match;
				preg_match("/(\.\.\/)/",$match,$mts); // checking the path how much directories back it is
				$match = preg_replace("/(\.\.\/)/","",$match); // remove all back going links to make a clear link
				$cnt = count($mts); // back goine folders
				$pathName = $this->getPathAndFileName($link,$cnt+1); // generate a new path for current file
				$replaceWith = $pathName['path'].$match; // new dependencies link
				$temp = $this->httpRequest->getContentOnly($link);
				$content = str_replace($replace,$this->saveFileToCache($temp,$type),$content); // adding link to new dependencies
			}
		}
		return $this->saveFileToCache($content,$type);
	} 
	
	
	/*
	function traverseCSSFiles()
	{ 
		foreach($this->head->find('link') as $loop=>$link)  
		{
			if(isset($link->href)&& preg_match('/(\.css)/',$link->href))
			{
				$url =$link->href;
				if(!preg_match("/((http:)|(https:))/",$link->href))
				{
					if($url[0] == "/")
					{
						if($link->href[0] == "/")
						{
							$url = $this->scheme."://".$this->getDomainOnly($this->base_url)."".str_replace("../","",$link->href);
						}
						else
						{
							$url = $this->scheme."://".$this->base_url."".str_replace("../","",$link->href);
						}
					}
					else
					{
						if($link->href[0] == "/")
						{
							$url = $this->scheme."://".$this->getDomainOnly($this->base_url)."/".str_replace("../","",$link->href);
						}
						else
						{
							$url = $this->scheme."://".$this->base_url."/".str_replace("../","",$link->href);
						}
						
					}
				}
				$tmp_link = $this->missingDependencies($url,"css");
				$this->head = str_replace($link->href,$tmp_link,$this->head);
			}
		}
		return $this->head;
	}*//*
	function traverseJSFiles()
	{ 
		foreach($this->body->find('script') as $loop=>$link)  
		{
			if(isset($link->src)&& preg_match('/(\.js)/',$link->src))
			{
				$url =$link->src;
				if(!preg_match("/((http:)|(https:))/",$link->src))
				{
					if($url[0] == "/")
					{
						if($link->src[0] == "/")
						{
							$url = $this->scheme."://".$this->getDomainOnly($this->base_url)."".str_replace("../","",$link->src);
						}
						else
						{
							$url = $this->scheme."://".$this->base_url."".str_replace("../","",$link->src);
						}
					}
					else
					{
						if($link->src[0] == "/")
						{
							$url = $this->scheme."://".$this->getDomainOnly($this->base_url)."/".str_replace("../","",$link->src);
						}
						else
						{
							$url = $this->scheme."://".$this->base_url."/".str_replace("../","",$link->src);
						}
					}
				}
				$tmp_link = $this->missingDependencies($url,"js");
				$this->body = str_replace($link->src,$tmp_link,$this->body);
			}
		}
		return $this->body;
	}*/
	
	function getDomainOnly($url)
	{
		$uri = explode("/",$url);
		return $uri[0];
	}
	/*
	function traverseIMGFiles()
	{ 
		$this->body = HtmlDomParser::str_get_html($this->body);
		foreach($this->body->find('img') as $loop=>$link)  
		{
			if(isset($link->src)&& preg_match('/((\.jpg)|(\.jpeg)|(\.gif)|(\.png))/',$link->src))
			{
				$url =$link->src;
				if(!preg_match("/((http:)|(https:))/",$link->src))
				{
					if($url[0] == "/")
					{
						if($link->src[0] == "/")
						{
							$url = $this->scheme."://".$this->getDomainOnly($this->base_url)."".str_replace("../","",$link->src);
						}
						else
						{
							$url = $this->scheme."://".$this->base_url."".str_replace("../","",$link->src);
						}
					}
					else
					{
						if($link->src[0] == "/")
						{
							$url = $this->scheme."://".$this->getDomainOnly($this->base_url)."/".str_replace("../","",$link->src);
						}
						else
						{
							$url = $this->scheme."://".$this->base_url."/".str_replace("../","",$link->src);
						}
					}
				}
				$tmp_link =  $this->missingDependencies($url,"jpg");
				$this->body = str_replace($link->src,$tmp_link,$this->body);
			}
		}
		return $this->body;
	}
	*/
	/*
	*  handling all dependencies in the css file
	*//*
	function missingDependencies($link,$type)
	{
		// access the content of the css file
		$content = file_get_contents($link);
		// match all dependencies
		preg_match_all("/(url\()(.*?)(\))/",$content,$matches);
		// if there exist any dependencies traverse through
		foreach($matches[2] as $match)
		{
			// check if it need any change if its complete url skip it
			if(!preg_match("/(http\:\/\/)/",$match) && !preg_match("/(https\:\/\/)/",$match))
			{
				$replace = $match;
				preg_match("/(\.\.\/)/",$match,$mts); // checking the path how much directories back it is
				$match = preg_replace("/(\.\.\/)/","",$match); // remove all back going links to make a clear link
				$cnt = count($mts); // back goine folders
				$pathName = $this->getPathAndFileName($link,$cnt+1); // generate a new path for current file
				$replaceWith = $pathName['path'].$match; // new dependencies link
				$temp = file_get_contents($link);
				$content = str_replace($replace,$this->saveFileToCache($temp,$type),$content); // adding link to new dependencies
			}
		}
		return $this->saveFileToCache($content,$type);
	} 
	*/
	
	function getPathAndFileName($url,$end)
	{
		$pieces = explode("/",$url);
		$path = '';
		$fileName = $url;
		$total_pieces = count($pieces);
		if($total_pieces > 1)
		{
			foreach($pieces as $i => $piece)
			{
				if($i == ($total_pieces-$end))
				{
					$fileName = $piece;
					break;
				}
				$path .= $piece."/";	
			}
		}
		return array("file_name" => $fileName, "path" => $path);
	}
	function replaceCSSPattern($content,$pattern)
	{
		$i = 0;
		if(count($pattern) > 0)
		{
			$pattern = '/(' . implode($pattern, ')|(') . ')/';
		}		
		while(preg_match($pattern,$content,$matches))
			{
				$unwanted =  explode("}",$matches[0]);
				$unwanted2 =  explode("{",$matches[0]);
				$removeable = $unwanted[0];
				$replace_with = ";";
				if(count($unwanted) > 1 && count($unwanted2) > 1)
				{
					$removeable = str_replace("{","",$unwanted[0]);
					$removeable = str_replace(";","",$unwanted[0]);
					$replace_with = '';
				}
				else if(count($unwanted) > 1){
					$removeable = str_replace(";","",$unwanted[0]);
					$replace_with = '';
				}
				else if(count($unwanted2) > 1)
				{
					$removeable = $unwanted2[1];
					$replace_with = '';
				}
				
				$i++;
				//echo "$i = $matches[0] <br>";
				//echo "$i = $removeable <br>";
				$content = str_replace($removeable,$replace_with,$content);
				
				if($i > 500) break;
			}
			echo $content;
		return $content;	
	}
	
	function removeAnimations()
	{

	}
} // end of class


?>