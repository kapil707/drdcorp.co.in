<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<!-- <a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> -->
    </div>
	<div class="col-xs-12">
		<div class="row">
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
				</div><div class="col-md-3">
					<button type="submit" class="btn btn-info submit_button" name="Submit">
						<i class="ace-icon fa fa-check bigger-110"></i>
						Submit
					</button>
				</div>
			</form>
		</div>
		<div class="row">
			<div class="col-sm-12  pt-1 pb-5">
				<div class="table-responsive">
					<?php /*<table class="table table-striped table-bordered table-hover" id="example-table"> */ ?>
					<table class="table table-striped table-bordered table-hover dataTables-example21">
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
									Amount
								</th>
								<th>
									Upi No
								</th>
								<th>
									ChemistID
								</th>
								<th>
									Date
								</th>
								<th>
									Time
								</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$i = 1;
						foreach($result as $row){ 
							$row_id = $row->id; ?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo $row->sender; ?></td>
								<td><?php echo $row->message_body; ?></td>
								<td><?php echo $row->amount; ?></td>
								<td><?php echo $row->upi_no; ?></td>
								<td width="160">
								<?php if(!empty($row->chemist_id)) { ?>					
								<span class="" style="float: left;">
									Final Chemist : <?php echo $row->chemist_id; ?><br>
								</span>
								<?php } 
								$set_chemist = "";
								$set_chemist_txt = "";
								if(empty($row->set_chemist) && empty($row->chemist_id) && $row->amount=="Amount not found" && $row->upi_no=="UPI reference number not found"){ 
									$set_chemist = "";
									$set_chemist_txt = "N/a";
								}
								if(empty($row->set_chemist) && empty($row->chemist_id) && $row->amount!="Amount not found" && $row->upi_no!="UPI reference number not found"){ 
									$set_chemist = "";
									$set_chemist_txt = "Set Chemist";
								}
								if(!empty($row->set_chemist)){
									$set_chemist = $row->set_chemist;
									$set_chemist_txt = $row->set_chemist;
								}
								?>
								<span class="span_chemist_<?= ($row_id); ?>" style="float: left;">
									<?php echo $set_chemist_txt; ?>
								</span>
								<?php
									if(empty($row->chemist_id) && $row->amount!="Amount not found" && $row->upi_no!="UPI reference number not found"){
										?>
										<i class="fa fa-pencil edit_chemist_<?= ($row_id); ?>" aria-hidden="true" onclick="edit_chemist('<?= ($row_id); ?>')" style="float: left;font-size: 13px;margin-left:10px;"></i>
										
										<input type="text" value="<?php echo $set_chemist; ?>" class="text_chemist_<?= ($row_id); ?> pg_text_box" style="float: left !important;display:none;" placeholder="chemist">
									
										<i class="fa fa-check add_chemist_<?= ($row_id); ?>" aria-hidden="true" onclick="add_chemist('<?= ($row_id); ?>')" style="float: left;font-size: 18px;display:none;"></i>
										<?php
									}
								?>
								</td>
								<td><?php echo $row->date; ?></td>
								<td><?php echo $row->time; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
function edit_chemist(id){
	$(".span_chemist_"+id).hide();
	$(".edit_chemist_"+id).hide();
	
	$(".text_chemist_"+id).show();
	$(".add_chemist_"+id).show();
}

function add_chemist(id){
	$(".span_chemist_"+id).show();
	$(".edit_chemist_"+id).show();
	
	$(".text_chemist_"+id).hide();
	$(".add_chemist_"+id).hide();
	
	var chemist = $(".text_chemist_"+id).val();
	if(chemist.trim()==""){
		alert(chemist)
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
</script>