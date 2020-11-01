<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extractor;
class DataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

	public function removeall()
	{
		Extractor::truncate();
	}
	public function columnUpdate(Request $request)
	{
		/*
		ext_id: 1
		user_id: 1
		_token: cftnNDuRHpsi2hXYi1BCEUASZN4p0kJza3pNELzS
		change_identifier: column_1
		editable_column_name_hi: Column 11
		action: edit
		*/
		if($request->_token == csrf_token() or true)
		{
			$extractor = Extractor::where(['user_id' => $request->user_id,'ext_id' => $request->ext_id])->first();
			
			if(count((array)$extractor) > 0 )
			{
				$ext_bot_data = json_decode($extractor->ext_bot,true);
				
				
				
				if($request->action == 'edit')
				{
					
					try{
						$ext_bot_data[$request->change_identifier]['name'] = $request->editable_column_name_hi;
						$extractor->ext_bot = json_encode($ext_bot_data);
						$extractor->save();
					}catch(Exception $e)
					{
						echo $e->getMessage();
					}
				}
				else if($request->action == 'delete')
				{
					$data_saved = json_decode($extractor->ext_data,true);
					
					try{
						unset($ext_bot_data[$request->change_identifier]);
						unset($data_saved[$request->change_identifier]);
						$extractor->ext_bot = json_encode($ext_bot_data);
						$extractor->ext_data = json_encode($data_saved);
						$extractor->save();
					}catch(Exception $e)
					{
						echo $e->getMessage();
					}
				}
				
			}
			else
			{
				echo 'Unauthorized Request';
			}
		}
		else
		{
			echo 'Invalid Request';
		}
	}
	
	public function dataUpdate(Request $request)
	{
		if($request->_token == csrf_token() or true)
		{
			$extractor = Extractor::where(['user_id' => $request->user_id,'ext_id' => $request->ext_id])->first();
			
			if(count((array)$extractor) > 0 )
			{
				$ext_bot_data = json_decode($extractor->ext_bot,true);
				$data_saved = json_decode($extractor->ext_data,true);
				if($request->action == 'edit')
				{
					
					try{
							if(!isset($ext_bot_data[$request->change_identifier]['bot']))
							{
								$ext_bot_data[$request->change_identifier]['bot'] = [];
							}

							$data_for_bot1 =$ext_bot_data[$request->change_identifier]['bot'];
							$bot_hi_count = count((array)$data_for_bot1);
							
							$unique_ids = $request->unique_id_hi_element;
							$unique_id_texts = $request->data_text_hi_element;
							$ik=0;
							foreach($unique_ids as $uid)
							{
								$data_for_bot1['d'.$bot_hi_count++] = $uid;
								$data_saved[$request->change_identifier]['data']['p1'][$uid] = $unique_id_texts[$ik++];
							}
							
							if($bot_hi_count > 1)
							{
								$data_for_bot1 = array_unique($data_for_bot1);
							}
							$ext_bot_data[$request->change_identifier]['bot'] = $data_for_bot1;
							
							$extractor->ext_data = json_encode($data_saved);
							$extractor->ext_bot = json_encode($ext_bot_data);
							$extractor->save();
							echo "add/edit done";
						}catch(Exception $e)
						{
							echo $e->getMessage();
						}
				}
				else if($request->action == 'delete')
				{
					
					try{
							unset($data_saved[$request->change_identifier]['data']['p1'][$request->unique_id_hi_element]);
							
							foreach($ext_bot_data[$request->change_identifier]['bot'] as $key=>$bot)
							{
								if($bot == $request->unique_id_hi_element)
								{
									echo " useting id ".$key;
									unset($ext_bot_data[$request->change_identifier]['bot'][$key]);
								}
							}							
							
							
							$extractor->ext_bot = json_encode($ext_bot_data);
							$extractor->ext_data = json_encode($data_saved);
							$extractor->save();
							echo "deleted";
						}catch(Exception $e)
						{
							echo $e->getMessage();
						}
				}
			}
			else
			{
				echo 'Unauthorized Request';
			}
		}
		else
		{
			echo 'Invalid Request';
		}
		
	}
   
   public function getExtractor($id)
   {
	   $extractor = Extractor::where(['ext_id' => $id])->first();
	   
	   echo "<br><br>Bot Data or Trainer For Page<br><br>";
	   
	   print_r( $extractor->ext_bot);

	   echo "<br><br>Extracted Data<br><br>";
	   print_r( $extractor->ext_data);

   }
}
