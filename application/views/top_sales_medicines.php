<link href="<?= base_url()?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url()?>/assets/font-awesome/css/font-awesome.css" rel="stylesheet">

<div class="container-fluid">
	<div class="row mt-5 p-5">
		<div class="col-sm-12 pt-1 pb-5">
			<table class="table table-striped table-bordered table-hover dataTables-example" id="example-table">
				<thead>
					<tr>
						<th>
							Sno.
						</th>
						<th>
							Medicine Name
						</th>
						<th>
							Medicine Code
						</th>
						<th>
							Medicine Sales
						</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 0;
						foreach($result as $row) {
							$i++;
							?>
							<tr>
								<td>
									<?php echo $i; ?>
								</td>
								<td>
									<?php echo $row->item_name; ?>
								</td>
								<td>
									<?php echo $row->item_code; ?>
								</td>
								<td>
									<?php echo $row->ct; ?>
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
<script src="<?= base_url()?>/assets/js/jquery-3.1.1.min.js"></script>
<script src="<?= base_url()?>/assets/js/plugins/dataTables/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>