/*
	For laravel post request we need to embed the token in header in order to make request
*/
	
// add column carrier

	var auto_suggest = false;

	var col_carry_9493373 = '<div class="hi-col-9493373" id="hi-col-9493373">'+
			'<div id="hi-col-9493373-head" class="hi-col-9493373-head">'+
				'Columns List'+
			'</div>'+
			'<div class="hi-col-9493373-action" id="hi-col-9493373-add-col">'+
			'Add New Column'+
			'</div>'+
			'<div id="hi-col-9493373-body" class="hi-table-9493373-tbody">'+
			
			'<table id="hi_table_column_carrier" class="table table-striped table-bordered nowrap">'+
				'<tbody id="hi-col-9493373-tbody">'+
				'<input class="tabledit-input form-control input-sm" type="hidden" name="ext_id" value="'+ext_id+'">'+
				'<input class="tabledit-input form-control input-sm" type="hidden" name="user_id" value="1">'+
				'<input class="tabledit-input form-control input-sm" type="hidden" name="_token" value="'+csrf_token+'">'+
				'<input class="tabledit-input form-control input-sm" type="hidden" id="_token" value="'+csrf_token+'">'+
			'</tbody>'+
			'</table>'+
			'</div>'+
		'</div>';
	var setting_carry_9493373 = '<div id="hi-setting-9493373">'+
			'<div id="hi-setting-9493373-head" class="hi-setting-9493373-head">'+
				'<img width="25px" src="/img/move.png">'+
			'</div>'+
			'<div id="hi-setting-9493373-body" >'+
				'<button class="mybtn mybtn-success" id="activate_btn" onclick="activateNow(this)">Activate</button>'+
				'<button class="mybtn mybtn-inverse" onclick="showSetting()">Settings</button>'+
				'<button class="mybtn mybtn-danger" onclick="deleteExtractor()">Delete</button>'+
				'<button class="mybtn mybtn-warning" id="autosuggest" onclick="autoSuggestion(this)">Auto Suggestion Disabled</button>'+
			'</div>'+ 
		'</div>';
		
	$('#carrier_9493373_float').append(setting_carry_9493373);	
	$('#carrier_9493373_float').append(col_carry_9493373);	
	$( "#hi-setting-9493373" ).draggable();
	$("#hi-col-9493373-add-col").click(function(){
		onAddSwitchHi();
		addNewHIColumnIdCarrier();
		addColumnHiDb();
	});	
		
		var c_s_col_9493373_hi = 0;
		var total_added_col_9493373_hi = 0;
		
		var unique_id_hi_element = {};
		var data_text_hi_element = {};
		
		var user_id = '1';
		var _token = $("#_token").val();
guiUpdateHi();
// for io text that need to be extracted
	function guiUpdateHi(){
		jQuery(function($) {
	   $(".io_text").hover(function(){
		   $(".io_text").removeClass("io_text_hover");
			$(this).addClass("io_text_hover");
			console.log("Hovered ",$(this).text());
			ind_path = 0;
			path = [];
			path_ind = [];
			if(auto_suggest)
			{
				checkParent($(this),$(this));
			}
			else
			{
				singleExtract($(this),false);
			}
		  
		}, function () {
			
			$(".io_text_hover").removeClass("io_text_hover");
		}).one('click',function(event){
			event.stopImmediatePropagation();
			
			ind_path = 0;
			path = [];
			path_ind = [];
			if(auto_suggest)
			{
				checkParent($(this),$(this),true);
			}
			else
			{
				singleExtract($(this),true);
			}
		});
	}); 
	}
function saveClickedDataDirectly()
{
	
}

var col_selected_id_9493373 = 0;
var selected_id = "hi-col-9493373-column_"+col_selected_id_9493373;
function addNewHIColumn()
{
	var selected_id = "hi-col-9493373-column_"+col_selected_id_9493373;
	var new_column_place = '<div class="hi-col-9493373" id="hi-col-9493373-column_'+col_selected_id_9493373+'">'+
			'<div class="hi-col-9493373-head" id="hi-col-9493373-head_'+col_selected_id_9493373+'">'+
			'</div>'+
			'<div class="hi-col-9493373-action">'+
			'Action Bar'+
			'</div>'+
			'<div class="hi-table-9493373-tbody">'+
			
			'<table id="hi_table_current_data_'+col_selected_id_9493373+'" class="table table-striped table-bordered nowrap">'+
				'<tbody id="hi_table_data_col_'+col_selected_id_9493373+'">'+
				'<input class="tabledit-input form-control input-sm" type="hidden" name="ext_id" value="'+ext_id+'">'+
				'<input class="tabledit-input form-control input-sm" type="hidden" id="_token" value="'+csrf_token+'">'+
			'</tbody>'+
			'</table>'+
			'</div>'+
		'</div>';
		
		$('#carrier_9493373_float').append(new_column_place);
		col_selected_id_9493373++;
		total_added_col_9493373_hi++;
		
    $( ".hi-col-9493373" ).draggable();
    $( ".hi-col-9493373" ).hide();
    $( "#hi-col-9493373" ).show();
    $( "#"+selected_id ).show();
}


function addNewHIColumnIdCarrier()
{
	
	var insert_col_data_hi = '<tr id="column_'+col_selected_id_9493373+'">'+
		'<td class="tabledit-view-mode" style="display: none;"><span class="tabledit-span">column_'+col_selected_id_9493373+'</span><input class="tabledit-input form-control input-sm" type="text" name="change_identifier" value="column_'+col_selected_id_9493373+'" ></td>'+
		'<td class="tabledit-view-mode"><span class="tabledit-span">Column '+col_selected_id_9493373+'</span><input onkeyup="onEditColumnHI(this)" class="tabledit-input form-control input-sm onEditColumnHI" type="text" name="editable_column_name_hi" value="Column'+col_selected_id_9493373+'" ></td>'+	
		'<td>'+
		'<a href="#" ><img  data-hi-col-selected="1" id="button_column_'+col_selected_id_9493373+'" onclick="change_image_hi(this)" width="16px" src="/img/selected.png"></img></a>'+
		'</td><td><a href="#" ><img  onclick="delete_column_hi(this)" id="_'+col_selected_id_9493373+'" width="16px" src="/img/delete.png"></img></a></td>'+
	'</tr>';
	c_s_col_9493373_hi = col_selected_id_9493373;
	
	addNewHIColumn();
	$('#hi-col-9493373-head_'+c_s_col_9493373_hi).text("Column "+c_s_col_9493373_hi);
	$("#hi-col-9493373-tbody").append(insert_col_data_hi);  
		
}


function onEditColumnHI(o){
	$('#hi-col-9493373-head_'+c_s_col_9493373_hi).text(o.value);
}


function onAddSwitchHi()
{
	for(i=0 ; i < col_selected_id_9493373 ;i++)
	{
		try{
		id ='button_column_'+i;
		document.getElementById(id).src = '/img/un-selected.png';
	 
		$("#"+id).attr('data-hi-col-selected','0');
		}catch(e)
		{
			
		}
	}
}

function delete_column_hi(o)
{
	if(total_added_col_9493373_hi > 1)
	{
		$("#column"+o.id).remove();
		$("#hi-col-9493373-column"+o.id).remove();
		removeColumnHiDb(o.id);
		total_added_col_9493373_hi--;
	}
}

function delete_column_row_hi(o)
{
	console.log(o.id);
	var uid = $("#"+o.id).attr('data-selected-unique-id');
	var column_id = "column_"+(c_s_col_9493373_hi);  
	if(auto_bot_mode == false)
	{
		column_id = "column_"+(c_s_col_9493373_hi);    
	}
	
	deleteDataHIForBot(column_id,uid);
	//guiUpdateHi();
}

function change_image_hi(o)
{
	var c_s = $("#"+o.id).attr('data-hi-col-selected');
	if(total_added_col_9493373_hi > 0)
	{
		if(c_s == '1')
		{
			document.getElementById(o.id).src = '/img/un-selected.png';
			$("#"+o.id).attr('data-hi-col-selected','0');
		}
		else
		{
			document.getElementById(o.id).src = '/img/selected.png';
			$("#"+o.id).attr('data-hi-col-selected','1');
		}
		
		$('.hi-col-9493373').hide();
		for(i=0 ; i < col_selected_id_9493373 ;i++)
		{
			id ='button_column_'+i;
			if(id != o.id){
				try{
					document.getElementById(id).src = '/img/un-selected.png';
					$("#"+id).attr('data-hi-col-selected','0');
				}catch(e)
				{
					
				}
			}
			else
			{
				c_s_col_9493373_hi = i;
				$( "#hi-col-9493373" ).show();
				$('#hi-col-9493373-column_'+i).show();
				
				if(c_s == '1')
				{
					$('#hi-col-9493373-column_'+i).hide();
				}
				
			}
		}
	}
	
}
$( document ).ready(function() {	
	addNewHIColumnIdCarrier();
    $( ".hi-col-9493373" ).draggable();
    $( "#hi-col-9493373" ).draggable();
		makeEditableHI();
	
		$("#column_0").remove();
		$("#hi-col-9493373-column_0").remove();
		total_added_col_9493373_hi--;
		
		console.log('before if');
		if(auto_bot_mode == true)
		{
			console.log('true');
			
			console.log(bot_data_extracted);
			var cnt_lp = 1 ;
			$.each(bot_data_extracted,function(i,v){
				
				if(cnt_lp++ == Object.keys(bot_data_extracted).length)
				{
					var img_slc = '/img/selected.png';
					var img_slc_d = '1';
				}
				else
				{
					var img_slc = '/img/un-selected.png';
					var img_slc_d = '0';
				}
				console.log(cnt_lp+' == '+Object.keys(bot_data_extracted).length);
				var unique_id_for_column = i.split('_');
				unique_id_for_column = unique_id_for_column[1];
				
					col_selected_id_9493373 = unique_id_for_column;
					
					var insert_col_data_hi = '<tr id="column_'+col_selected_id_9493373+'">'+
						'<td class="tabledit-view-mode" style="display: none;"><span class="tabledit-span">column_'+col_selected_id_9493373+'</span><input class="tabledit-input form-control input-sm" type="text" name="change_identifier" value="column_'+col_selected_id_9493373+'" ></td>'+
						'<td class="tabledit-view-mode"><span class="tabledit-span">'+bot_data_extracted_bot[i]['name']+'</span><input onkeyup="onEditColumnHI(this)" class="tabledit-input form-control input-sm onEditColumnHI" type="text" style="display:none;" name="editable_column_name_hi" value="'+bot_data_extracted_bot[i]['name']+'" ></td>'+	
						'<td>'+
						'<a href="#" ><img  data-hi-col-selected="'+img_slc_d+'" id="button_column_'+col_selected_id_9493373+'" onclick="change_image_hi(this)" width="16px" src="'+img_slc+'"></img></a>'+
						'</td><td><a href="#" ><img  onclick="delete_column_hi(this)" id="_'+col_selected_id_9493373+'" width="16px" src="/img/delete.png"></img></a></td>'+
					'</tr>';
					c_s_col_9493373_hi = col_selected_id_9493373;
					
					addNewHIColumn();
					console.log(bot_data_extracted_bot[i]['name']);
					$('#hi-col-9493373-head_'+c_s_col_9493373_hi).text(bot_data_extracted_bot[i]['name']);
					$("#hi-col-9493373-tbody").append(insert_col_data_hi);
				
				
				
				
				
				$.each(v.data,function(k,vl){
					
						var insert_data_hi = '<tr id="row_sel_'+k+'">'+
						'<td class="tabledit-view-mode">'+vl+'</td>'+
						'<td><a href="#" ><img  onclick="delete_column_row_hi(this)" data-selected-unique-id="'+k+'" id="_sel_'+k+'" width="16px" src="/img/delete.png"></img></a></td>'+
						'</tr>';
						$("#hi_table_data_col_"+unique_id_for_column).append(insert_data_hi);  
					
					
				});
				
			});
			
		}
		else
		{
			$( "#hi-col-9493373-add-col" ).trigger( "click" );
			console.log('false');
		}
		
		console.log('after if');
});
		

function getParent()
{
	
}
var path = {};
var path_ind = {};
var ind_path = 0;
var index_of_sel = 0;


function checkParent(obj,base_obj,save=false)
{
	var tag = obj.prop("tagName"); 
	var ind =obj.index();
	path_ind[ind_path] = ind;
	path[ind_path++] = tag;
	//var ind = obj.index();
	
	
	if(tag == "TR" || tag == "LI" || (obj.prop("className").search(/col/) != -1)  )
	{
		console.log('obj.prop("className").search(/col/)'+obj.prop("className").search(/col/));
		if(tag != "TR" && tag != "LI")
		{
			tagx = "."+obj.prop("className").match(/(col)\-([a-z][a-z])\-([0-9]){1,2}/); 
			tags = tagx.split(',');
			
			if(tags[0] =='.null')
			{
				tag = '';
			}
		}
		 
		console.log("indexes "+path_ind);
		console.log("path "+path);
	/*	var expected_parent;
		switch(tag)
		{
			case "LI":
				expected_parent = "UL";
				break;
			case "TR":
				expected_parent = "TABLE";
				break;
			default:
				expected_parent = tag;
		}*/
		c_object_selected = obj.parent()
		/*while(1)
		{
			if(c_object_selected.prop('tagName') == expected_parent || c_object_selected.prop('tagName') == "BODY")
			{
				break;
			}
			c_object_selected = c_object_selected.parent();
		}*/
		if((obj.prop("className").search(/col/) != -1))
		{
			c_object_selected = c_object_selected.parent();
		}
		console.log(c_object_selected.prop('className')+" "+tag);
		path.reverse();
		path_ind.reverse();
		pathx = path.join(" ");
		ind_path -= 2;
		path.splice(0,1);
		path_ind.splice(0,1);
		unique_id_hi_element = {};
		data_text_hi_element = {};
		index_of_sel = 0;
		$(c_object_selected).find(tag).each(function(inx,val){
			getElementByIndexes(0,$(this),save);
		});
		if(save)
			sendDataHIForBot();
		return;
		
	}
	else if(tag == "BODY")
	{
		unique_id_hi_element = {};
		data_text_hi_element = {};
		console.log("Hovered Data ");
		return;
	}
	else
	{
		checkParent(obj.parent(),base_obj,save); 
	}
	
}


function singleExtract(obj,save)
{	
	console.log("single element extractor");
	unique_id_hi_element = {};
	data_text_hi_element = {};
	if(save)
		{
			var tmp_txt = obj.remove('script').text();
			var tmp_uid = obj.attr("data-hi_id");
			index_of_sel = 0;
			//c_s_col_9493373_hi,unique_id_hi_element,data_text_hi_element
			data_text_hi_element[index_of_sel] = tmp_txt;
			unique_id_hi_element[index_of_sel] = tmp_uid;
			if((data_text_hi_element[index_of_sel] != "") &&(data_text_hi_element[index_of_sel] != " "))
			{
				var insert_data_hi = '<tr id="row_sel_'+unique_id_hi_element[index_of_sel]+'">'+
				'<td class="tabledit-view-mode">'+data_text_hi_element[index_of_sel]+'</td>'+
				'<td><a href="#" ><img  onclick="delete_column_row_hi(this)" data-selected-unique-id="'+unique_id_hi_element[index_of_sel]+'" id="_sel_'+unique_id_hi_element[index_of_sel]+'" width="16px" src="/img/delete.png"></img></a></td>'+
				'</tr>';
			
			$("#hi_table_data_col_"+c_s_col_9493373_hi).append(insert_data_hi); obj.addClass("io_text_saved");				
				index_of_sel++;
			}
		}
		else
		{
			obj.addClass("io_text_hover");
			}
	
	
	if(save)
			sendDataHIForBot();
}

function getElementByIndexes(lpvar,obj,save)
{
	var ind = path_ind[lpvar];
	var tag = path[lpvar];	
	var obj = obj.find(">*:eq("+ind+")");
	if(lpvar++ == ind_path)
	{
		var tmp_txt = obj.remove('script').text();
		var tmp_uid = obj.attr("data-hi_id");
		console.log(tmp_uid+" = "+tmp_txt);
		if(save)
			{
				//c_s_col_9493373_hi,unique_id_hi_element,data_text_hi_element
				data_text_hi_element[index_of_sel] = tmp_txt;
				unique_id_hi_element[index_of_sel] = tmp_uid;
				if((data_text_hi_element[index_of_sel] != "") &&(data_text_hi_element[index_of_sel] != " "))
				{
					var insert_data_hi = '<tr id="row_sel_'+unique_id_hi_element[index_of_sel]+'">'+
					'<td class="tabledit-view-mode">'+data_text_hi_element[index_of_sel]+'</td>'+
					'<td><a href="#" ><img  onclick="delete_column_row_hi(this)" data-selected-unique-id="'+unique_id_hi_element[index_of_sel]+'" id="_sel_'+unique_id_hi_element[index_of_sel]+'" width="16px" src="/img/delete.png"></img></a></td>'+
					'</tr>';
				
				$("#hi_table_data_col_"+c_s_col_9493373_hi).append(insert_data_hi); obj.addClass("io_text_saved");				
					index_of_sel++;
				}
			}
			else
			{
				obj.addClass("io_text_hover");
			}
		
		return tmp_txt;
	}
	else
	{
		getElementByIndexes(lpvar,obj,save);
	}
}

