<div class="row">
    <div class="col-xs-12">
		<a href="<?php echo base_url(); ?>admin/<?= $Page_name ?>">
		    <button type="button" class="btn btn-w-m btn-info"><< Back</button>
		</a>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
		<?php
        foreach ($result as $row)
        { ?>
        	<!-- <input type="hidden" name="old_password" value="<?= $row->password; ?>" /> -->
            <div class="form-group" id="data_5">
           		<div class="col-sm-4">
					<div class="col-sm-12">
                        <label class="control-label" for="form-field-1">
                            String Value
                        </label>
                    </div>
                    <div class="col-sm-12">
						<input type="text" class="form-control" value="<?php echo $row->string_value;?>" name="string_value" required>
                    </div>
                </div>

                <div class="col-sm-4">
					<div class="col-sm-12">
                        <label class="control-label" for="form-field-1">
                            Chemist Id
                        </label>
                    </div>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" value="<?php echo $row->chemist_id;?>" name="chemist_id" required>
                    </div>
                </div>
            </div>

            <!-- <div class="form-group" id="data_5">
				<div class="col-sm-4">
                    <div class="col-sm-12">
                        <label class="control-label" for="form-field-1">
                            Status
                        </label>
                    </div>
                    <div class="col-sm-12">
                        <select name="status" id="status" data-placeholder="Select Status" class="chosen-select">
							<option value="1" <?php if($row->status==1) { ?> selected <?php } ?>>
								Active
							</option>
							<option value="0" <?php if($row->status==0) { ?> selected <?php } ?>>
								Inactive
							</option>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('status'); ?>
                        </span>
                    </div>
                </div>
			</div> -->
            <div class="space-4"></div>
            <br /><br />
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-info" name="Submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>
                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>
            <?php } ?>
        </form>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div>