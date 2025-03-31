<script>
$(document).ready(function(){
	let data = [];
	<?php
	$i = 1;
	foreach ($result as $row)
	{
		$status = "Inactive";
		if($row->status==1){
			$status = "Active";
		}
		$passwordstatus = "Inactive";
		if($row->password!=''){
			$passwordstatus = "Active";
		}
		?>
		data.push(['<?= ($row->code); ?>', '<?= ($row->altercode); ?>','<?= ($row->name); ?>','<?= ($row->email); ?>','<?= ($row->mobile); ?>','<?= ($status); ?>','<?= ($passwordstatus); ?>','<img src="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->image); ?>" width=100>','<a href="<?= base_url(); ?>admin/<?php echo $Page_name ?>/edit/<?= ($row->code); ?>">Edit</a>']);
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