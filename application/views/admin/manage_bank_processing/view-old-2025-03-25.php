<style>
.pg_text_box{
    width: 100px !important;
	border-radius: 10px !important;
    padding: 3px !important;
    height: 26px !important;
    border-radius: 0px !important;
    background: none !important;
    border: none !important;
    border-bottom: solid !important;
}
.myinput1{
	border-radius: 10px;
}
.myborder{
	margin: 0px !important;
    padding: 0px !important;
    margin-left: 15px !important;
	margin-right: -15px !important;
    border: 1px solid #676a6c !important;
}
.myborder1{
	margin: 0px !important;
    padding: 0px !important;
	margin-left: 15px !important;
	margin-right: -15px !important;
    border: 1px solid #676a6c !important;
}
.dataTables-example21 td{
	padding: 3px !important;
	border: none !important;
	border-right: 1px solid #676a6c !important;
	border-bottom: 2px solid #676a6c !important;
}
.dataTables-example21 tr:hover{
	background-color: #eeeeee !important;
}
.td_div {
	word-break: break-all;
    word-wrap: break-word;
    /*width: 280px;*/
    padding: 3px;
    border-bottom: solid 1px #676a6c;
}
.td_div1 {
	word-break: break-all;
    word-wrap: break-word;
    /*width: 280px;*/
    padding: 3px;
}
.td_div0 {
	word-break: break-all;
    word-wrap: break-word;
    padding: 3px;
	border-bottom: solid 1px #676a6c;
	max-height: 120px;
    overflow: auto;
    overflow-x: auto;	
}
.td_div01 {
	word-break: break-all;
    word-wrap: break-word;
    padding: 3px;
    max-height: 120px;
	overflow: auto;
    overflow-x: auto;
}
</style>
<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<!-- <a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> -->
   	</div>
	<?php
	$date_range = "";
	if(isset($_GET["date-range"])){
		$date_range = $_GET["date-range"];
	}
	?>
	<form method="get" class="mb-5" style="margin-bottom:10px;">
		<div class="col-md-3">
			<label for="date-range">Select Date Range:</label>
		</div>
		<div class="col-md-3">
			<input type="text" id="date-range" class="form-control" name="date-range" value="<?php echo $date_range ?>">
		</div>
		<div class="col-md-3">
		</div>
		<div class="col-md-3">
			<button type="submit" class="btn btn-info submit_button" name="Submit">
				<i class="ace-icon fa fa-check bigger-110"></i>
				Submit
			</button>
		</div>
	</form>
	<div class="col-xs-12">
		<div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example21">
                <thead>
                    <tr>
						<th width="20px">Id</th>
						<th></th>
						<!-- <th width="250px">Upi No || Orderid || Amount|| Date</th>
						<th width="250px">From || Find || Find by || Status</th>
						<th width="">Invoice || WhatsApp</th>
						<th width="250px">Chemist || Invoice || WhatsApp</th> -->
                    </tr>
                </thead>
				<tbody>
				<?php
				$i= 1;
				foreach ($result as $entry) {
					$tr_style = "";
					$chemist_fafa[] = "";
					/********************************************** */
					$row_id = $entry->id;
					/****************************************************** */
					$status = "Add just now";
					if($entry->status==1){
						$status = "Add From SMS";
					}
					if($entry->status==2){
						$status = "Add From Statment";
					}
					if($entry->status==4){
						$status = "Done by Admin";
					}
					if($entry->status==7){
						$status = "Invoice Find Done";
					}
					if($entry->status==8){
						$status = "Ready For Download";
					}
					$row_status = "<b>Status : </b>".$status;
					/****************************************************** */
					$row_date = "<b>Date : </b>".date("d-M-Y",strtotime($entry->date));
					/****************************************************** */
					if($entry->from_sms==1){
						$row_type = "SMS";
					}
					if($entry->from_statment==1){
						$row_type = "Statment";
					}
					if($entry->from_sms==1 && $entry->from_statment==1){
						$row_type = "SMS/Statment";
					}
					/****************************************************** */
					$row_upi_no = $entry->upi_no;
					$row_upi_no1 = "<b>Upi No : </b>".$entry->upi_no;
					/****************************************************** */
					$row_orderid = "<b>Orderid : </b>".$entry->orderid;
					/****************************************************** */
					$row_amount = "<b>Amount : </b>Rs.".$entry->amount."/-";
					/****************************************************** */
					$row_from_text = $entry->received_from;
					$from_text = $entry->received_from;
					/********************************************** */
					$from_value_find = $entry->from_value_find;
					/********************************************** */
					$from_value = $entry->from_value;
					/********************************************** */
					$from_value_find = preg_quote($from_value_find, '/');
					$row_from_text_find = preg_replace('/(' . $from_value_find . ')/i', '<span style="background-color: yellow;">$1</span>', $from_value);
					if(empty($from_value)){
						$row_from_text_find = "N/a";
					}
					/********************************************** *
					$row_from_text_logic = $entry->final_find_by;
					if(!empty($row_from_text_logic)){
						$row_from_text_logic = " || (<b>$row_from_text_logic</b>)";
					}
					if(empty($from_value)){
						$row_from_text_logic = ""; //yha jab work karta ha jab chemist find nahi hua or find me kuch aa raha ha to wo empty ho jaya
					}
					// chemist find karta ha yha logic
					/********************************************** */
					$chemist = $entry->find_chemist;
					$chemist_array = explode("||", $chemist);
					$chemist_array = array_unique($chemist_array);
					// if(count($chemist_array)==1){
					// 	$singal_chemist_id = $chemist_array[0];
					// }
					$row_chemist_id = "";
					if(!empty($chemist_array)){
						foreach($chemist_array as $rows){
							$row_chemist_id.= $rows;
							if(!empty($chemist_fafa[$rows])){
								$row_chemist_id.= $chemist_fafa[$rows];
							}
							$row_chemist_id.= " || ";
						}
					}
					if(!empty($row_chemist_id)){
						$row_chemist_id = substr($row_chemist_id, 0, -4);
					}
					if(empty($chemist_array[0])){
						$row_chemist_id = "";
					}

					$row_invoice_chemist = $entry->invoice_chemist ? $entry->invoice_chemist : "N/a";
					$row_invoice_text = $entry->invoice_text;
					$row_invoice_recommended = $entry->invoice_recommended;
					
					// invoice say chemist find karta ha yha logic
					/********************************************** *
					$find_invoice_chemist_id = $entry->invoice;
					$find_invoice_chemist_id_array = explode("||", $find_invoice_chemist_id);
					$row_find_invoice_all = $row_invoice_chemist = "";
					foreach($find_invoice_chemist_id_array as $rows){
						$rows = str_replace('Amt.', 'Rs.', $rows);
						$rows1 = str_replace('Amt-x.', 'Rs.', $rows);
						$row_find_invoice_all.= $rows1."/-<br>";
						$arr = explode(":-",$rows);
						$row_invoice_chemist.= $arr[0]." || ";
					}
					$row_invoice_chemist = explode(" || ", $row_invoice_chemist);
					$row_invoice_chemist = array_unique($row_invoice_chemist);
					$row_invoice_chemist = implode(' || ', $row_invoice_chemist);
					if(!empty($row_invoice_chemist)){
						$row_invoice_chemist = substr($row_invoice_chemist, 0, -4);
					}
					if(!empty($entry->final_invoice_chemist)) {
						$find_invoice_chemist_id = $entry->final_invoice;
						$row_find_invoice_all = $row_invoice_chemist = "";
						$find_invoice_chemist_id_array = explode(",", $find_invoice_chemist_id);
						foreach($find_invoice_chemist_id_array as $rows){
							$rows = str_replace('Amt.', 'Rs.', $rows);
							$row_find_invoice_all.= $rows."/-<br>";
						}
						$row_invoice_chemist = $entry->final_invoice_chemist;
					}
					/*************************************************/
					if(empty($find_invoice_chemist_id)){
						$row_find_invoice_all = "N/a";
					}
					/********************************************** */
					$row_whatsapp_id = $entry->whatsapp_id;
					/********************************************** */
					$row_whatsapp_chemist = $entry->whatsapp_chemist;
					$row_whatsapp_remanded = $entry->whatsapp_remanded;
					/********************************************** */
					//$row_whatsapp = $entry->whatsapp;//$entry->whatsapp_body2;
					$row_whatsapp = $entry->vision_text;//$entry->whatsapp_body2;
					/********************************************** */
					/********************************************** */
					$final_chemist = $entry->final_chemist;
					$row_find_by_invoice_chemist_id = $entry->invoice;
					/********************************************** */	
					$textbox_final_chemist = "";
					$row_find_by = "";
					$row_find_i = 0;
					if(!empty($row_chemist_id)){
						$row_find_by.= "Chemist,";
						$row_find_i++;
					}
					if(!empty($row_whatsapp_chemist)){
						$row_find_by.= "Whatsapp,";
						$row_find_i++;
					}
					if(!empty($row_find_by_invoice_chemist_id)){
						$row_find_by.= "Invoice,";
						$row_find_i++;
					}
					if(!empty($row_find_by)){
						$row_find_by = substr($row_find_by, 0, -1);
					}
					if(empty($row_chemist_id) && empty($row_invoice_chemist) && empty($row_whatsapp_chemist)){
						$row_find_by = "N/a";
						$tr_style = "background-color: #f9f9f9";
					}
					if($row_find_i==1){
						$tr_style = "background-color: khaki";
					}
					if($row_find_i==2){
						$tr_style = "background-color: #99ff99";
					}
					/********************************************** */
					$row_whatsapp_chemist1 = 
					$row_whatsapp_chemist1 = str_replace(' ', '', $row_whatsapp_chemist);
					$row_whatsapp_chemist1 = str_replace('-', '', $row_whatsapp_chemist1);
					if((strtolower($row_chemist_id)==strtolower($row_invoice_chemist)) && (strtolower($row_chemist_id)==strtolower($row_whatsapp_chemist1)) && (!empty($row_chemist_id) && !empty($row_invoice_chemist) && !empty($row_whatsapp_chemist1))){
						$tr_style = "background-color: #1ab394;";
						$textbox_final_chemist = $row_chemist_id;
					}
					// jab koi be chemist find na ho to
					/********************************************** */
					if(empty($row_chemist_id)){
						$row_chemist_id = "N/a";
					}
					// jab koi be invoice say chemist find na ho to
					/********************************************** */
					if(empty($row_find_by_invoice_chemist_id)){
						$row_find_by_invoice_chemist_id = "N/a";
					}
					// jab koi be whatapp say chemist find na ho to
					/********************************************** */
					if(empty($row_whatsapp_chemist)){
						$row_whatsapp_chemist = "N/a";
					}
					// jab koi be whatapp say chemist find na ho to
					/********************************************** */
					if(empty($row_whatsapp)){
						$row_whatsapp = "N/a";
					}
					// jab user done kar dayta ha to color change hota ha iss say
					/********************************************** */
					$done_status = $entry->status;
					if($entry->status==3){
						$tr_style = "background-color: #a9c5f9";
					}
					if($entry->status==4){
						$tr_style = "background-color: #e8ffe2;";					
					}
					
					//new25
					// jab processing ho jaya or chemist id na milay to
					if($entry->process_status==1 && empty($entry->find_chemist)){
						$tr_style = "background-color:rgb(255 130 130);";
					}
					?>
					<tr class="tr_css_<?php echo $row_id; ?>" style="<?php echo $tr_style ?>">
						<td>
							<?php echo $i++; ?>
						</td>
						<td>
							<div class="row">
								<div class="col-sm-10">
									<?php if(!empty($entry->sms_text)) { ?>
										<b>SMS :</b> <?= $entry->sms_text; ?> 
									<?php } ?>

									<?php if(!empty($entry->sms_text) && !empty($entry->statment_text)) { echo "<br>"; } ?>

									<?php if(!empty($entry->statment_text)) { ?>
										<b>Statment :</b> <?= $entry->statment_text; ?>
									<?php } ?>
								</div>

								<div class="col-sm-2 text-right">	
									<i class="fa fa-refresh row_refresh_id_<?= ($row_id); ?>" aria-hidden="true" onclick="row_refresh('<?= ($row_id); ?>')"></i> Refresh
									<?php if($entry->recommended){ echo " || <b>Recommended : $entry->recommended</b>"; }?>
								</div>
								
								<div class="col-sm-4 myborder">
									<div class="td_div">
										<?= $row_upi_no1; ?> <b>(<?= $row_type; ?>)</b>
									</div>
									<div class="td_div">
										<?= $row_orderid; ?>
									</div>
									<div class="td_div">
										<?= $row_amount; ?>
									</div>
									<div class="td_div1">
										<?= $row_date; ?>
									</div>
								</div>

								<div class="col-sm-4 myborder1">
									<div class="td_div">
										<b>From :</b> <?= ($row_from_text); ?>

										<input type="hidden" value="<?php echo $from_text ?>" class="text_from_text_<?= ($row_id); ?>">
										<input type="text" value="<?php echo $chemist; ?>" class="form-control myinput1 text_from_text_chemist_id_<?= ($row_id); ?> pg_text_box" style="display:none;">
										<i class="fa fa-check add_from_text_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="add_from_text_chemist_id('<?= ($row_id); ?>')" style="display:none"></i>
										<i class="fa fa-times cancel_from_text_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="cancel_from_text_chemist_id('<?= ($row_id); ?>')" style="display:none"></i>
										<i class="fa fa-pencil edit_from_text_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="edit_from_text_chemist_id('<?= ($row_id); ?>')"></i>
									</div>
									<div class="td_div">
										<b>Find  : </b> <?= ($row_from_text_find); ?>
									</div>
									<div class="td_div">
										<b>Find by : </b> 
										<?= $row_find_by; ?>
									</div>
									<div class="td_div1">
										<?= $row_status; ?>
									</div>
								</div>

								<div class="col-sm-4 myborder1">
									<div class="td_div">
										<b>Chemist : </b>
										<?= $row_chemist_id;?>
									</div>
									<div class="td_div">
										<b>Invoice : </b>
										<?= $row_invoice_chemist ?>

										<?php if($row_invoice_chemist=="N/a" &&$row_invoice_remanded) { 
											echo " || <b>Remanded : $row_invoice_remanded </b>";
										} ?>
									</div>
									<div class="td_div">
										<b onclick="get_whats_message('<?= ($row_id); ?>','<?= ($row_whatsapp_id); ?>','<?= $row_upi_no; ?>')" data-toggle="modal" data-target="#myModal">WhatsApp : </b>
										<?= $row_whatsapp_chemist; ?>

										<?php if($row_whatsapp_chemist=="N/a" &&$row_whatsapp_remanded) { 
											echo " || <b>Remanded : $row_whatsapp_remanded </b>";
										} ?>
									</div>
									<div class="td_div1">
										<b style="float: left; margin-right:5px;">Final Chemist : </b>
										<?php if(empty($textbox_final_chemist) && $row_chemist_id != "N/a"){
											$textbox_final_chemist = $row_chemist_id;
										}?>

										<input type="text" value="<?php echo $textbox_final_chemist ?>" class="form-control text_final_chemist_id_<?= ($row_id); ?> pg_text_box" style="<?php if(!empty($entry->final_chemist)) { ?>display:none;<?php } ?>float: left !important;" placeholder="Chemist Id">
										<i class="fa fa-check add_final_chemist_<?= ($row_id); ?>" aria-hidden="true" onclick="add_final_chemist('<?= ($row_id); ?>')" style="<?php if(!empty($entry->final_chemist)) { ?>display:none;<?php } ?> float: left;font-size: 20px;"></i>

										<span class="span_final_chemist_<?= ($row_id); ?>" <?php if(empty($entry->final_chemist)) { ?>style="display:none" <?php } ?>><?php echo $final_chemist ?></span>
										<i class="fa fa-pencil edit_final_chemist_<?= ($row_id); ?>" aria-hidden="true" onclick="edit_final_chemist('<?= ($row_id); ?>')" <?php if(empty($entry->final_chemist)) { ?>style="display:none" <?php } ?>></i>
									</div>
								</div>
								
								<?php if($row_invoice_text!="") { ?>
								<div class="col-sm-12">
									<b>Invoice Text : </b>
									<?= ($row_invoice_text); ?>
								</div>
								<?php } ?>

								<?php if($row_whatsapp!="N/a") { ?>
								<div class="col-sm-12">
									<b onclick="get_whats_message('<?= ($row_id); ?>','<?= ($row_whatsapp_id); ?>','<?= $row_upi_no; ?>')" data-toggle="modal" data-target="#myModal">WhatsApp Text : </b>
									<?= ($row_whatsapp); ?>
								</div>
								<?php } ?>
								
							</div>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
    </div>
</div>
<script>
function add_final_chemist(id){
	var from_text = $(".text_from_text_"+id).val();
	var chemist_id = $(".text_final_chemist_id_"+id).val();
	if(chemist_id.trim()==""){
		alert("Etner any chemist id")
	}else{
		$(".text_final_chemist_id_"+id).hide();
		$(".add_final_chemist_"+id).hide();
		$(".span_final_chemist_"+id).html(chemist_id);
		$(".span_final_chemist_"+id).show();
		$(".edit_final_chemist_"+id).show();
		$.ajax({
			type : "POST",
			data : {id:id,chemist_id:chemist_id,from_text:from_text},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_final_chemist",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Save successfully');
				$(".tr_css_"+id).css("background-color", "#e8ffe2");
			}
		});
	}
}
function edit_final_chemist(id){
	$(".text_final_chemist_id_"+id).show();
	$(".add_final_chemist_"+id).show();
	$(".span_final_chemist_"+id).hide();
	$(".edit_final_chemist_"+id).hide();
}
function add_from_text_chemist_id(id){
	var from_text = $(".text_from_text_"+id).val();
	var chemist_id = $(".text_from_text_chemist_id_"+id).val();
	if(chemist_id.trim()==""){
		alert("Etner any chemist id")
	}else{
		cancel_from_text_chemist_id(id);
		$.ajax({
			type : "POST",
			data : {chemist_id:chemist_id,from_text:from_text,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_from_text_chemist_id",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Chemist set successfully');
			}
		});
	}
}
function edit_from_text_chemist_id(id){
	$(".text_from_text_chemist_id_"+id).show();
	$(".add_from_text_chemist_id_"+id).show();
	$(".cancel_from_text_chemist_id_"+id).show();
	$(".edit_from_text_chemist_id_"+id).hide();
}
function cancel_from_text_chemist_id(id){
	$(".text_from_text_chemist_id_"+id).hide();
	$(".add_from_text_chemist_id_"+id).hide();
	$(".cancel_from_text_chemist_id_"+id).hide();
	$(".edit_from_text_chemist_id_"+id).show();
}
function row_refresh(id){
	$.ajax({
		type : "POST",
		data : {id:id},
		url  : "<?= base_url()?>admin/<?= $Page_name?>/row_refresh",
		cache: true,
		error: function(){
			toastr.error('Error');
		},
		success: function(data){
			toastr.info('Refresh successfully');
			$(".tr_css_"+id).css("background-color", "aliceblue");
		}
	});
}
var row_id;
function get_whats_message(row_id1,row_whatsapp_id,row_upi_no){
	row_id = row_id1;
	$(".modal-title").html("Upi No : "+row_upi_no);
	$.ajax({
		type : "POST",
		data : {row_whatsapp_id:row_whatsapp_id},
		url  : "<?= base_url()?>admin/<?= $Page_name?>/get_whats_message",
		cache: true,
		error: function(){
			toastr.error('Error');
		},
		success: function(data){
			var table = $(".get_whats_message");
			table.find("tr:gt(0)").remove();
			$.each(data.items, function(i,item){
				if (item)
				{
					var row = "<tr>" +
							"<td>" + item.from_number + "</td>" +
							"<td>" + item.body + "</td>" +
							"<td>" +item.ist_timestamp+ "</td>" +
							"<td>" +item.vision_text+ "</td>" +
							"<td> <a href='https://api.wassi.chat" +item.screenshot_image+ "?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e' target='_blank'>	<img src='https://api.wassi.chat" +item.screenshot_image+ "?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e' width='100px'></a> </td>" +
							"</tr>";
					table.append(row);
				}
			});
		},
		timeout: 60000
	});
}
function add_whatsapp_chemist_id(){
	var whatsapp_chemist = $(".text_whatsapp_chemist_id").val();
	if(whatsapp_chemist.trim()==""){
		alert("Etner any chemist id")
	}else{
		$.ajax({
			type : "POST",
			data : {row_id:row_id,whatsapp_chemist:whatsapp_chemist,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_whatsapp_chemist_id",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Chemist set successfully');
				$(".text_whatsapp_chemist_id").val('');
			}
		});
	}
}
</script>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered table-hover get_whats_message">
			<tr>
				<td>From</td>
				<td>Body</td>
				<td>Time</td>
				<td>Vision Text</td>
				<td>Image</td>
			</tr>
		</table>
		<b style="float: left;">Add WhatsApp Chemist : </b>
		<input type="text" value="<?php echo $textbox_final_chemist ?>" class="form-control text_whatsapp_chemist_id" style="float: left; width: 100px; border-radius: 10px;" placeholder="Chemist Id">
		<i class="fa fa-check add_whatsapp_chemist_id" aria-hidden="true" onclick="add_whatsapp_chemist_id()" style="float: left;font-size: 20px;"></i>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>