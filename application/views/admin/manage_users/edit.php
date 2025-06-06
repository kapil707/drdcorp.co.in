<div class="row">
	<div class="col-xs-12">
		<!-- <button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button> -->
        <a href="<?php echo base_url(); ?>admin/<?php echo $Page_name ?>">
            <button type="submit" class="btn btn-info">
                << Back
            </button>
        </a>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post">
        <?php
        foreach ($result as $row)
        { ?>
            <input type="hidden" name="old_password" value="<?= $row->password; ?>" />
            <input type="hidden" name="old_image" value="<?= $row->image; ?>" />
			<div class="form-group">			
           		<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Name
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="form-field-1" placeholder="Name" name="name" value="<?= $row->name; ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('name'); ?>
                        </span>
                    </div>
                </div>
				
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Email Id
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="form-field-1" placeholder="Email Id" name="email" value="<?= $row->email; ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('email'); ?>
                        </span>
                    </div>
                </div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            User Type
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="user_type" id="user_type" data-placeholder="Select User Type" class="chosen-select">
							<option value="">
								
							</option>
							<?php
							$user_type = $row->user_type;
                            $user_id = $this->session->userdata("user_id");
							$result1 = $this->db->query("select * from tbl_user_type where user_id='$user_id' and status=1")->result();
							foreach($result1 as $row1)
							{
								?>
								<option value="<?= $row1->id; ?>" <?php if($user_type == $row1->id) { ?> selected="selected" <?php } ?>>
								<?= $row1->user_type_title; ?>
								</option>
								<?php
							}
							?>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('parent_category'); ?>
                        </span>
                    </div>
                </div>
				
                <div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Photo
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="form-field-1" placeholder="image" name="image" value="<?= set_value('image'); ?>" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('image'); ?>
                        </span>
                    </div>
              	</div>
          	</div>
			
			<div class="form-group" id="data_5">
				<div class="col-sm-6">
					<div class="col-sm-4 text-right">
						<label class="control-label" for="form-field-1">
							Password
						</label>
					</div>
					<div class="col-sm-8">
						<input type="password" class="form-control" id="form-field-1" placeholder="Password" name="password" value="" />
					</div>
					<div class="help-inline col-sm-12 has-error">
						<span class="help-block reset middle">  
							<?= form_error('password'); ?>
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
                        <select name="status" id="status" data-placeholder="Select Status" class="chosen-select" >
							<option value="1" <?php if($row->status=="1") { ?> selected <?php } ?>>
								Active
							</option>
							<option value="" <?php if($row->status=="") { ?> selected <?php } ?>>
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