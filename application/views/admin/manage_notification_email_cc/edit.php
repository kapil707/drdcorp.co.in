<div class="row">
	<div class="col-xs-12">
		<button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post">
        <?php
        foreach ($result as $row)
        { ?> 
            <div class="form-group">
                <div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Email
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="form-field-1" placeholder="Email" name="email" value="<?= $row->email; ?>" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('email'); ?>
                        </span>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Email cc
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="form-field-1" placeholder="Email cc" name="email_cc" value="<?= $row->email_cc; ?>" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('email_cc'); ?>
                        </span>
                    </div>
                </div>				
            </div>
                
            <div class="form-group">			
                <div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Email Function
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="email_function_id" id="email_function_id" data-placeholder="Select Email Function" class="chosen-select" required>
                            <option value="0">
                                Select
                            </option>
                            <?php
                            $result1 = $this->db->query("SELECT * FROM `tbl_email` WHERE `status`=1")->result();
                            foreach($result1 as $row1){
                                ?>
                                    <option value="<?php echo $row1->id; ?>" <?php if($row->email_function_id==$row1->id) { echo "selected"; } ?>>
                                        <?php echo $row1->email_function; ?>
                                    </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('email_function_id'); ?>
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Status
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="status" id="status" data-placeholder="Select Status" class="chosen-select">
                            <option value="0" <?php if($row->status==0) { echo "selected"; } ?>>							
                                Inactive
                            </option>
                            <option value="1" <?php if($row->status==1) { echo "selected"; } ?>>							
                                Active
                            </option>
                        </select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('status'); ?>
                        </span>
                    </div>
                </div>
            </div>      

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
</div><!-- /.row -->