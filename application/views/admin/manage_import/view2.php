<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button>
   	</div>
    <div class="col-xs-12">
        <div class="table-responsive">
			<table id="data-table-basic" class="table table-striped table-bordered table-hover dataTables-example">
                <thead>
                    <tr>
                    	<th>
                        	Sno.
                        </th>
						<th>
							Item
						</th>
						<th>
							Quantity
						</th>
						<th>
							MRP
						</th>
						<th>
							Total
						</th>
                    </tr>
                </thead>
                <tbody>
                <?php
				$i=1;
                foreach ($result as $row)
                {
					$total_q	= $total_q   + $row->quantity;
					$total_m 	= $total_m   + $row->mrp;
					$total_q_m 	= $total_q_m + ($row->quantity * $row->mrp);
					?>
                    <tr id="row_<?= $row->id; ?>">
                    	<td>
                        	<?= $i++; ?>
                        </td>
						<td>
                        	<?= $row->item_name;?>
                        </td>
						<td>
                        	<?= $row->quantity;?>
                        </td>
						<td>
                        	<?= $row->mrp;?>
                        </td>
						<td>
                        	<?= $row->quantity * $row->mrp;?>
                        </td>
					</tr>
                    <?php
                    }
                    ?>
                </tbody>
				<tfoot>
                    <tr>
                    	<th>
                        	
                        </th>
						<th>
							
						</th>
						<th>
							<?php echo $total_q ?>
						</th>
						<th>
							<?php echo money_format('%!i',$total_m); ?>
						</th>
						<th>
							<?php echo money_format('%!i',$total_q_m); ?>
						</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>