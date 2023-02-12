

<?php

include('client.extractor.empty_dialog')
use App\Handyimport\ProcessDocument;
use App\Extractor;
$extractor = Extractor::where('ext_id',$ext_id)->first();

if(count($extractor) > 0)
{
	$json_data = array();
	for($i=1;$i<5;$i++)
	{
		$url = "http://books.toscrape.com/catalogue/page-$i.html";
		
		$obj = new ProcessDocument($url,$extractor,true);  
		foreach($obj->bot_data_tags as $col_name => $tmp_li)
		{
			$col_data  = array();
			foreach($tmp_li['bot'] as $bx){
				$b = "[data-hi_id=".$bx."]";
				$col_data[$bx] = strip_tags($obj->bodyTag->find($b,0));
			}
			$json_data[$col_name]["data"]["p$i"] = $col_data;
		}
	}
	$extractor->ext_data = json_encode($json_data);
	$extractor->save();
	echo "Run Succeeded";
}
else
{
	echo "Invalid Request";
}

