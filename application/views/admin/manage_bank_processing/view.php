<style>
@media (min-width: 992px) {
    .modal-lg {
        width: 900px;
    }
}
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
	width: 32%;
	margin: 0px !important;
    padding: 0px !important;
    margin-left: 15px !important;
	margin-right: -10px !important;
    border: 1px solid #676a6c !important;
}
.myborder1{
	width: 32%;
	margin: 0px !important;
    padding: 0px !important;
	margin-left: 15px !important;
	margin-right: -10px !important;
    border: 1px solid #676a6c !important;
}
.myborder2{
	width: 32%;
	margin: 0px !important;
    padding: 0px !important;
	margin-left: 15px !important;
	margin-right: -10px !important;
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
.text_find_match {
	padding: 2px;
	color: white;
	background-color: #0c9f00;
	border-radius: 5px;
}

.text_find_match_not {
	padding: 2px;
	color: white;
	background-color: #9f0032;
	border-radius: 5px;
}

.text_find_match_edit {
	padding: 2px;
	color: white;
	background-color: #c96912;
	border-radius: 5px;
}
.duble_tick{
	width: 16px;
    fill: #0c9f00;
    margin-bottom: -6px;
}
.duble_tick_not{
	width: 16px;
    fill: #9f0032;
    margin-bottom: -6px;
}
.blink_me {
	color:#9f0032;
  	animation: blinker 2s linear infinite;
}

.blink_me_white {
  	animation: blinker 2s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
<?php 
$duble_tick = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="duble_tick"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 178.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l80 80c12.5 12.5 32.8 12.5 45.3 0l160-160zm96 128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 402.7 54.6 297.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l256-256z"/></svg>';
?>
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
	<div class="col-xs-12">
		<b>Others</b> Total SMS : <?php echo $other_total_sms; ?> ||
		Total Statment : <?php echo $other_total_statment; ?> ||
		Total Whatsapp : <?php echo $other_total_whatsapp; ?> ||
		Total Invoice : <?php echo $other_total_invoice; ?>
	</div>
	<div class="col-xs-12" style="margin-top:10px;margin-bottom:10px;">
		<b>Processing</b> Entries : <?php echo $total_processing; ?> ||
		Find SMS : <?php echo $total_sms; ?> ||
		Find Statment : <?php echo $total_statment; ?> ||
		Find Whatsapp Message : <?php echo $total_whatsapp; ?> ||
		Find Whatsapp Chemist : <?php echo $total_whatsapp_chemist; ?> ||
		Find Invoice : <?php echo $total_invoice; ?> ||
		Find Chemist : <?php echo $total_find_chemist; ?> ||
		Find Not Chemist : <?php echo $total_not_find_chemist; ?>
	</div>
	<form method="get" class="mb-5">
		<div class="col-md-3">
			<label for="date-range">Select Date Range:</label>
		</div>
		<div class="col-md-3">
			<input type="text" id="date-range" class="form-control" name="date-range" value="<?php echo $date_range ?>">
		</div>
		<div class="col-md-3">
			<select name="check_type" class="form-control">
				<option value="">Select All</option>

				<option value="find_sms" 
					<?php if($check_type=="find_sms") { ?> selected <?php } ?>>
					Find SMS
				</option>

				<option value="find_statment" 
					<?php if($check_type=="find_statment") { ?> selected <?php } ?>>
					Find Statment
				</option>

				<option value="find_all" 
					<?php if($check_type=="find_all") { ?> selected <?php } ?>>
					Find All
				</option>

				<option value="find_chemist" 
					<?php if($check_type=="find_chemist") { ?> selected <?php } ?>>
					Find Chemist
				</option>
				
				<option value="not_find_chemist" 
					<?php if($check_type=="not_find_chemist") { ?> selected <?php } ?>>
					Not Find Chemist
				</option>

				<option value="find_whatsapp" 
					<?php if($check_type=="find_whatsapp") { ?> selected <?php } ?>>
					Find Whatsapp
				</option>

				<option value="not_find_whatsapp" 
					<?php if($check_type=="not_find_whatsapp") { ?> selected <?php } ?>>
					Not Find Whatsapp
				</option>

				<option value="find_invoice" 
					<?php if($check_type=="find_invoice") { ?> selected <?php } ?>>
					Find Invoice
				</option>

				<option value="not_find_invoice" 
					<?php if($check_type=="not_find_invoice") { ?> selected <?php } ?>>
					Not Find Invoice
				</option>
			</select>
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
					/********************************************** */
					$row_id = $entry->id;
					$row_process_status = $entry->process_status;
					$row_process_status_value = "Just Added";
					if($row_process_status==1){
						$row_process_status_value = "Find From Chemist Done";
					}
					if($row_process_status==2){
						$row_process_status_value = "Find From Chemist/WhatsApp Done";
					}
					if($row_process_status==3){
						$row_process_status_value = "Find From Chemist/WhatsApp/Invoice Done";
					}
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
					/************************************************ */
					$row_from_text 				= $entry->from_text;
					$row_from_text_find 		= $entry->from_text_find;
					$row_from_text_find_match 	= $entry->from_text_find_match;
					$row_from_text_find_chemist = $entry->from_text_find_chemist;
					$row_final_chemist 			= $entry->final_chemist;
					$row_final_chemist_textbox	= $entry->final_chemist;
					/********************************************** */
					$row_from_text_find_match_value = preg_quote($row_from_text_find_match, '/');
					$row_from_text_find_match_value = preg_replace('/(' . $row_from_text_find_match_value . ')/i', '<span class="text_find_match">$1</span>', $row_from_text_find);
					if(empty($row_from_text)){
						$row_from_text_find_match_value = "N/a";
					}
					/********************************************** */
					$row_invoice_chemist = $entry->invoice_chemist;
					$row_invoice_text = $entry->invoice_text;
					$row_invoice_recommended = $entry->invoice_recommended;
					/********************************************** */
					$row_whatsapp_id = $entry->whatsapp_id;
					$row_whatsapp_chemist = $entry->whatsapp_chemist;
					$row_whatsapp_text = $entry->whatsapp_text;
					$row_whatsapp_recommended = $entry->whatsapp_recommended;
					$row_whatsapp_number = $entry->whatsapp_number;
					$row_whatsapp_timestamp = $entry->whatsapp_timestamp;
					/********************************************** */
					
					$row_find_by = "";
					$row_find_i = 0;
					if(!empty($row_from_text_find_chemist)){
						$row_find_by.= "Chemist,";
						$row_find_i++;
					}
					if(!empty($row_whatsapp_chemist)){
						$row_find_by.= "Whatsapp,";
						$row_find_i++;
					}
					if(!empty($row_invoice_chemist)){
						$row_find_by.= "Invoice,";
						$row_find_i++;
					}
					if(!empty($row_find_by)){
						$row_find_by = substr($row_find_by, 0, -1);
					}
					/********************************************** */
					//recommended say find_chemist ko set karta ha yha
					$row_recommended = $entry->recommended;
					$row_recommended_value = "";
					if(!empty($row_recommended)){
						$row_recommended_value = "<div class='col-sm-12'><b class='blink_me'>Find Chemist ($row_from_text_find_chemist) : Recommended set by invoice or whatsapp : ".$row_recommended."</b></div>";	
						$row_from_text_find_chemist = $row_recommended;
					}
					/********************************************** */
					//jab user sirf invoice say or find chemist say milata ha to final textbox me value add hoti ha
					if((strtolower($row_from_text_find_chemist)==strtolower($row_invoice_chemist)) && empty($row_whatsapp_chemist)){
						$row_final_chemist_textbox = $row_from_text_find_chemist;
					}
					//jab user sirf whatsapp say or find chemist say milata ha to final textbox me value add hoti ha
					if((strtolower($row_from_text_find_chemist)==strtolower($row_whatsapp_chemist)) && empty($row_invoice_chemist)){
						$row_final_chemist_textbox = $row_from_text_find_chemist;
					}
					//jab user sirf invoice/whatsapp say or find chemist say milata ha to final textbox me value add hoti ha
					$row_all_chemist_match = 0;
					if((strtolower($row_from_text_find_chemist)==strtolower($row_invoice_chemist)) && (strtolower($row_from_text_find_chemist)==strtolower($row_whatsapp_chemist)) && !empty($row_from_text_find_chemist) && !empty($row_invoice_chemist) && !empty($row_whatsapp_chemist)){

						$tr_style = "background-color: #1ab394;";
						$row_all_chemist_match = 1;

						if(empty($row_final_chemist_textbox)){
							$row_final_chemist_textbox = $row_from_text_find_chemist;
						}
					}
					if(empty($row_from_text_find_chemist)){
						$tr_style = "background-color: #ff9494;";
					}

					if(!empty($row_from_text_find_chemist)){
						$tr_style = "background-color: #fcf7ca;";
					}

					if(!empty($row_whatsapp_chemist)){
						$tr_style = "background-color: #f0e68c";
					}

					if(!empty($row_invoice_chemist)){
						$tr_style = "background-color: #f0e68c;";
					}
					
					if(!empty($row_from_text_find_chemist) && !empty($row_whatsapp_chemist) && !empty($row_invoice_chemist)){
						$tr_style = "background-color: #b1ffbf;";
					}

					//jab recommended match say user set ho jaya to
					if(!empty($row_recommended)){
						$tr_style = "background-color: #80e3ff;";
					}
					if(!empty($row_final_chemist)){
						$tr_style = "background-color: #4bff97;";
					}
					?>
					<tr class="tr_css_<?php echo $row_id; ?>" style="<?php echo $tr_style ?>">
						<td>
							<?php echo $i++; ?>
						</td>
						<td>
							<div class="row">
								<?php
								if(!empty($row_recommended_value)){
									echo $row_recommended_value;
								}?>
								<div class="col-sm-10">
									<?php if(!empty($entry->sms_text)) { ?>
										<b>SMS :</b> <?= $entry->sms_text; ?> 
									<?php } ?>

									<?php if(!empty($entry->sms_text) && !empty($entry->statment_text)) { echo "<br>"; } ?>

									<?php if(!empty($entry->statment_text)) { ?>
										<b>Statment :</b> <?= $entry->statment_text; ?>
										-- (From Find By <?php echo $entry->statment_type ?>)
									<?php } ?>
								</div>

								<div class="col-sm-2 text-right">
									<?php if($row_process_status!=0){ ?>
									<i class="fa fa-refresh row_refresh_id_<?= ($row_id); ?>" aria-hidden="true" onclick="row_refresh('<?= ($row_id); ?>')"></i> Refresh
									<?php } ?>
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
										<b>From :</b>
										<?= ($row_from_text); ?>

										<?php 
										if($row_process_status!=0){
											if(empty($textbox_final_chemist) && empty($row_from_text_find_chemist)){ ?>
											<br>
											<b class="text_find_match blink_me_white edit_from_text_chemist_id_<?= ($row_id); ?>" onclick="edit_from_text_chemist_id('<?= ($row_id); ?>')">Add Chemist <i class="fa fa-pencil" aria-hidden="true"></i></b>

											<input type="hidden" value="<?php echo $row_from_text ?>" class="text_from_text_<?= ($row_id); ?>" placeholder='Set Chemist'>

											<input type="text" value="<?php echo $row_from_text_find_chemist; ?>" class="form-control myinput1 text_from_text_chemist_id_<?= ($row_id); ?> pg_text_box" style="display:none;" placeholder="Set Chemist Id">

											<b class="text_find_match add_from_text_chemist_id_<?= ($row_id); ?>" onclick="add_from_text_chemist_id('<?= ($row_id); ?>')" style="display:none">
												Set <i class="fa fa-check" aria-hidden="true"></i>
											</b>
											&nbsp;
											<b class="text_find_match_not cancel_from_text_chemist_id_<?= ($row_id); ?>" onclick="cancel_from_text_chemist_id('<?= ($row_id); ?>')" style="display:none">
												Cancel <i class="fa fa-times" aria-hidden="true"></i>
											</b>
										<?php } ?>
										<?php } ?>
									</div>
									<div class="td_div">
										<b>Find  : </b> 
										
										<span class="from_text_find_match_id_<?= ($row_id); ?>">
										<?php if(!empty($row_from_text_find_match)) { 
											echo ($row_from_text_find_match_value);
										} else {
											echo "N/a";
										}?>
										<?php 
										if(strtolower($row_from_text)==strtolower($row_from_text_find)){
											echo $duble_tick;
										}
										?>
										</span>
									</div>
									<div class="td_div">
										<b>Find by : </b> 
										<?php 
										if(!empty($row_find_by)) { 
											echo $row_find_by; 
										}else{
											echo "N/a";
										}?>
									</div>
									<div class="td_div1">
										<b>Status : </b>
										<?= $row_process_status_value; ?>
									</div>
								</div>

								<div class="col-sm-4 myborder2">
									<div class="td_div">
										<b>Find Chemist : </b>
																		
										<span class="from_text_find_chemist_id_<?= ($row_id); ?>">
										<?php 
										if(!empty($row_from_text_find_chemist))
										{
											echo $row_from_text_find_chemist;
										} else {
											echo "N/a";
										}?>
										<?php
										if($row_all_chemist_match==1){
											echo $duble_tick;
										}
										?>
										</span>
									</div>
									<div class="td_div">
										<b>Find Invoice : </b>
										<?php 
										if(!empty($row_invoice_chemist))
										{
											echo $row_invoice_chemist;
										} else {
											echo "N/a";
										}?>

										<?php
										if((strtolower($row_invoice_chemist)==strtolower($row_from_text_find_chemist)) && !empty($row_invoice_chemist)){
											echo $duble_tick;
										}
										?>
										
										<?php if(empty($row_invoice_chemist) &&!empty($row_invoice_recommended)) { 
											echo " || <b>Recommended : $row_invoice_recommended </b>";
										} ?>
									</div>
									<div class="td_div">
										<b>Find WhatsApp : </b>
										
										<?php 
										if(!empty($row_whatsapp_chemist))
										{
											echo $row_whatsapp_chemist;
										} else {
											echo "N/a";
										}?>

										<?php
										if((strtolower($row_whatsapp_chemist)==strtolower($row_from_text_find_chemist)) && !empty($row_whatsapp_chemist)){
											echo $duble_tick;
										}
										?>
										
										<?php if(empty($row_whatsapp_chemist) &&!empty($row_whatsapp_recommended)) { 
											echo " || <b>Recommended : $row_whatsapp_recommended </b>";
										} ?>
										
										<?php if(!empty($entry->whatsapp_set_chemist)) { 
											echo " || <b>Set : $entry->whatsapp_set_chemist </b>";
										} ?>
									</div>
									<div class="td_div1">
										<span class="span_final_chemist_id_<?= ($row_id); ?>">

											<?php if(empty($textbox_final_chemist) && empty($row_from_text_find_chemist)){
												?>
												<?php if($row_process_status!=0){ ?>
												<b style="float: left; margin-right:5px;" class="text_find_match_not blink_me_white">Please Set form Chemist </b>
												<?php } ?>
												<?php if($row_process_status==0){ ?>
												<b style="float: left; margin-right:5px;" class="text_find_match_not blink_me_white">Loading Please Wait....</b>
												<?php } ?>
												<?php
												$mycss_chemist_final = "display:none;";
											} else {
												$mycss_chemist_final = "";
											} ?>
										</span>

										<b style="float: left; margin-right:5px;<?php echo $mycss_chemist_final ?>" class="span_final_chemist_lbl_<?= ($row_id); ?>">Final Chemist : </b>

										<span class="span_final_chemist_<?= ($row_id); ?>" <?php if(empty($row_final_chemist)) { ?>style="display:none" <?php } ?>>
											<?php echo $row_final_chemist ?>
										</span>
										
										<span class="text_find_match_edit edit_final_chemist_<?= ($row_id); ?>" onclick="edit_final_chemist('<?= ($row_id); ?>')" <?php if(empty($row_final_chemist)) { ?>style="display:none" <?php } ?>>
											Edit <i class="fa fa-pencil" aria-hidden="true"></i>
										</span>

										<input type="text" value="<?php echo $row_final_chemist_textbox ?>" class="form-control text_final_chemist_id_<?= ($row_id); ?> pg_text_box" style="<?php if(!empty($row_final_chemist)) { ?>display:none;<?php } ?>float: left !important;<?php echo $mycss_chemist_final ?>" placeholder="Chemist Id">
										
										<span class="text_find_match add_final_chemist_<?= ($row_id); ?>" onclick="add_final_chemist('<?= ($row_id); ?>')" style="<?php if(!empty($row_final_chemist)) { ?>display:none;<?php } ?>float: left;<?php echo $mycss_chemist_final ?>">
											Set
											<i class="fa fa-check" aria-hidden="true"></i>
										</span>

										<span class="text_find_match_not cancel_final_chemist_<?= ($row_id); ?>" onclick="cancel_final_chemist('<?= ($row_id); ?>')" style="margin-left:10px;display:none;float: left;">
											Cancel <i class="fa fa-times" aria-hidden="true"></i>
										</span>
									</div>
								</div>
								
								<?php if($row_invoice_text!="") { ?>
								<div class="col-sm-12">
									<b>Invoice-Text : </b>
									<?= ($row_invoice_text); ?>
								</div>
								<?php } ?>

								<?php if($row_whatsapp_text!="") { ?>
								<div class="col-sm-12">
									<b onclick="get_whatsapp_message('<?= ($row_id); ?>','<?= ($row_whatsapp_id); ?>','<?= $row_upi_no; ?>')" data-toggle="modal" data-target="#myModal">
									<span class='<?php if((strtolower($row_whatsapp_chemist)==strtolower($row_from_text_find_chemist)) && $row_whatsapp_chemist!="N/a"){ echo "text_find_match"; } if(strtolower($row_whatsapp_chemist)!=strtolower($row_from_text_find_chemist)) { echo "text_find_match_not blink_me_white"; }?>'>WhatsApp-Text :</span></b>
									Number : <?= ($row_whatsapp_number); ?>,
									Date / Time : <?= date('d-M-y \a\t H:i:s',$row_whatsapp_timestamp) ?><br><b>Message :</b> <?= ($row_whatsapp_text); ?>
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

	var chemist_id = $(".text_final_chemist_id_"+id).val();
	
	if(chemist_id.trim()==""){
		alert("Etner any chemist id")
	}else{
		$(".cancel_final_chemist_"+id).hide();
		$(".text_final_chemist_id_"+id).hide();
		$(".add_final_chemist_"+id).hide();
		$(".span_final_chemist_"+id).html(chemist_id);
		$(".span_final_chemist_"+id).show();
		$(".edit_final_chemist_"+id).show();
		$.ajax({
			type : "POST",
			data : {id:id,chemist_id:chemist_id},
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
	$(".cancel_final_chemist_"+id).show();
	$(".add_final_chemist_"+id).show();

	$(".span_final_chemist_"+id).hide();
	$(".edit_final_chemist_"+id).hide();
}
function cancel_final_chemist(id){
	$(".text_final_chemist_id_"+id).hide();
	$(".cancel_final_chemist_"+id).hide();
	$(".add_final_chemist_"+id).hide();

	$(".span_final_chemist_"+id).show();
	$(".edit_final_chemist_"+id).show();
}
function add_from_text_chemist_id(id){
	var from_text = $(".text_from_text_"+id).val();
	var chemist_id = $(".text_from_text_chemist_id_"+id).val();

	if(chemist_id.trim()==""){
		alert("Etner any chemist id")
	}else{
		cancel_from_text_chemist_id(id);

		$(".edit_from_text_chemist_id_"+id).html('Edit <i class="fa fa-pencil" aria-hidden="true"></i>');		

		$(".from_text_find_match_id_"+id).html(from_text + '<?php echo $duble_tick ?>');

		$(".from_text_find_chemist_id_"+id).html(chemist_id);

		$(".tr_css_"+id).css("background-color", "#eeeeee");

		
		$(".span_final_chemist_id_"+id).hide();	
		$(".span_final_chemist_lbl_"+id).show();
		$(".text_final_chemist_id_"+id).show();
		$(".add_final_chemist_"+id).show();

		$(".text_final_chemist_id_"+id).val(chemist_id);

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
function get_whatsapp_message(row_id1,row_whatsapp_id,row_upi_no){
	row_id = row_id1;
	$(".modal-title").html("Upi No : "+row_upi_no);
	$.ajax({
		type : "POST",
		data : {row_whatsapp_id:row_whatsapp_id},
		url  : "<?= base_url()?>admin/<?= $Page_name?>/get_whatsapp_message",
		cache: true,
		error: function(){
			toastr.error('Error');
		},
		success: function(data){
			var table = $(".get_whatsapp_message");
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
        <table class="table table-striped table-bordered table-hover get_whatsapp_message">
			<tr>
				<td>From</td>
				<td>Body</td>
				<td>Time</td>
				<td>Vision Text</td>
				<td>Image</td>
			</tr>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>