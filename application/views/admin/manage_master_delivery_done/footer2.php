<script>
$(document).ready(function(){
	let data = [];
	<?php
	$i = 1;
	foreach ($result as $row)
	{
		?>
		data.push(['<?= $i++; ?>', '<?= ($row->gstvno); ?>', '<?= ($row->date); ?>', '<?= ($row->user_altercode); ?>', '<?= ($row->chemist_code); ?>', 'amount ','<?= ($row->time);?>','<?= trim(preg_replace('/\s\s+/', ' ',$row->message)); ?>','<?= trim(preg_replace('/\s\s+/', ' ', $row->payment_message)); ?> ' , '<?= ($row->payment_type); ?>','<?= ($row->latitude); ?> <?= ($row->longitude); ?>','<a href="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->date);?>/<?= ($row->image1); ?>" target="_blank"><img src="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->date);?>/<?= ($row->image1); ?>" width=100></a>','<a href="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->date);?>/<?= ($row->image2); ?>" target="_blank"><img src="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->date);?>/<?= ($row->image2); ?>" width=100></a>','<a href="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->date);?>/<?= ($row->image3); ?>" target="_blank"><img src="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->date);?>/<?= ($row->image3); ?>" width=100></a>','<a href="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->date);?>/<?= ($row->image4); ?>" target="_blank"><img src="https://drdweb.co.in/upload_drd_master/chemist_photo/<?= ($row->date);?>/<?= ($row->image4); ?>" width=100></a>']);
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