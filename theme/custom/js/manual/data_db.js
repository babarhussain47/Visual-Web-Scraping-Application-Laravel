
   // A $( document ).ready() block.



  function makeEditableHI()
			{ 
			$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
			});	
		$('#hi_table_column_carrier').Tabledit({
				url: "/update-column",
				editButton: false,
				deleteButton: false,
				hideIdentifier: true,
				 ajaxOptions: {
					type: 'get'
				},
				columns: {
						  identifier: [0, 'change_identifier'],
						  editable: [[1,"editable_column_name_hi"]]
						},
				 onSuccess: function(data, textStatus, jqXHR) {
					console.log('onSuccess(data, textStatus, jqXHR)');
					console.log(data);
					console.log(textStatus);
					console.log(jqXHR);
				},
				onFail: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus);
				},
				onAlways: function() {
					console.log('onAlways()');
				},
				onAjax: function(action, serialize) {
					console.log('onAjax(action, serialize)');
					console.log(action);
					console.log(serialize);
				}
				  
			  });
			}
			
			
			function addColumnHiDb(){
				/*
				ext_id: 1
				user_id: 1
				_token: cftnNDuRHpsi2hXYi1BCEUASZN4p0kJza3pNELzS
				change_identifier: column_1
				editable_column_name_hi: Column 11
				action: edit
				*/
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': csrf_token
					}
				});	
				$.post('/update-column',{_token:_token,ext_id:ext_id,user_id:user_id,change_identifier:'column_'+(col_selected_id_9493373-1),editable_column_name_hi:'Column '+(col_selected_id_9493373-1),action: 'edit'},function(d){ 
					
				});	
			}
			
			function removeColumnHiDb(id){
				$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': csrf_token
						}
					});	
				/*
				ext_id: 1
				user_id: 1
				_token: cftnNDuRHpsi2hXYi1BCEUASZN4p0kJza3pNELzS
				change_identifier: column_1
				editable_column_name_hi: Column 11
				action: edit
				*/
				$.post('/update-column',{_token:_token,ext_id:ext_id,user_id:user_id,change_identifier:'column'+id,id:'Column '+id,action: 'delete'},function(d){ 
					
				});	
			}
			function sendDataHIForBot(){
				console.log(unique_id_hi_element);
				var change_identifier = "column_"+c_s_col_9493373_hi;
			
				$.post('/update_extractors',{action:'edit',_token:_token,ext_id:ext_id,user_id:user_id,change_identifier:change_identifier,unique_id_hi_element:unique_id_hi_element,data_text_hi_element:data_text_hi_element},function(d){ 
					
				});	
			}
			
			function deleteDataHIForBot(column_id,uid){

			
				$.post('/update_extractors',{action:'delete',_token:_token,ext_id:ext_id,user_id:user_id,change_identifier:column_id,unique_id_hi_element:uid},function(d){ 
					console.log('removing '+"#row_sel_"+uid);
					$("#row_sel_"+uid).remove();
					$("[data-hi_id="+uid+"]").removeClass("io_text_saved");
					$("[data-hi_id="+uid+"]").addClass("io_text");
				});	
			}
			
			
	function showMessageLoad(title,msg)
{
	Swal.fire({
  title: title,
  text: msg,
  type: 'info',
  showCancelButton: false,
  showConfirmButton: false,
  allowOutsideClick: false
	});

}
 
function loadingSuccess(title,msg)
{
	Swal.fire({
  title: title,
  text: msg,
  type: 'success', 
  timer: 1000,
  showCancelButton: false,
  showConfirmButton: false,
  allowOutsideClick: false
	});
}

function activateNow(obj)
{
	showMessageLoad("Activating Extractor","Please wait while we save your setting!");
	
	$.get('/user/extractor/'+ext_id+"/activate/"+user_id,{_token:csrf_token,ext_draft:ext_draft},function(resp){
		loadingSuccess("Settings Saved","Your extractor is now ready for run!");
		if(ext_draft == 0)
			ext_draft = 1;
		else
			ext_draft = 0;
		updateActivateBtn();
	});
	
	
}

	function deleteExtractor()
	{

		Swal.fire({
		  title: 'Are you sure want to delete ',
		  text: "You won't be able to revert this! All data associated with extractor will removed.",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.value) {
			  Swal.fire('Processing...','Extractor will be deleted');
			 $.post('/user/extractor/delete',
					{
						'_token': csrf_token,
			 			ext_id: ext_id,_token:csrf_token,
					},function(res){
						
						if(res == "EXTRACTOR_DELETED")
						{
							Swal.fire('Delete Success!','Requested Extractor was removed','success');
							
							location.href = '/user/extractor/list';
						}
						else if(res == "NO_EXTRACTOR_FOUND")
						{
							Swal.fire('Delete Error!','Requested Extractor does not exist!','error');
						}
						else
						{
							Swal.fire('Delete Error!','Something went wrong!','error');
						}
						
					}
					);
		  }
		});
	}

	function showSetting()
	{
		showMessageLoad('Loading...',"Please wait! while we prepare setting page for you.");
		var attr_value = ext_id;
		$.get('/user/extractor/'+attr_value+'/settings2',function(data){
			loadingSuccess('Loading Completed!','Go Ahead...');
			$("#title_of_module").text("Extractor Settings");
			$("#body_of_module").html(data);
			 $('#modal_extractors').modal({
				backdrop: 'static',
				keyboard: false,
				show: true
			}); 
		});
	}
	
	function saveSettings(){
	$('#modal_extractors').modal({
				show: false
			}); 
	var attr_value = ext_id;
	var form_id = '#setting_form_save_'+attr_value;
	showMessageLoad('Saving...','Please wait while we save data.');
	$("#add_inv_btn").hide();
var data = $(form_id).serialize();

$.get('/user/extractor/'+attr_value+'/settings/save',data,function(data){
	loadingSuccess('Settings Saved!','Go Ahead!');
}).fail(function(resp) {
    console.log(resp.errors); // or whatever
});
}
function autoSuggestion(obj)
{
	if(auto_suggest)
	{
		$('#autosuggest').removeClass('mybtn-success');
		$('#autosuggest').addClass('mybtn-warning');
		
		$('#autosuggest').text('Auto Suggestion Disabled');
		auto_suggest = false;
		loadingSuccess("Settings Saved","Auto Suggesion Disabled for current session");
	}
	else
	{
		$('#autosuggest').removeClass('mybtn-warning');
		$('#autosuggest').addClass('mybtn-success');
		$('#autosuggest').text('Auto Suggestion Enabled');
		auto_suggest = true;
		loadingSuccess("Settings Saved","Auto Suggesion Enabled for current session");
	}
}
updateActivateBtn();
function updateActivateBtn(id)
{
	if(ext_draft == 1)
	{
		$('#activate_btn').removeClass('mybtn-warning');
		$('#activate_btn').addClass('mybtn-success');
		$('#activate_btn').text('Activate Now');
	}
	else
	{
		$('#activate_btn').removeClass('mybtn-success');
		$('#activate_btn').addClass('mybtn-warning');
		$('#activate_btn').text('Deactivate Now');
	}
}