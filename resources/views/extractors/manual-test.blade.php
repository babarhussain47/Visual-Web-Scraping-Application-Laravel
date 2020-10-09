

<?php


use App\Handyimport\ProcessDocument;
$st = time();

if(isset($_GET['url']))
	$url = $_GET['url'];

$obj = new ProcessDocument($url,$extractor,true);  

  echo $obj->headTag; 
  echo $obj->bodyTag; 
  /*
echo "<font color=red> ".(time()-$st)." s</font>";
$classes = array();
$array_bot_data = json_decode($extractor->ext_bot,true);
$data_of_unique_ids = [];

foreach($array_bot_data as $ar)
{
	foreach($ar['bot'] as $b){
	$data_of_unique_ids[] = $b;
	}
}
?>

<script>
$( document ).ready(function(){
	<?php foreach($data_of_unique_ids as $ii){?>
	$("[data-hi_id="+<?php echo $ii;?>+"]").removeClass("io_text");
	$("[data-hi_id="+<?php echo $ii;?>+"]").addClass("io_text_saved");
	
	<?php }?>
});
</script>
