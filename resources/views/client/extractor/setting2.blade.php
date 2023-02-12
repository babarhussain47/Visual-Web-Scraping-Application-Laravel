
<?php 

	$repeat_op = array("no_repeat"=>"No Repeat","daily"=>"Daily","weekly"=>"Weekly","monthly"=>"Monthly","yearly"=>"Yearly");
	
	$day_op = array();
	$day_op[strtoupper(date("D"))] = date("l");
	for($i=1 ;$i <= 6 ;$i++)
	{
		$dd =  date("l", time()+86400*$i);
		$dds =  date("D", time()+86400*$i);
		$day_op[strtoupper($dds)] = $dd;
	}
	
	$date_op = array();
	$date_op[strtolower( date("j"))] = date("j");
	for($i=1;$i<31;$i++)
	{
		$dd =  date("j", time()+86400*$i);
		$date_op[strtolower($dd)] = $dd;
	}
	
	$month_op = array();
	
	for($i=0 ;$i <= 11 ;$i++)
	{
		$timestamp = mktime(0, 0, 0, date('n') + $i, 1);
		$month_op[date('n', $timestamp)] = date('F', $timestamp);
	}

?>

    <!-- Styles -->
    <link type="text/css" href="https://dev.handyimport.io/theme/files/bower_components/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Switch component css -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/switchery/css/switchery.min.css')}}">

    <!-- jpro forms css -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/j-pro/css/demo.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/j-pro/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/j-pro/css/j-pro-modern.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/switchery/css/switchery.min.css')}}">



<!-- Page body start -->
<div class="page-body">
	<div class="row">
		<div class="col-sm-12">
			<!-- Job application card start -->
			<div class="card">
				<div class="card-header">
					<h5>Settings for {{$extractor->ext_name}} :P</h5>
				<?php
					
					if(old('extractor_urls'))
						$urls_data = json_decode(old('extractor_urls'),true);
					else
						$urls_data = json_decode($extractor->ext_urls,true);				
				?>	
				</div> 
				<div class="card-block">
					<div class="j-wrapper j-wrapper-640">
						<form method="post" class="j-pro" id="setting_form_save_{{$extractor->ext_id}}">
							{{csrf_field()}}
							<!-- end /.header-->
							<div class="j-content">
							
								<!-- start extractor name -->
								<div class="j-row">
									<div class="j-span12 j-unit">
										<div class="j-input {{ $errors->has('extractor_name') ? ' j-error-view' : '' }}">
											<label class="j-icon-right" for="extractor_name">
												<i class="icofont icofont-text-width"></i>
											</label>
											<input type="text" id="extractor_name" name="extractor_name" value="@if(old('extractor_name'))old('extractor_name')@else{{$extractor->ext_name}}@endif" placeholder="Extractor Name">
											<span class="j-tooltip j-tooltip-right-top">Extractor Name (You can identify later)</span>
											@if ($errors->has('extractor_name'))
												<span class="j-error-view">{{ $errors->first('extractor_name') }}</span>
											@endif
										</div>
									</div>
								</div>
								<!-- end extractor name -->
								
								<!-- start extractor url -->
								<div class="j-row">
									<div class="j-span12 j-unit">
										<div class="j-input {{ $errors->has('extractor_url') ? ' j-error-view' : '' }}">
											<label class="j-icon-right" for="extractor_url">
												<i class="icofont icofont-link"></i>
											</label>
											<input type="text" id="extractor_url" disabled readonly name="extractor_url" value="@if(old('extractor_url'))old('extractor_url')@else{{$extractor->ext_url}}@endif" placeholder="Extractor Name">
												<span class="j-tooltip j-tooltip-right-top">Base URL (main link on which you will build extractor)</span>
											@if ($errors->has('extractor_url'))
												<span class="j-error-view">{{ $errors->first('extractor_url') }}</span>
											@endif
										</div>
									</div>
								</div>
								<!-- end extractor url -->
									
								<!-- start extractor short description -->
								<div class="j-row">
									<div class="j-span12 j-unit">
									<label>Extractor Description</label><br>
										<div class="j-input {{ $errors->has('extractor_desc') ? ' j-error-view' : '' }}">
											
											<textarea type="text" id="extractor_desc" name="extractor_desc">@if(old('extractor_desc'))old('extractor_desc')@else{{$extractor->ext_desc}}@endif</textarea>
												<span class="j-tooltip j-tooltip-right-top">Short Description of Extractor</span>
											@if ($errors->has('extractor_desc'))
												<span class="j-error-view">{{ $errors->first('extractor_desc') }}</span>
											@endif
										</div>
									</div>
								</div>
								<!-- end extractor description -->
								
								
								
								<!-- start extractor related urls -->
								<input type="hidden" name="extractor_urls" value="" id="extractor_urls_array">
								
								<div class="j-row">  
									<div class="j-span12 j-unit">
									<label>Extractor Related URLs Pattern</label><br>
										<div class="j-input ">	
											<input type="text" placeholder="www.aimfox.net/page/[[p1]]?cat=[[p2]]&type=[[p3]]" value="{{$urls_data['url']}}" id="extractor_urls" />
												<span class="j-tooltip j-tooltip-right-top">Similar/Related URLs</span>
											
										</div>
									</div>
								</div>
								<div id="hi-url-parameters-labels" style="@if(count($urls_data['data']) == 0)display:none;@endif"> 
								
									<div class="j-row">  
										<div class="j-span3 j-unit">
											<div class="j-input ">
												<label>LABEL</label>
											</div>
										</div>
										<div class="j-span3 j-unit">
											<div class="j-input">
												<label>START</label>
											</div>
										</div>
										<div class="j-span3 j-unit">
											<div class="j-input ">
												<label>INCREMENT</label>
											</div>
										</div>
										<div class="j-span3 j-unit">
											<div class="j-input">
												<label>END</label>
											</div>
										</div>
									</div>
								</div>
								<div id="hi-url-parameters">
								
								@foreach($urls_data["data"] as $key => $value)
								
								<div class='j-row' id='prow_{{$key}}'>  
									<div class='j-span3 j-unit'>
										<div class='j-input '>
											<input type='text' id='label_{{$key}}'   disabled value='{{$urls_data["params"][$key]}}' />
										</div>
									</div>
									<div class='j-span3 j-unit'>
										<div class='j-input '>
											<input type='text' value='{{$urls_data["data"][$key][0]}}' onchange='keyupUrls()' id='start_{{$key}}' />
										</div>
									</div>							
									<div class='j-span3 j-unit'>
										<div class='j-input '>
											<input type='text' value='{{$urls_data["data"][$key][1]}}' onchange='keyupUrls()' id='increment_{{$key}}'  />
										</div>
									</div>
									<div class='j-span3 j-unit'>
										<div class='j-input '>
											<input type='text' value='{{$urls_data["data"][$key][2]}}' onchange='keyupUrls()' id='end_{{$key}}'  />
										</div>
									</div>	
								
								</div>
								
								
								@endforeach
								
								</div>
								<!-- end extractor related urls -->
								
								<!-- start extractor Scheduler -->
								<div class="j-row">
									<div class="j-span12 j-unit">
										<label>Schedule Extractor (Auto run)</label>
									</div>
								</div>
								<div class="j-row">
									<div class="j-span12 j-unit">
										<label class="j-input j-select">
											<select name="repeater" id="repeater">
												@foreach($repeat_op as $rop_k=>$rop_v)
													<option @if($extractor->ext_run_type == $rop_k) selected @endif value="{{$rop_k}}">{{$rop_v}}</option>
												@endforeach
											</select>
											<i></i>
										</label>
									</div>

								</div>
								<div class="j-row">
									<div class="j-span6 j-unit" id="time_selector" >
									
										 <div class='input-group date' id='datetimepicker3'>
											<input type='text' name="time_selector" class="form-control" value="@if(old('time_selector'))old('time_selector')@else{{$extractor->ext_time}}@endif"/>
											<span class="input-group-addon ">
												<span class="icofont icofont-ui-calendar"></span>
											</span>
											
										</div>
									</div>
									
									<div class="j-span6 j-unit" id="day_selector" >
										<label class="j-input j-select">
											<select name="day_selector">
												@foreach($day_op as $rop_k=>$rop_v)
													<option @if($extractor->ext_day == $rop_k) selected @endif value="{{$rop_k}}">{{$rop_v}}</option>
												@endforeach
											</select>
											<i></i>
										</label>
									</div>

									</div>
								<div class="j-row">
									<div class="j-span6 j-unit" id="date_selector" >
										<label class="j-input j-select">
											<select name="date_selector">
												@foreach($date_op as $rop_k=>$rop_v)
													<option @if($extractor->ext_date == $rop_k) selected @endif value="{{$rop_k}}">{{$rop_v}}</option>
												@endforeach
											</select>
											<i></i>
										</label>
									</div>
									<div class="j-span6 j-unit" id="month_selector" >
										<label class="j-input j-select">
											<select name="month_selector">
												@foreach($month_op as $rop_k=>$rop_v)
													<option @if($extractor->ext_month == $rop_k) selected @endif value="{{$rop_k}}">{{$rop_v}}</option>
												@endforeach
											</select>
											<i></i>
										</label>
									</div>

								</div>
								<!-- end extractor Scheduler -->
								
									<div class="j-row">
									<div class="j-span12 j-unit">
										<label>POST Request for your website</label>
										<small>We will send data to this url, you can handle the request, data is sent in JSON format</small>
									</div>
								</div>
								<!-- start extractor name -->
								<div class="j-row">
									<div class="j-span12 j-unit">
										<div class="j-input {{ $errors->has('post_url') ? ' j-error-view' : '' }}">
											<label class="j-icon-right " for="post_url">
												<input type="checkbox" name="post_url_en" @if(old('post_url_en') && (old('post_url_en')=="on")) checked @elseif($extractor->post_url_en && ($extractor->post_url_en=="on")) checked @endif >
											</label>
											<input type="text" id="post_url" name="post_url" value="@if(old('post_url'))old('post_url')@else{{$extractor->post_url}}@endif" placeholder="https://example.com/save_data">
											<span class="j-tooltip j-tooltip-right-top">Your website URL where you will receive your data</span>
											@if ($errors->has('post_url'))
												<span class="j-error-view">{{ $errors->first('post_url') }}</span>
											@endif
										</div>
									</div>
								</div>
								<!-- end extractor name -->
								
								<!-- start response from server -->
								<div class="j-response"></div>
								<!-- end response from server -->
							</div>
							<!-- end /.content -->
							<div class="j-footer">
								<div type="submit" onclick="saveSettings()" data-hi-ext_id="{{$extractor->ext_id}}" id="save_{{$extractor->ext_id}}" class="btn btn-primary">Save</div>
							</div>
							<!-- end /.footer -->
						</form>
					</div>
				</div>
			</div>
			<!-- Job application card end -->
		</div>
	</div>
</div>
<!-- Page body end -->

	    <!-- Required Jquery -->
	<script type="text/javascript" src="https://dev.handyimport.io/theme/files/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="https://dev.handyimport.io/theme/files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://dev.handyimport.io/theme/files/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="https://dev.handyimport.io/theme/files/bower_components/bootstrap/js/bootstrap.min.js"></script>
<!-- Bootstrap date-time-picker js -->
    <script src="{{asset('theme/files/assets/pages/advance-elements/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('theme/files/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('theme/files/assets/pages/advance-elements/bootstrap-datetimepicker.min.js')}}"></script>


<script>
	
var added_entries_for_parameters = [];
var added_ids_for_parameters = [];
var extractor_urls_array = {};
extractor_urls_array["url"] = "";
extractor_urls_array["params"] = {};
extractor_urls_array["data"] = {};
$("#extractor_urls_array").val(JSON.stringify(extractor_urls_array));
var count_ids_arr = 0;

$("#extractor_urls").on("paste keyup",function(){keyupUrls();});

keyupUrls();
function keyupUrls(){
	
	added_entries_for_parameters = added_entries_for_parameters.filter(function (el) {
    return el != null && el != "";
  });
	var input_value = $("#extractor_urls").val();
	
	// set extractor dynamic url
	
	var m = input_value.match(/(\[\[)[a-zA-Z0-9-](.*?)(\]\])/g);
	if(m)
	{	
		extractor_urls_array["url"] = input_value; 
		extractor_urls_array["params"] = {};
		extractor_urls_array["data"] = {};
		$("#hi-url-parameters-labels").show();
		$.each(m,function(key,value){
			add_now = true;
			var tmp_value = value.replace("[[","");
			tmp_value = tmp_value.replace("]]","");
			if(count_ids_arr > m.length)
			{
				for(i=m.length ; i< count_ids_arr ; i++)
				{
					$("#prow_"+i).remove();
				}
			}
			var parameters_row =	"<div class='j-row' id='prow_"+key+"'>  "+
										"<div class='j-span3 j-unit'>"+
											"<div class='j-input '>"+
												"<input type='text' id='label_"+key+"'   disabled value='"+tmp_value+"' />"+
											"</div>"+
										"</div>"+
										"<div class='j-span3 j-unit'>"+
										"	<div class='j-input '>"+
												"<input type='text' placeholder='' onchange='keyupUrls()' id='start_"+key+"' />"+
											"</div>"+
										"</div>"+
										"<div class='j-span3 j-unit'>"+
											"<div class='j-input '>"+
											"	<input type='text' placeholder='' onchange='keyupUrls()' id='increment_"+key+"'  />"+
											"</div>"+
										"</div>"+
										"<div class='j-span3 j-unit'>"+
										"	<div class='j-input '>"+
										"		<input type='text' placeholder='' onchange='keyupUrls()' id='end_"+key+"'  />"+
									"		</div>"+
									"	</div>"+
								"	</div>";
			
			if(key > 0)
			{
				if(jQuery.inArray(tmp_value, added_entries_for_parameters) !== -1)
				{
					add_now = false;
				}
				else
				{
					$("#label_"+key).val(tmp_value);
					added_entries_for_parameters[key] = tmp_value;
					extractor_urls_array["params"][key] = tmp_value;
					extractor_urls_array["data"][key] =[$("#start_"+key).val(),$("#increment_"+key).val(),$("#end_"+key).val()];
				}
			}
			else
			{
				$("#label_"+key).val(tmp_value);
				added_entries_for_parameters[key] = tmp_value;
				extractor_urls_array["params"][key] = tmp_value;
				extractor_urls_array["data"][key] =[$("#start_"+key).val(),$("#increment_"+key).val(),$("#end_"+key).val()];
			}
			
			$("#extractor_urls_array").val(JSON.stringify(extractor_urls_array));
			console.log(extractor_urls_array);
			
			
			if(!$("#prow_"+key).length && add_now){		
				added_ids_for_parameters[count_ids_arr++] = "prow_"+key;	
				$("#hi-url-parameters").append(parameters_row);
			}
				
				

		});
		
	}
	else
	{
		extractor_urls_array["url"]="";
		extractor_urls_array["params"]={};
		extractor_urls_array["data"]={};
		$("#extractor_urls_array").val(JSON.stringify(extractor_urls_array));
		$("#hi-url-parameters").html('');
		$("#hi-url-parameters-labels").hide();
	}
}
function show_hide_fn(){
	var sel = $( "#repeater option:selected" ).val();
	
	if(sel == "daily")
	{
		$("#time_selector").show();
		$("#time_selector").removeClass("j-span6");
		$("#time_selector").addClass("j-span12");
		$("#day_selector").hide();
		$("#date_selector").hide();
		$("#month_selector").hide();
	}
	else if(sel == "weekly")
	{
		$("#time_selector").show();
		$("#time_selector").removeClass("j-span6");
		$("#time_selector").addClass("j-span12");
		$("#day_selector").show();
		$("#day_selector").removeClass("j-span6");
		$("#day_selector").addClass("j-span12");
		
		$("#date_selector").hide();
		$("#month_selector").hide();	
	}
	else if(sel == "monthly")
	{
		$("#time_selector").show();
		$("#time_selector").removeClass("j-span6");
		$("#time_selector").addClass("j-span12");
		$("#day_selector").hide();
		$("#date_selector").show();
		$("#date_selector").removeClass("j-span6");
		$("#date_selector").addClass("j-span12");
		$("#month_selector").hide();
	}
	else if(sel == "yearly")
	{
		$("#time_selector").show();
		$("#time_selector").removeClass("j-span6");
		$("#time_selector").addClass("j-span12");
		$("#day_selector").hide();
		$("#date_selector").show();
		$("#date_selector").removeClass("j-span6");
		$("#date_selector").removeClass("j-span12");
		$("#date_selector").addClass("j-span6");
		$("#month_selector").show();	
	}
	else
	{		
		$("#time_selector").hide();
		$("#day_selector").hide();
		$("#date_selector").hide();
		$("#month_selector").hide();
	}
}

$(document).ready(function(){
	show_hide_fn();
});

$("#repeater").change(function(){
	show_hide_fn();
});


    $('#datetimepicker3').datetimepicker({
        format: 'LT',
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left"
        }
    });
	

	
</script>