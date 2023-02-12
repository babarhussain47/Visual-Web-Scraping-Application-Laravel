@extends('layouts.app')

@section('head-css')
 <!-- Select 2 css -->
    <link rel="stylesheet" href="{{asset('theme/files/bower_components/select2/css/select2.min.css')}}"/>
  <!-- sweet alert framework -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/sweetalert/css/sweetalert.css')}}">
	
	 <!-- Notification.css -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/notification/notification.css')}}">

<style>

.swal2-container,.sweet-alert {
  z-index: 999999999991 !important;
}
.js-notification{
	display:block;
}
.js-notification:hover{
	cursor:copy !important;
	color:blue;
	font-weight:bold;
}
</style>
@endsection

@section('content')

 <!-- Individual Column Searching (Text Inputs) start -->
                                                <div class="card">
                                                    <div class="card-header">
													
													   <h5>
															All Applications
															<small> Click on app type or other field to edit and press enter to save. </small>
															
														</h5>
														<div class="animation-model">
															<button style="float:right; margin-right:40px;" type="button" class="btn btn-primary waves-effect waves-light add" data-toggle="modal" data-target="#register" >Add Application</button>
														</div>
                                                    </div>
													
                                                    <div class="card-block">
                                                        <div class="dt-responsive table-responsive">
                                                            <table id="footer-search" class="table table-striped table-bordered nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Name</th>
                                                                        <th>Public Key</th>
                                                                        <th>Private Key</th>
                                                                        <th>Extractor ID</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
																
																
																@foreach($apps as $app)
                                   
                                                            <tr id="tr_{{$app->app_id}}">
                                                                <td>{{$app->app_id}}</td>
                                                                <td>{{$app->app_name}}</td>
                                                                <td><span  class="js-notification" data-type="success" data-from="top" data-align="right" onclick="copyToClipboard('#pubkey_{{$app->app_id}}_btn')" id="pubkey_{{$app->app_id}}_btn">{{$app->public_key}}</span></td>
                                                                <td><span class="js-notification"data-type="success" data-from="top" data-align="right" style="display:none;" onclick="copyToClipboard('#pvtkey_{{$app->app_id}}_btn')" id="pvtkey_{{$app->app_id}}_btn">{{$app->private_key}}</span><span id="pvtkey_{{$app->app_id}}" class="btn btn-warning" onClick="showKey(this)">Show Key</span></td>
                                                                <td>{{$app->ext_id}}</td>
                                                                <td><span class="btn btn-danger btn-sm" id="del_{{$app->app_id}}" data-appid="{{$app->app_id}}" onclick="deleteApplication(this)">DELETE</span></td>
															
                                                            </tr>
																@endforeach	
                                                                
                                                               </tbody>
                                                            </table>
                                                        </div>
														
														@if(count($apps) == 0)	
																<div class="alert alert-warning">
                                                                    <label>No Application Listed, try adding new.</label>
                                                                </div>
														@endif
														
														 <div class="animation-model">
															<button type="button" class="btn btn-primary waves-effect waves-light add" data-toggle="modal" data-target="#register" >Add Application</button>
														</div>
                                                    </div>
                                                </div>
												
																									
													 <!-- Register modal start -->
                                    <div id="register" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="login-card card-block login-card-modal">
                                                <div class="md-float-material">
                                                   
                                                    <div class="card m-t-15">
                                                        <div class="auth-box card-block">
                                                        <div class="row m-b-20">
                                                            <div class="col-md-12">
                                                                <h3 class="text-center txt-primary">Add Application</h3>
																<span class="label label-danger">You will not be able to edit any information, so please be careful while creating application.</span>
                                                            </div>
                                                        </div>
                                                        <hr/>
														
                                                        <div class="input-group">
                                                            <input type="text" id="app_name" required class="form-control" placeholder="Application Name">
                                                            <span class="md-line"></span>
                                                        </div>
                                                        
                                                        <div class="input-group">
                                                            <select id='app_ext' class='form-control select2'>
																<option value="">-- Select Extractor --</option>
															@foreach($extractors as $extractor)
																<option value="{{$extractor->ext_id}}">{{$extractor->ext_name}}</option>
															@endforeach	
															</select>
                                                            <span class="md-line"></span>
                                                        </div>
                                                        
                                                        
                                                        <div class="input-group">
                                                            <input type="text" id="website" required class="form-control" placeholder="Website">
                                                            <span class="md-line"></span>
                                                        </div>
                                                        
                                                        <div class="row m-t-15">
                                                            <div class="col-md-12">
                                                                <div class="btn btn-primary btn-md btn-block waves-effect text-center add_now_btn" onclick="addClass(this)">Add Now</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                <!-- end of form -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Register modal end-->

                                                <!-- Individual Column Searching (Text Inputs) end -->

@endsection

@section('body-js')

    <!-- Editable-table js -->
    <script type="text/javascript" src="{{asset('theme/files/assets/pages/edit-table/jquery.tabledit.js')}}"></script>
<!-- Select 2 js -->
<script type="text/javascript" src="{{asset('theme/files/bower_components/select2/js/select2.full.min.js')}}"></script>

    <!-- sweet alert js -->
    <script type="text/javascript" src="{{asset('theme/files/bower_components/sweetalert/js/sweetalert.min.js')}}"></script>

    <!-- notification js -->
    <script type="2c337e7489e7a183b97c4bdd-text/javascript" src="{{asset('theme/files/assets/js/bootstrap-growl.min.js')}}"></script>
    <script type="2c337e7489e7a183b97c4bdd-text/javascript" src="{{asset('theme/files/assets/pages/notification/notification.js')}}"></script>

  <script>
	function showKey (obj)
	{
		var id = obj.id+"_btn";
		$("#"+obj.id).hide();
		$("#"+id).show();
	}
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
  Swal.fire({
  position: 'top-end',
  type: 'success',
  title: 'Copied',
  showConfirmButton: false,
  timer: 1500
})
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function addClass(obj){
	var app_name	=	$("#app_name").val();
	var app_ext	=	$("#app_ext").val();
	var website	=	$("#website").val();
	
	if(app_name != '' && app_ext != '' && website!= '')
	{
		$(".add_now_btn").hide();
		var request = {app_name:app_name,app_ext:app_ext,website:website};
		$.post('<?php echo route('add_app')?>',request,function(data){
			if(data == "Application Added")
			{
				location.reload();
			}else
			{
				$(".add_now_btn").show();
				Swal.fire('Error',data,'error');
			}
		});
	}
	else
	{
		Swal.fire('Error',"You need to fill all fields",'error');
	}
}

  // Setup - add a text input to each footer cell
    $('#footer-search tfoot th').each(function() {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
    });
function deleteApplication(obj)
{
	var app_id = $("#"+obj.id).data("appid");
	Swal.fire({
		  title: 'Are you sure want to delete app ?',
		  text: "You won't be able to revert this! All data associated with app will removed, and any production will stop. No, request made through this app will be entertained.",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.value) {
			  Swal.fire('Processing...','Application will be deleted');
			  $.post("<?php echo route("delete_app");?>",{app_id:app_id},function(res){
					if(res == "OK")
					{
						Swal.fire('Done',res,'success');
						$("#tr_"+app_id).remove();
					}
					else
					{
						Swal.fire('Error',res,'error');
					}
			  });
		  }
		});
}
	
</script>
@endsection

