<link href="<?= base_url()?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url()?>/assets/font-awesome/css/font-awesome.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<link href="<?= base_url()?>/assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<div class="container-fluid p-5">
	<div class="row">
        <div class="col-md-3">
			<label for="date-range">Select Date Range:</label>
    		<input type="text" id="date-range" class="form-control">
        </div>
    </div>
	<div class="row">
		<div class="col-sm-12  pt-1 pb-5">
			<table class="table table-striped table-bordered table-hover dataTables-example" id="example-table">
				<thead>
					<tr>
						<th>
							Sno.
						</th>
						<th>
							Sender
						</th>
						<th>
							Message
						</th>
						<th>
							Date
						</th>
						<th>
							Time
						</th>
						<th>
							ChemistID
						</th>
					</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
	</div>
</div>
<script src="<?= base_url()?>/assets/js/jquery-3.1.1.min.js"></script>
<script src="<?= base_url()?>/assets/js/plugins/dataTables/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
var table;
$(document).ready(function(){

	var today = new Date();
    var day = today.getDate();
    var month = today.getMonth() + 1; // January is 0!
    var year = today.getFullYear();

    if (day < 10) {
        day = '0' + day;
    }

    if (month < 10) {
        month = '0' + month;
    }

    var formattedDate = year + '-' + month + '-' + day;

	from_date = to_date = formattedDate;

	table = $('#example-table').DataTable({
		ajax: {
		url: 'https://www.drdweb.co.in/upload_sms/api01/get_upload_sms',
			type: 'POST',
			data: function(d) {
				return $.extend({}, d, {
					from_date: from_date,
					to_date: to_date
				});
			},
			dataSrc: 'items'
		},
		order: [[4, 'desc']],
		columns: [
			{ data: 'id', title: 'ID' },
			{ data: 'sender', title: 'Sender' },
			{ data: 'message_body', title: 'Message Body' },
			{ data: 'date', title: 'Date' },
			{ data: 'time', title: 'Time' },
			{ data: 'chemist_id', title: 'ChemistID' }
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

	$('#date-range').daterangepicker({
		opens: 'left', // Date picker position
		locale: {
			format: 'DD-MM-YYYY', // Date format
			separator: ' to ',
			applyLabel: 'Apply',
			cancelLabel: 'Cancel',
			daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
			monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
		}
	});

	$('#date-range').on('apply.daterangepicker', function(ev, picker) {
		
        var selectedDates = $('#date-range').val().split(' to ');
		if (selectedDates.length === 2) {
			from_date = selectedDates[0].trim();
			to_date = selectedDates[1].trim();

			from_date 	= data_formet_change(from_date);
			to_date 	= data_formet_change(to_date);
		}
		table.ajax.reload();
    });

	function data_formet_change(dateValue){
		var dateParts = dateValue.split('-');
		if (dateParts.length === 3) {
			var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
			return formattedDate;
		}
	}
	//reload_page();
})
function reload_page(){

	table.ajax.reload();
	setInterval(function () {
		reload_page();
	}, 120000);
}
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>