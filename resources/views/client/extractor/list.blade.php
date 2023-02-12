@extends('layouts.app')

@include('client.extractor.empty_dialog')

@section('head-css')
<style>

.more-info
{
	left:calc(65% - 50px) !important;
	transform: translateY(-40px) !important;
	-webkit-box-shadow:none !important;
	box-shadow:none !important;
}

.ms-container{
	width:700px !important;
}
.modal{
	z-index:999999 !important;
}
.swal2-container,.sweet-alert {
  z-index: 999999999991 !important;
}
</style>
@endsection
<?php
$draft_msg = array("Active","Draft");
$draft_msg_label = array("success","warning");
?>
@section('content')


    <div class="row">
	
		<!-- subscribe start -->
	<div class="col-md-6 col-lg-4">
		<div class="card">
			<div class="card-block text-center">
				<i class="feather text-c-green d-block f-40">+</i>
				<h4 class="m-t-20"><span class="text-c-blgreenue"></span> </h4>
				<p class="m-b-20">If you want to add new extractor then please click </p>
				<a href="{{route('new_extractor')}}">
					<button class="btn btn-success btn-sm btn-round">Add new Extractor</button> 
				</a>
			</div>
		</div>
	</div>
 
	
        @foreach($extractors  as $extractor)
		
		<div class="col-md-12 col-lg-4" id="ext_rem_id_{{$extractor->ext_id}}">
			<div class="card user-widget-card" style="color:black;">
				<div class="card-block">
					<b class=" bg-simple-c-green card1-icon">{{$extractor->ext_id}}</b>
					
					<h4 class="text-center"><span>{{$extractor->ext_name}}</span></h4>
					<p class="text-left">{{$extractor->ext_url}}</p>
					
					
					<button class="btn btn-inverse btn-sm btn-round" id="setting_{{$extractor->ext_id}}" onclick="showSetting(this)" data-hi-ext_id="{{$extractor->ext_id}}">Settings</button>
					<a href="{{route('new_extractor_builder',['id' => $extractor->ext_id])}}">
						<button class="btn btn-primary btn-sm btn-round">Builder</button>
					</a>
						<button onClick="runExtractor({{$extractor->ext_id}})" class="btn btn-success btn-sm btn-round">Run Extractor</button>
						

					<button class="btn btn-info btn-sm btn-round"  id="data_{{$extractor->ext_id}}"onclick="showData(this)" data-hi-ext_id="{{$extractor->ext_id}}">Data</button>
					<button id="ext_del_{{$extractor->ext_id}}" data-ext_id="{{$extractor->ext_id}}" data-ext_name="{{$extractor->ext_name}}" onClick="deleteExtractor(this)" class="btn btn-danger btn-sm btn-round">Delete</button>
					
					<span class="more-info"  id="more_info_{{$extractor->ext_id}}"><label id="label_{{$extractor->ext_id}}" class="label label-{{$draft_msg_label[$extractor->ext_draft]}}">{{$draft_msg[$extractor->ext_draft]}}</label> Extractor @if($extractor->ext_draft) <button class="label label-success" id="info_{{$extractor->ext_id}}" onclick="activateNow('{{$extractor->ext_id}}')">Activate Now</button> @endif</span>
				</div>
			</div>
		</div>
		
		<!-- subscribe start -->
		<!--div class="col-md-12 col-lg-4" id="ext_rem_id_{{$extractor->ext_id}}">
			<div class="card">
				<div class="card-block text-center">
					<i class="feather icon-home text-c-lite-green d-block f-40"></i>
					<div><label class="label label-warning">Draft</label> Extractor <span class="btn btn-sm btn-success">Activate Now</span></div>
					<h4 class="m-t-20">
						{{$extractor->ext_name}}
					</h4>
					<p class="m-b-20">{{$extractor->ext_url}}</p>
					<a href="{{route('extractor_settings',['id'=>$extractor->ext_id])}}">	
						<button class="btn btn-inverse btn-sm btn-round">Settings</button>
					</a>
					 
					<a href="{{route('new_extractor_builder',['id' => $extractor->ext_id])}}">
						<button class="btn btn-primary btn-sm btn-round">Builder</button>
					</a>
					<a href="#">
						<button onClick="runExtractor({{$extractor->ext_id}})" class="btn btn-success btn-sm btn-round">Run Extractor</button>
					</a>	

					<a href="{{route('extractor_data',['id' => $extractor->ext_id])}}">
						<button class="btn btn-info btn-sm btn-round">Data</button>
					</a>	
					<a href="#" id="ext_del_{{$extractor->ext_id}}" data-ext_id="{{$extractor->ext_id}}" data-ext_name="{{$extractor->ext_name}}" onClick="deleteExtractor(this)">
						<button class="btn btn-danger btn-sm btn-round">Delete</button>
					</a>						
				</div>
			</div>
		</div-->
		@endforeach
		
	
	
    </div>
	
	@endsection
	@section('body-js')
	<script>
	function showSetting(o)
	{
		showMessageLoad('Loading...',"Please wait! while we prepare setting page for you.");
		var attr_value = $("#"+o.id).attr("data-hi-ext_id");
		$.get('/user/extractor/'+attr_value+'/settings',function(data){
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
	function showData(o)
	{
		var attr_value = $("#"+o.id).attr("data-hi-ext_id");
		showMessageLoad('Loading...',"Please wait! while we prepare setting page for you.");
		var attr_value = $("#"+o.id).attr("data-hi-ext_id");
		$.get('/user/extractor/'+attr_value+'/data',function(data){
			loadingSuccess('Loading Completed!','Go Ahead...');
			$("#title_of_module").text("Extractor Data");
			$("#body_of_module").html(data);
			 $('#modal_extractors').modal({
				backdrop: 'static',
				keyboard: false,
				show: true
			}); 
		});
	}
		var csrf_token = "<?php echo csrf_token()?>";
		
		
		
		function runExtractor(id)
		{
			Swal.fire({
		  title: 'Are you really want to run ?',
		  text: "All data will be replaced with new data retrived from the server.",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, run it!'
		}).then((result) => {
		  if (result.value) {
			  Swal.fire('Processing...','We are extracting data');
			$.get("/user/extractor/"+id+"/run",{_token:csrf_token},function(res)
			{
				if(res == "Draft extractor can't be run. Activate to run." || res == "Invalid extractor or extractor not found" || res =="Invalid request")
				{
					Swal.fire('Error!',res,'error');
				}
				else if(res ==  "NO_PACKAGE")
				{
					Swal.fire('Error!','You do not have subscription or is expired, Please Subscribe to a package to continue.','error');
				}
				else
				{
					Swal.fire('Success',res,'success');	
				}
			});
		  }
		});
			
		}
		
		
		
	function deleteExtractor(obj)
	{
		var attt_ext_id = "data-ext_id";
		var ext_id = $("#"+obj.id).attr(attt_ext_id);
		var attt_ext_name = "data-ext_name";
		var ext_name = $("#"+obj.id).attr(attt_ext_name);
		Swal.fire({
		  title: 'Are you sure want to delete '+ext_name+" ?",
		  text: "You won't be able to revert this! All data associated with extractor will removed.",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.value) {
			  Swal.fire('Processing...','Extractor will be deleted');
			 $.post('{{route("delete_extractor")}}',
					{
						'_token': $('meta[name=csrf-token]').attr('content'),
			 			ext_id: ext_id,
					},function(res){
						
						if(res == "EXTRACTOR_DELETED")
						{
							$("#ext_rem_id_"+ext_id).remove();
							Swal.fire('Delete Success!','Requested Extractor was removed','success');
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
function activateNow(ext_id)
{
	showMessageLoad("Activating Extractor","Please wait while we save your setting!");
	
	$.get('/user/extractor/'+ext_id+"/activate/"+{{Auth::user()->id}},{_token:'{{csrf_token()}}'},function(resp){
		loadingSuccess("Settings Saved","Your extractor is now ready for run!");
		var info_id = '#info_'+ext_id;
		var label_id = "#label_"+ext_id;
		
		$(label_id).removeClass('label-warning');
		$(label_id).addClass('label-success'); 
		
		$(label_id).text('Active'); 
		$(info_id).remove(); 
	});
	
	
}
function saveSettings(o){
	$('#modal_extractors').modal({
				show: false
			}); 
	var attr_value = $("#"+o.id).attr("data-hi-ext_id");
	var form_id = '#setting_form_'+o.id;
	showMessageLoad('Saving...','Please wait while we save data.');
	$("#add_inv_btn").hide();
var data = $(form_id).serialize();

$.get('/user/extractor/'+attr_value+'/settings/save',data,function(data){
	loadingSuccess('Settings Saved!','Go Ahead!');
}).fail(function(resp) {
    console.log(resp.errors); // or whatever
});
}
	</script> 
	@endsection