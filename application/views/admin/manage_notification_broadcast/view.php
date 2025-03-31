<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
		<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a>
		
		<a href="<?php echo base_url(); ?>admin/manage_notification_broadcast/add2/broadcast_title">
		    <button type="button" class="btn btn-w-m btn-info">Broadcast Title</button>
		</a>
		
		<a href="<?php echo base_url(); ?>admin/manage_notification_broadcast/add2/broadcast_message">
		    <button type="button" class="btn btn-w-m btn-info">Broadcast Message</button>
		</a>
		
		<a href="<?php echo base_url(); ?>admin/manage_notification_broadcast/add2/broadcast_status">
		    <button type="button" class="btn btn-w-m btn-info">Broadcast Status</button>
		</a>
   	</div>
	<div class="col-xs-12">
		<div class="row">
			<div class="col-md-3">
				<label for="date-range">Select Date Range:</label>
				<input type="text" id="date-range" class="form-control">
				<br>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12  pt-1 pb-5">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="example-table">
						<thead>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>