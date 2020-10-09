@extends('layouts.app')

@section('content')

 <div class="row">
 
	<div class="col-md-6 col-xl-3">
		<div class="card widget-card-1">
			<div class="card-block-small">
				<i class="feather icon-book bg-c-blue card1-icon"></i>
				<span class="text-c-blue f-w-600">Total Extractors</span>
				<h4>{{$data['total_extractors']}}</h4>
				<div>
					<span class="f-left m-t-10 text-muted">
						<a href="{{route('list_extractors')}}?t=all" class="btn btn-primary more-info">More Info</a>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-xl-3">
		<div class="card widget-card-1">
			<div class="card-block-small">
				<i class="feather icon-repeat bg-c-pink card1-icon"></i>
				<span class="text-c-pink f-w-600">Auto Scheduled</span>
				<h4>{{$data['auto_run']}}</h4>
				<div>
					<span class="f-left m-t-10 text-muted">
						<a href="{{route('list_extractors')}}?t=auto" class="btn btn-danger more-info">More Info</a>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-xl-3">
		<div class="card widget-card-1">
			<div class="card-block-small">
				<i class="feather icon-file-text bg-c-yellow card1-icon"></i>
				<span class="text-c-yellow f-w-600">Draft Extractors</span>
				<h4>{{$data['draft_extractors']}}</h4>
				<div>
					<span class="f-left m-t-10 text-muted">
						<a href="{{route('list_extractors')}}?t=draft" class="btn btn-warning more-info">More Info</a>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-xl-3">
		<div class="card widget-card-1">
			<div class="card-block-small">
				<i class="feather icon-activity bg-c-green card1-icon"></i>
				<span class="text-c-green f-w-600">Active Extractors</span>
				<h4>{{$data['total_extractors'] - $data['draft_extractors']}}</h4>
				<div>
					<span class="f-left m-t-10 text-muted">
						<a href="{{route('list_extractors')}}?t=active" class="btn btn-success more-info">More Info</a>
					</span>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="col-md-6 col-xl-3">
		<div class="card user-widget-card bg-c-blue">
			<div class="card-block">
				<i class="feather icon-repeat bg-simple-c-blue card1-icon"></i>
				<h4>{{$data['auto_run_daily']}}</h4>
				<p>Monthly Scheduled</p>
				<a href="{{route('list_extractors')}}?r=daily" class="more-info">Daily Scheduled List</a>
			</div>
		</div>
	</div>
	
	<div class="col-md-6 col-xl-3">
		<div class="card user-widget-card bg-c-pink">
			<div class="card-block">
				<i class="feather icon-repeat bg-simple-c-pink card1-icon"></i>
				<h4>{{$data['auto_run_weekly']}}</h4>
				<p>Monthly Scheduled</p>
				<a href="{{route('list_extractors')}}?r=weekly" class="more-info">Weekly Scheduled List</a>
			</div>
		</div>
	</div>
	
	<div class="col-md-6 col-xl-3">
		<div class="card user-widget-card bg-c-green">
			<div class="card-block">
				<i class="feather icon-repeat bg-simple-c-green card1-icon"></i>
				<h4>{{$data['auto_run_monthly']}}</h4>
				<p>Monthly Scheduled</p>
				<a href="{{route('list_extractors')}}?r=monthly" class="more-info">Monthly Scheduled List</a>
			</div>
		</div>
	</div>
	
	<div class="col-md-6 col-xl-3">
		<div class="card user-widget-card bg-c-yellow">
			<div class="card-block">
				<i class="feather icon-repeat bg-simple-c-yellow card1-icon"></i>
				<h4>{{$data['auto_run_yearly']}}</h4>
				<p>Yearly Scheduled</p>
				<a href="{{route('list_extractors')}}?r=yearly" class="more-info">Yearly Scheduled List</a>
			</div>
		</div>
	</div>
	
	
</div>

<div class="row">
	<div class="col-xl-12 col-md-12">
		<div class="card feed-card">
			<div class="card-header">
				<h5>Analytics All times</h5>
			</div>
		<div class="card-block">		
			<!-- HTML -->
			<div id="chartdiv"></div>
			</div>
		</div>
	</div>
</div>
	
	<div class="row">

	<div class="col-xl-8 col-md-12">
		<div class="card feed-card">
			<div class="card-header">
				<h5>User Activities</h5>
			</div>
			<div class="card-block">
				
				@foreach($data['user_activities'] as $activity)
					<div class="row m-b-30">
						<div class="col-auto p-r-0">
							<i class="feather icon-bell bg-simple-c-blue feed-icon"></i>
						</div>
						<div class="col">
							<h6 class="m-b-5">{{$activity->act_desc}} <span class="text-muted f-right f-13">{{$activity->created_at}}</span></h6>
						</div>
					</div>
				@endforeach
				<div class="text-center">
					<a href="{{route('list_activities',['type'=>'user'])}}" class="b-b-primary text-primary">View all Activities</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-md-12">
		<div class="card user-activity-card">
			<div class="card-header">
				<h5>Recent Login</h5>
			</div>
			<div class="card-block">
			@foreach($data['user_activities_login'] as $activity)
				<div class="row m-b-25">
					
					<div class="col">
						<p class="text-muted m-b-0">{{$activity->act_desc}}</p>
						<p class="text-muted m-b-0"><i class="feather icon-clock m-r-10"></i>{{$activity->created_at}}</p>
					</div>
				</div>
			@endforeach
				<div class="text-center">
					<a href="{{route('list_activities',['type'=>'login'])}}" class="b-b-primary text-primary">View all </a>
				</div>
			</div>
		</div>
	</div>
	
	
</div>
@endsection
@section('body-js')
<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);
$.get('{{route("getAnalytics")}}',{},function(data){
	// Add data
chart.data = data ;
});


// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
//dateAxis.renderer.grid.template.location = 0;
//dateAxis.renderer.minGridDistance = 30;

var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis1.title.text = "Actual Data Extracted"; 

var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis2.title.text = "Requests/Columns";
valueAxis2.renderer.opposite = true;
valueAxis2.renderer.grid.template.disabled = true;
//[{"created_at":"2019-06-09 13:03:37","rows_extracted":300,"total_requests":5,"column_extracted":3,"ext_id":15}]
// Create series
var series1 = chart.series.push(new am4charts.ColumnSeries());
series1.dataFields.valueY = "rows_extracted";
series1.dataFields.dateX = "created_at";
series1.yAxis = valueAxis1;
series1.name = "Data Extracted";
series1.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";
series1.fill = chart.colors.getIndex(0);
series1.strokeWidth = 0;
series1.clustered = false;
series1.columns.template.width = am4core.percent(40);

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "counts";
series2.dataFields.dateX = "created_at";
series2.yAxis = valueAxis1;
series2.name = "Total Runs";
series2.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";
series2.fill = chart.colors.getIndex(0).lighten(0.5);
series2.strokeWidth = 0;
series2.clustered = false;
series2.toBack();

var series3 = chart.series.push(new am4charts.LineSeries());
series3.dataFields.valueY = "total_requests";
series3.dataFields.dateX = "created_at";
series3.name = "Total Requests";
series3.strokeWidth = 2;
series3.tensionX = 0.7;
series3.yAxis = valueAxis2;
series3.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";

var bullet3 = series3.bullets.push(new am4charts.CircleBullet());
bullet3.circle.radius = 3;
bullet3.circle.strokeWidth = 2;
bullet3.circle.fill = am4core.color("#fff");

var series4 = chart.series.push(new am4charts.LineSeries());
series4.dataFields.valueY = "column_extracted";
series4.dataFields.dateX = "created_at";
series4.name = "Columns Extracted";
series4.strokeWidth = 2;
series4.tensionX = 0.7;
series4.yAxis = valueAxis2;
series4.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";
series4.stroke = chart.colors.getIndex(0).lighten(0.5);
series4.strokeDasharray = "3,3";

var bullet4 = series4.bullets.push(new am4charts.CircleBullet());
bullet4.circle.radius = 3;
bullet4.circle.strokeWidth = 2;
bullet4.circle.fill = am4core.color("#fff");

// Add cursor
chart.cursor = new am4charts.XYCursor();

// Add legend
chart.legend = new am4charts.Legend();
chart.legend.position = "top";

// Add scrollbar
chart.scrollbarX = new am4charts.XYChartScrollbar();
chart.scrollbarX.series.push(series1);
chart.scrollbarX.series.push(series3);
chart.scrollbarX.parent = chart.bottomAxesContainer;

}); // end am4core.ready()
</script>

@endsection


