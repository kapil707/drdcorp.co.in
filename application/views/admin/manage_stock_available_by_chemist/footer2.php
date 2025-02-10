<script>
$(document).ready(function(){
	let data = [];
	<?php
	$i = 1;
	foreach ($result as $row)
	{
		$status = "Inactive";
		?>
		data.push(['<?= ($i++); ?>','<?= ($row->i_code); ?>','<?= ($row->item_name); ?>','<?= $this->StockLowModel->get_chemist_id($row->i_code); ?>']);
		<?php
	}
	?>
	$('#example-table').DataTable({
		data: data,
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
});
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>