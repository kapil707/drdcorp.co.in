<div class="row">
	<?php
	//$date_range = "";
	if(isset($_GET["date-range"])){
		$date_range = $_GET["date-range"];
	}
	?>
    <div class="col-xs-2">
    	<a href="add">
            <button class="btn btn-info">
                Add
            </button>
        </a>
   	</div>
	<form method="get" class="mb-5">
		<div class="col-xs-2">
			<label for="date-range">Date Range:</label>
		</div>
		<div class="col-xs-4">
			<input type="text" id="date-range" class="form-control" name="date-range" value="<?php echo $date_range ?>">
		</div>
		<div class="col-xs-2">
			<button type="submit" class="btn btn-info submit_button" name="Submit">
				<i class="ace-icon fa fa-check bigger-110"></i>
				Submit
			</button>
		</div>		
		<div class="col-xs-2">
			
		</div>
	</form>
	<div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover dataTables-example">
				<thead>
					<tr>
						<th>Party Code</th>
						<th>Party Name</th>
						<th>Date</th>
						<th>Bank Name</th>
						<th>Ifsc Code</th>
						<th>Amount In Words</th>
						<th>Amount</th>
						<th>Reciever</th>
						<th>Account Number</th>
						<th>Cheque Number</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;			
				foreach ($result as $row) { ?>
					<tr class="tr_css_<?php echo $row->id; ?>">
						<td>
							<?php echo $row->party_code; ?>
						</td>
						<td>
							<?php echo $row->party_name; ?>
						</td>
						<td>
							<?php echo $row->date; ?>
						</td>
						<td>
							<?php echo $row->bank_name; ?>
						</td>
						<td>
							<?php echo $row->ifsc_code; ?>
						</td>
						<td>
							<?php echo $row->amount_in_words; ?>
						</td>
						<td>
							<?php echo $row->amount_in_digits; ?>
						</td>
						<!-- <td>
							<?php echo date('m/d/Y', strtotime($row->date)); ?>
						</td> -->
						<td>
							<?php echo $row->reciever; ?>
						</td>
						<td>
							<?php echo $row->account_number; ?>
						</td>
						<td>
							<?php echo $row->cheque_number; ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>