

<?php


use App\Handyimport\ProcessDocument;
$st = time();

$url = 'https://handyimport.io/';
if(isset($_GET['url']))
	$url = $_GET['url'];

$obj = new ProcessDocument($url,$extractor);  

 echo $obj->headTag; 
 echo $obj->bodyTag; 
echo "<font color=red> ".(time()-$st)." s</font>";
$classes = array();
?>
