<script>
$(document).ready(function(){
	let data = [];
	<?php
	$i = 1;
	foreach ($result as $row)
	{
		$status = "Inactive";
		?>
		data.push(['<?= ($i++); ?>','<?= ($row->altercode); ?>','<?= ($row->name); ?>','<?= ($row->email); ?>','<?= ($row->mobile); ?>','<?= ($row->address); ?>','<?= ($row->website_limit); ?> / <?= ($row->android_limit); ?>','<?= ($row->status); ?>','<div class="btn-group"><a href="edit/<?php echo $row->id;?>" class="btn-white btn btn-xs">Edit</a></div>','<?php if($row->id2!="") { ?><a href="javascript:void(0)" onclick=logout_fun("<?= ($row->altercode); ?>")>Logout</a><?php } ?>']);
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