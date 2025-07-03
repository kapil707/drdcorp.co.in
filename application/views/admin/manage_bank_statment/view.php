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
			<a href="<?= base_url()?>admin/<?= $Page_name?>/statment_excel_file?date-range=<?= $date_range ?>" class="btn btn-info">Download Statment</a>
		</div>
	</form>
	<div class="col-xs-4">
		<a href="view">
            <button class="btn btn-info">
                All formet
            </button>
        </a>
		<a href="view1">
            <button class="btn btn-info">
                formet 1
            </button>
        </a>
		<a href="view2">
            <button class="btn btn-info">
                formet 2
            </button>
        </a>
		<a href="view3">
            <button class="btn btn-info">
                formet 3
            </button>
        </a>
	</div>
	<form method="post">
		<div class="col-sm-6">&nbsp;</div>
		<div class="col-xs-2">
			<input type="submit" name="checkbox-submit" value="Submit Checkbox" class="btn btn-info">
		</div>
		<div class="col-xs-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover dataTables-example">
					<thead>
						<tr>
							<th>Account Number</th>
							<th>Branch Number</th>
							<th>Statement Date</th>
							<th>Closing Ledger Balance</th>
							<th>Calculated Balances</th>
							<th>Amount</th>
							<th>Entry Date</th>
							<th>Value Date</th>
							<th>Bank Reference</th>
							<th>Customer Reference</th>
							<th>Narrative</th>
							<th>Transaction Description</th>
							<th>Chemist</th>
							<th>Invoice</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$i = 1;			
					foreach ($result as $row) { ?>
						<tr class="tr_css_<?php echo $row->id; ?>">
							<td>
								<?php echo $row->account_no; ?>
							</td>
							<td>
								<?php echo $row->branch_no; ?>
							</td>
							<td>
								<?php echo $row->statment_date; ?>
							</td>
							<td>
								<?php echo $row->closing_ledger_balance; ?>
							</td>
							<td>
								<?php echo $row->calculated_balances; ?>
							</td>
							<td>
								<?php echo $row->amount; ?>
							</td>
							<td>
								<?php echo $row->enter_date; ?>
							</td>
							<td>
								<?php echo date('m/d/Y', strtotime($row->date)); ?>
							</td>
							<td>
								<?php echo $row->bank_reference; ?>
							</td>
							<td>
								<?php echo $row->customer_reference; ?>
							</td>
							<td>
								<?php echo $row->narrative; ?>
							</td>
							<td>
								<?php echo $row->transaction_description; ?>
							</td>
							<td>
								<?php echo $row->chemist_id; ?>
							</td>
							<td>
								<?php 
								$gstvNo = "";
								$invoice = $row->invoice_number; 
								if(!empty($invoice)){
									$parts = explode("||", $invoice);
									foreach($parts as $invoice) {
										preg_match('/GstvNo:([\w-]+)/', $invoice, $matches);
										$gstvNo.= $matches[1].',';
									}
									$gstvNo = substr($gstvNo, 0, -1);
								}
								echo $gstvNo;
								?>
							</td>
							<td>
								<?php if(empty($row->essysol_id2)){ ?>
									<label><input type="checkbox" name="checkbox[]" value="<?php echo $row->customer_reference; ?>">Checkbox</label>
								<?php } else {
									if(!empty($row->essysol_id)){
										echo "Essysol Id : ".$row->essysol_id;
									} else { ?>
								 	<input type="hidden" name="essysol_id2" value="<?php echo $row->essysol_id2 ?>">
									<input type="submit" name="checkbox-delete" value="Delete" class="btn btn-danger" onclick="if (confirm('Delete this entry!')) return true; else return false;">
								<?php } }?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>