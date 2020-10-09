<style>
.MySampleClassAddedUsefull:hover
{
	border:1px solid red;
}
.io_text
{
	border:2px solid green;
	margin:0px !important;
	padding:0px !important;
}
.io_text:hover
{
	border:2px solid blue;
	margin:0px !important;
	padding:0px !important;
}

<?php
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

$features_tags = array(
"a", 
"h1",
"h2",
"h3",
"h4",
"h5",
"h6",
"b",
"i",
"u",
"strike",
"s",
"pre",
"code",
"tt",
"blockquote",
"q",
"small",
"font",
"center",
"em",
"strong",
"abbr",
"acronym",
"address",
"bdo",
"big",
"cite",
"del",
"dfn",
"ins",
"kbd",
"samp",
"span",
"sup",
"sub",
"var",
);

	$client = new Client(); //GuzzleHttp\Client
	
	$url = "https://aimfox.net";
	if(isset($_GET['url']))		
		$url = $_GET['url'];
	$result = $client->get($url);
	
	
	$body_str =  $result->getBody();
	/*
	 * Remove all comments from the body string 
	*/
	$body_str =  preg_replace("/<!--[^>]*-->/","",$body_str);
	$document = HtmlDomParser::str_get_html($body_str);
	
	$head = $document->find('head',0);
	$body = $document->find('body',0);
	echo $head;
	getTag($body,$features_tags);
	echo $body;
?>


<div id="handyimport_scrap">

<?php 
function getTag($html,$f)
{
	//echo "<br><br><br>",$html,"<br><br><br>";
	if(count($html->find('*')) == 0) 
	{
		$class = $html->getAttribute('class');
		$class = str_replace("MySampleClassAdded","",$class);
		
		$class .= '  ';
		$html->setAttribute('class',$class);
		$html->innertext = "<span class='io_text'>".$html->innertext."</span>";
		$data = strip_tags($html->innertext);
		$predected_text = '/^([A-Za-z0-9\:\;\'\"`\-\=\t\s\+\_\)\(\*\&\^\%\$\#\@\!\~\{\}\[\]\\\|\?\/\.\,]){1,}$/';  
		 
		if(preg_match($predected_text,$data) && !preg_match("/^([\s]){1,}$/",$data) )
		{
			//echo "<br><font color=green>use full data </font> "."(",$data,")<br><br>";
		}
		else if(!preg_match("/^([\s]){0,}$/",$data) )
		{
			//echo "<br><font color=yellow>may be use full data </font> "."(",$data,")<br><br>";
		}
		else
		{
			//echo "<br><font color=red>seems not usefull </font> "."(",$data,")<br><br>";
		}
		return;
	}
		
	foreach($html->find('*') as $i=>$tag)
	{
		//echo "<font color=red>Not use full data </font> ".$tag->tag,"<br>";
		if($tag->tag == "a")
		{
			$tag->setAttribute("onclick","return false");
			$tag->setAttribute("ondblclick","location=this.href");
		}
		
		if($tag->tag != "script")
		{
			if(in_array($tag->tag, $f))
			{
				$class = $tag->parent()->getAttribute('class').' MySampleClassAddedUsefull ';
				$tag->parent()->setAttribute('class',$class);
			}
			$class = $tag->getAttribute('class').' MySampleClassAdded ';
			$tag->setAttribute('class',$class);
			//$tag->setAttribute('onclick','myfunction(this);');
			getTag($tag,$f);
		}

	}
}


 ?>

	
</div>


	<script>
		
	$(function($) {
    $('#handyimport_scrap a').click(function() {
		alert("clicked");
        return false;
    }).dblclick(function() {
        window.location = this.href;
        return false;
    }).keydown(function(event) {
        switch (event.which) {
            case 13: // Enter
            case 32: // Space
                window.location = this.href;
                return false;
        }
    });
});​	


	</script>

