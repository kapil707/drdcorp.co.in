<?php /*
<script>
$(document).ready(function(){
	let data = [];
	let row_done_color = [];
	<?php
	$j = -1;
	$i = 1;
	foreach ($result as $row)
	{
		$chemist_dt = "";
		$chemist_fafa = "";
		if($row->process_status=="1"){
			$chemist_fafa = '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;font-size: 20px;"></i>';
		}
		if($row->process_status=="0"){
			$chemist_fafa = '<i class="fa fa-question-circle" aria-hidden="true" style="color: orange;font-size: 20px;"></i>';
		}
		$search = $row->process_name;
		
		$search_escaped = preg_quote($search, '/');
		$highlighted_text = preg_replace('/(' . $search_escaped . ')/i', '<span style="background-color: yellow;">$1</span>', $row->process_value);

		$invoice_chemist = "";
		$process_invoice = "";
		$fruits_array = explode(",", $row->process_invoice);
		foreach($fruits_array as $rows){
			$process_invoice.= $rows."<br>";

			$arr = explode(":-",$rows);
			$invoice_chemist = $arr[0];
		}
		
		$find = "find by ";
		if(!empty($row->process_invoice)){
			$find.= "<b>invoice</b>,";
		}
		if(!empty($row->chemist_id)){
			$find.= "<b>chemist</b>";
		}
		if(empty($process_invoice) && empty($row->chemist_id)){
			$find = "N/A";
		}
		
		$final_chemist = "";
		$chemist_id_array = explode(",", $row->chemist_id);
		$chemist_id_array = array_unique($chemist_id_array);
		foreach($chemist_id_array as $rows){
			$chemist_dt.= $rows."<br>"; 
			$final_chemist = $rows;
		}
		
		$done_chemist = "";
		$find_all = "";
		if((strtolower($final_chemist)==strtolower($invoice_chemist)) && (!empty($invoice_chemist) && !empty($final_chemist))){
			$find_all = "done";
			$done_chemist = $final_chemist; 
		}
		?>
		$(".myhiden_data_for_modal").append("<p class='myhiden_data_for_modal_id<?= ($row->id); ?>' received_from='<?= ($row->received_from); ?>' chemist_id='<?= ($row->chemist_id); ?>' process_invoice='<?= ($row->process_invoice); ?>' find_by='<?= ($row->find_by); ?>' find='<?= ($find); ?>'></p>")

		data.push(['<?= ($row->status); ?> / <?= ($row->type); ?>', '<?= ($row->date); ?>','<?= ($row->upi_no); ?><br><?= ($row->orderid); ?>','<?= ($row->amount); ?>','<?= ($row->received_from); ?>','<?= ($highlighted_text); ?>','<?= ($chemist_dt); ?>','<?= ($process_invoice); ?>','<?= ($row->find_by); ?><br><?= ($find); ?>','<?= ($find_all); ?>','<input type="text" value="<?php echo $done_chemist ?>">']);
		<?php
		if($find_all=="done"){
			?>
			row_done_color.push(<?php echo $j; ?>);
			<?php
		}
		$j++;
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
		],
		"rowCallback": function( row, data, index ) {
			for (var i = 0; i < row_done_color.length; i++) {
				if (index == row_done_color[i] ) {
					$(row).css("background-color", "rgb(183, 215, 183)");
				}
			}
        }
	});
});

function model_data_add(id){
	received_from = $(".myhiden_data_for_modal_id"+id).attr("received_from")
	chemist_id = $(".myhiden_data_for_modal_id"+id).attr("chemist_id")
	process_invoice = $(".myhiden_data_for_modal_id"+id).attr("process_invoice")
	find_by = $(".myhiden_data_for_modal_id"+id).attr("find_by")
	find = $(".myhiden_data_for_modal_id"+id).attr("find")

	$(".hidden_id").val(id);
	$(".hidden_received_from").val(received_from);

	var chemist_id_array = chemist_id.split(",");
	var chemist_id_val = "";
	for (i=0;i<chemist_id_array.length;i++){
		chemist_id_val+="<li onclick='add_chemist_id(\""+chemist_id_array[i]+"\")'>"+chemist_id_array[i]+"</li>";
	}

	var process_invoice_array = process_invoice.split(",");
	var process_invoice_val = "";
	for (i=0;i<process_invoice_array.length;i++){
		inv = process_invoice_array[i].split(":-");
		process_invoice_val+="<li onclick='add_chemist_id(\""+inv[0]+"\")'>"+process_invoice_array[i]+"</li>";
	}

	$(".main_modal_title").html(find)
	$(".main_modal_p").html("<div class='row'><div class='col-sm-6'>Chemist find by Server : <ul>"+chemist_id_val+"</ul></div><div class='col-sm-6'>Invoice find by Server : <ul>"+process_invoice_val+"</ul></div></div>")
}
function add_chemist_id(id) {
    // Use the 'id' parameter here
    //console.log("chemist_id: " + id);
	$(".add_new_chemist").val(id);
}
function onchange_add_new_chemist(){
	add_new_chemist = $(".add_new_chemist").val();
	console.log(add_new_chemist)
}
function add_chemist_id_by_link_name(){
	id = $(".hidden_id").val();
	chemist_id = $(".add_new_chemist").val();
	string_value = $(".hidden_received_from").val();

	$.ajax({
		type : "POST",
		data : {id:id,chemist_id:chemist_id,string_value:string_value,},
		url  : "<?= base_url()?>admin/<?= $Page_name?>/add_chemist_id_by_link_name",
		cache: true,
		error: function(){
			swal("error add to cart")
		},
		success    : function(data){
		}
	});
}
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script> */ ?>

<script>
	$(document).ready(function(){
		$('.dataTables-example21').DataTable({
			"order": [[0, "desc"]],
			pageLength: 25,
			responsive: true,
			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
				{ extend: 'copy'},
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
$(document).ready(function(){
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
});
</script>