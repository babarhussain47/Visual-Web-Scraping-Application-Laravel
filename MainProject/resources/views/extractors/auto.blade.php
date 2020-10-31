

<?php


use App\Handyimport\EmailExtractor;
use App\Handyimport\PhoneExtractor;
use App\Handyimport\ProcessDocument;
$st = time();

$url = 'https://aimfox.net/contact-us';

if(isset($_GET['url']))
	$url = $_GET['url'];


$obj = new ProcessDocument($url,$extractor);  
			$email_extractor = new EmailExtractor($obj->bodyTag);
			print_r($email_extractor->emails);
			
			$phone_extractor = new PhoneExtractor($obj->bodyTag);
			print_r($phone_extractor->phones);
 // echo $obj->headTag; 
 // echo $obj->bodyTag; 
echo "<font color=red> ".(time()-$st)." s</font>";
?>

