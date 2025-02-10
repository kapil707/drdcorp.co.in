<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
var table;
$(document).ready(function(){

	// Initialize DataTable
	table = $('#view_api_table').DataTable({
		ajax: {
			url: '<?php echo base_url(); ?>admin/<?php echo $Page_name ?>/view_api',
			type: 'POST',
			data: '',
			dataSrc: 'items'
		},
		order: [[0, 'asc']],
		columns: [
			{ data: 'sr_no', title: 'Id' },
			{ data: 'chemist_id', title: 'ChemistId' },
			{ data: 'salesman_id', title: 'SalesmanId' },
			{ data: 'datetime', title: 'DateTime' }
		],
		pageLength: 25,
		responsive: true,
		dom: '<"html5buttons"B>lTfgitp',
		buttons: [
			{extend: 'copy'},
			{extend: 'csv'},
			{extend: 'excel', title: 'ExampleFile'},
			{extend: 'pdf', title: 'ExampleFile'},
			{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					$(win.document.body).find('table')
							.addClass('compact')
							.css('font-size', 'inherit');
				}
			}
		]
	});

	// Set the reload interval to refresh the table every 2 minutes (120000 milliseconds)
	setInterval(function () {
		reload_page();
	}, 120000);
});

// Function to reload the DataTable
function reload_page(){
	table.ajax.reload();
}
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>