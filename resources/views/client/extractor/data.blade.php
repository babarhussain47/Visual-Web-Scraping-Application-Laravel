

    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('theme/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
    <!-- Style.css -->


	<div class="row">
		<div class="col-sm-12">
			<!-- Ajax data source (Arrays) table start -->
			<div class="card">
				<div class="card-header">
					<h5>All Data</h5>
					<span></span>

				</div>
				<div class="card-block">
					<div class="table-responsive dt-responsive">
						<table id="dt-ajax-array" class="table table-striped table-bordered nowrap">
							<thead>
								<tr>
								@foreach(json_decode($extractor->ext_bot,true) as $key => $value)
									<th>{{$value["name"]}}</th>
								@endforeach
								</tr>
							</thead>
							<!--tfoot>
								<tr>
									<th>Name</th>
									<th>Position</th>
									<th>Office</th>
									<th>Extn.</th>
									<th>Start date</th>
									<th>Salary</th>
								</tr>
							</tfoot-->
						</table>
					</div>
				</div>
			</div>
			<!-- Ajax data source (Arrays) table end -->
		</div>
	</div>
		
	 <!-- data-table js -->
    <script src="{{asset('theme/files/bower_components/datatables.net/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/assets/pages/data-table/js/jszip.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/assets/pages/data-table/js/pdfmake.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/assets/pages/data-table/js/vfs_fonts.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/files/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}" type="text/javascript"></script>

	
	<script>
$(document).ready(function() {
	 // Data-table ajax
    $('#dt-ajax-array').DataTable(
		 {
        dom: 'Bfrtip',
        buttons: [
		
                'print',
				'copyHtml5',
				'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
			{
            extend: 'excelHtml5',
            autoFilter: true,
            sheetName: 'Exported data'
        }
        ]
    ,
        "ajax": "https://dev.handyimport.io/user/extractor/"+<?php echo $extractor->ext_id?>+"/ajax-data/<?php echo Auth::user()->id;?>?_token=<?php echo csrf_token();?>"
    });
});
	</script>
	