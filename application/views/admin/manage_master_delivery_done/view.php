<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<?php /*<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> */ ?>
   	</div>
	   <form method="get">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
				<div class="input-group date nk-int-st">
					<label>Select Date</label>
					<input type="date" class="form-control" value="<?= $mydate; ?>" name="mydate">
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
			<button type="submit" name="submit" class="btn btn-success notika-btn-success waves-effect" value="submit">Submit</button>
		</div>
	</form>
	<div class="col-xs-12">
        <div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="example-table">
                <thead>
                    <tr>
						<th>
							Sno.
						</th>
						<th>
							Gstvno
						</th>
						<th>
							Vdt
						</th>
						<th>
							Deliverby
						</th>
						<th>
							User Altercode
						</th>
						<th>
							Amount
						</th>
						<th>
							Update Time
						</th>
						<th>
							Message
						</th>
						<th>
							Payment Message
						</th>
						<th>
							Payment Type
						</th>
						<th>
							Map
						</th>
						<th>
							Material photo1
						</th>
						<th>
							Material photo2
						</th>
						<th>
							Payment Detail
						</th>
						<th>
							NR ackn
						</th>
                    </tr>
                </thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
    </div>
</div>