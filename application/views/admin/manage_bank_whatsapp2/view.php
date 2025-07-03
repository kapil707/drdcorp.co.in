<style>
.pg_text_box{
    width: 60px !important;
	border-radius: 10px !important;
    padding: 3px !important;
    height: 26px !important;
    border-radius: 0px !important;
    background: none !important;
    border: none !important;
    border-bottom: solid !important;
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
		<?php
		/*$parmiter = '';
		$curl = curl_init();
			
			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL =>"http://192.46.214.43:5000/groups",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 0,
					CURLOPT_TIMEOUT => 300,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_POSTFIELDS => $parmiter,
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json',
						'Authorization: Bearer THIRTEENWOLVESWENTHUNTINGBUT10CAMEBACK'
					),
				)
			);

			$response = curl_exec($curl);
			//print_r($response);
			curl_close($curl);

			$data0 = json_decode($response, true); // Convert JSON string to associative array
			?>
			<select class="form-control" name="select_group">
				<?php 
				if (isset($data0['groups'])) {
					foreach ($data0['groups'] as $groups) {
						if(!empty($groups)){
							?>
							<option value="xxxx">
								<?php echo $groups ?>
							</option>
							<?php
						}
					}
				}
				?>
			</select> */ ?>
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
						<th>
							ID
						</th>
						<th>
							Date / Time
						</th>
						<th>
							Number
						</th>
						<th>
							Body
						</th>
						<th>
							Vision Text
                        </th>
						<th>
							Image<br>UPI No
                        </th>
						<th>
							Chemist
                        </th>
						<th>
							Amount
                        </th>
                    </tr>
                </thead>
				<tbody>
					<?php
						foreach ($result as $message) {
							$body = $message->body ? $message->body : "Body not found";

							$reply_body = $message->reply_body ? $message->reply_body : "";
							if($reply_body)
							{
								$reply_body = "Reply Body : " .$reply_body;
							}

							$date = $message->date ? $message->date : "Date not found";

							$extracted_text = $message->extracted_text ? $message->extracted_text : "extracted_text not found";
							
							$vision_text = $message->vision_text ? $message->vision_text : "vision_text not found";

							$from_number = $message->from_number ? $message->from_number : "Date not found";
							
							$id = $message->id;
							
							$row_id = $message->id;

							$screenshot_image = $message->screenshot_image ? $message->screenshot_image : "screenshot_image not found";

							$timestamp = $message->timestamp ? $message->timestamp : "timestamp not found";
							
							$chemist_id = "";
							if(!empty($message->find_chemist))
							{
								$chemist_id = $message->find_chemist;
							}

							if(!empty($message->final_chemist))
							{
								$chemist_id = $message->final_chemist;
							}

							if(!empty($message->set_chemist))
							{
								$chemist_id = $message->set_chemist;
							}

							$extracted_text = str_replace("\n", "<br>", $extracted_text);
							$vision_text = str_replace("\n", "<br>", $vision_text);
							?>
							<tr>
								<td>
									<?php echo $id; ?>
								</td>
								<td>
									<?php //echo date('d-M-y \a\t H:i:s', $timestamp); ?>
								</td>
								<td>
									<?php echo $from_number; ?>
								</td>
								<td>
									<?php echo $body; ?><br>
									<b><?php echo $reply_body ;?></b>
								</td>
								<td><?php echo $vision_text; ?></td>
								<td>
									<?php if(!empty($media_id)) { ?>
									<b data-toggle="modal" data-target="#myModal" onclick="get_full_image('https://api.wassi.chat<?php echo $media_id; ?>?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e')">	<img src="https://api.wassi.chat<?php echo $media_id; ?>?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e" width="100px">
									</b>
									<?php } ?>
									<br>
									<?php if(!empty($message->upi_no)){ ?>
										<b>UPI No </b>: <?php echo $message->upi_no; ?>
									<?php } ?>
								</td>
								<td width="250">
									<span style="">Find Chemist :
									<?php if(!empty($message->find_chemist)){ 
										echo $message->find_chemist; 
									} else {
										echo "N/a";	
									}?>
									</span>
									<br>
									<span style="">Final Chemist :
									<?php if(!empty($message->final_chemist)){ 
										echo $message->final_chemist; 
									} else {
										echo "N/a";	
									}?>
									</span>
									<br>
									<span style="">Set Chemist :
										<span class="span_chemist_<?= ($row_id); ?>">
											<?php if(!empty($message->set_chemist)){ 
												echo $message->set_chemist; 
											} else {
												echo "N/a";	
											}?>
										</span>
									</span>
									<br>
									<?php if(empty($message->final_chemist)){ ?>
										<span class="text_find_match_edit edit_chemist_<?= ($row_id); ?>" onclick="edit_chemist('<?= ($row_id); ?>')" style="margin-left:5px;">
											Edit <i class="fa fa-pencil" aria-hidden="true"></i>
										</span>
									<?php } ?>

									<input type="text" value="<?php echo $chemist_id; ?>" class="text_chemist_<?= ($row_id); ?> pg_text_box" style="float: left !important;display:none;" placeholder="Set Chemist Id">

									<br><br>
									
									<span class="text_find_match add_chemist_<?= ($row_id); ?>" onclick="add_chemist('<?= ($row_id); ?>')" style="display:none;margin-left:5px;"> 
										Set <i class="fa fa-check" aria-hidden="true"></i>
									</span>

									<span class="text_find_match_not cancel_chemist_<?= ($row_id); ?>" onclick="cancel_chemist('<?= ($row_id); ?>')" style="display:none;margin-left:5px;"> 
										Cancel <i class="fa fa-times" aria-hidden="true"></i>
									</span>
										
								</td>
								<td width="150px">
									<span style="">Amount : </span>
									<span class="span_amount_<?= ($row_id); ?>" style=""><?php echo $message->amount; ?></span>

									<br>
									<span class="text_find_match_edit edit_amount_<?= ($row_id); ?>" onclick="edit_amount('<?= ($row_id); ?>')" style="margin-left:5px;">
										Edit <i class="fa fa-pencil" aria-hidden="true"></i>
									</span>
									
									<br>
									<input type="text" value="<?php echo $message->amount; ?>" class="text_amount_<?= ($row_id); ?> pg_text_box" style="float: left !important;display:none;" placeholder="Amount">

									<br><br>

									<span class="text_find_match add_amount_<?= ($row_id); ?>" onclick="add_amount('<?= ($row_id); ?>')" style="display:none;margin-left:5px;"> 
										Set <i class="fa fa-check" aria-hidden="true"></i>
									</span>

									<span class="text_find_match_not cancel_amount_<?= ($row_id); ?>" onclick="cancel_amount('<?= ($row_id); ?>')" style="display:none;margin-left:5px;"> 
										Cancel <i class="fa fa-times" aria-hidden="true"></i>
									</span>
								</td>
							</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
    </div>
</div>
<script>
function edit_chemist(id){
	$(".span_chemist_"+id).hide();
	$(".edit_chemist_"+id).hide();
	
	$(".text_chemist_"+id).show();
	$(".add_chemist_"+id).show();
	$(".cancel_chemist_"+id).show();

	var chemist = $(".text_chemist_"+id).val();
	if(chemist=="N/a"){
		$(".text_chemist_"+id).val('');
	}
}

function cancel_chemist(id){
	$(".text_chemist_"+id).hide();

	$(".add_chemist_"+id).hide();
	$(".cancel_chemist_"+id).hide();

	$(".span_chemist_"+id).show();
	$(".edit_chemist_"+id).show();
}

function add_chemist(id){

	cancel_chemist(id);

	var chemist = $(".text_chemist_"+id).val();
	if(chemist.trim()==""){
		alert("Enter Chemist")
	}else{
		$.ajax({
			type : "POST",
			data : {row_id:id,chemist:chemist,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_chemist",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Chemist set successfully');
				$(".span_chemist_"+id).html(chemist);
			}
		});
	}
}


function edit_amount(id){
	$(".span_amount_"+id).hide();
	$(".edit_amount_"+id).hide();
	
	$(".text_amount_"+id).show();
	$(".add_amount_"+id).show();
	$(".cancel_amount_"+id).show();

	var amount = $(".text_amount_"+id).val();
	if(amount=="N/a" || amount=="0.0"){
		$(".text_amount_"+id).val('');
	}
}

function cancel_amount(id){
	$(".text_amount_"+id).hide();

	$(".add_amount_"+id).hide();
	$(".cancel_amount_"+id).hide();

	$(".span_amount_"+id).show();
	$(".edit_amount_"+id).show();
}

function add_amount(id){
	cancel_amount(id);
	
	var amount = $(".text_amount_"+id).val();
	if(amount.trim()==""){
		alert("Etne amount")
	}else{
		$.ajax({
			type : "POST",
			data : {row_id:id,amount:amount,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_amount",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Amount set successfully');
				$(".span_amount_"+id).html(amount);
			}
		});
	}
}
function get_full_image(url){
	$('#myfullimg').attr('src', url);
}
</script>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Image</h4>
      </div>
      <div class="modal-body">
        <img id="myfullimg" src="https://via.placeholder.com/150" alt="Default Image" width="100%">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>