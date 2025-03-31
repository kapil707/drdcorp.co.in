<div class="row">
	<div class="col-xs-12">
		<a href="<?php echo base_url(); ?>admin/<?= $Page_name ?>/view">
		    <button type="button" class="btn btn-w-m btn-info"><< Back</button>
		</a>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Title
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="form-field-1" placeholder="Title" name="title" value="DRD - Notification" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('title'); ?>
                        </span>
                    </div>
                </div>
           		<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Function Type
                        </label>
                    </div>
                    <div class="col-sm-8">
						<select name="funtype" id="funtype" class="form-control" onchange="onchange_funtype()">
							<option value="0">
								Not Need
							</option>
							<option value="1">
								Select Medicine
							</option>							
							<option value="2">
								Select Company
							</option>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('funtype'); ?>
                        </span>
                    </div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<div class="col-sm-4 text-right">
						<label class="control-label" for="form-field-1">
							Message
						</label>
					</div>
					<div class="col-sm-8">
						<textarea type="text" class="form-control" id="form-field-1" placeholder="Message" name="message" value="" required="required"><?= set_value('message'); ?></textarea>
					</div>
					<div class="help-inline col-sm-12 has-error">
						<span class="help-block reset middle">
							<?= form_error('message'); ?>
						</span>
					</div>
				</div>
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Image
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="form-field-1" placeholder="Image" name="image" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('image'); ?>
                        </span>
                    </div>
              	</div>
			</div>
				
			<div class="form-group">				
				<div class="col-sm-6">
					<div class="col-sm-4 text-right">
						<label class="control-label" for="form-field-1">
							Enter Name / Altercode
						</label>
					</div>
					<div class="col-sm-8">
						<input type="hidden" id="find_chemist_id" name="find_chemist_id"/>

						<input type="text" class="form-control" id="chemist_name" name="chemist_name" tabindex="1" placeholder="Enter Name / Altercode" autocomplete="off" required />

						<div class="find_chemist_result"></div>
					</div>
					<div class="help-inline col-sm-12 has-error">
						<span class="help-block reset middle">
							<?= form_error('altercode'); ?>
						</span>
					</div>
				</div>
				
				<div class="col-sm-6 div_medicine" style="display:none">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Select Medicine
                        </label>
                    </div>
                    <div class="col-sm-8">
						<input type="text" id="find_medicine_id" name="find_medicine_id"/>

						<input type="text" class="form-control" id="medicine_name" name="medicine_name" tabindex="1" placeholder="Enter Medicine" autocomplete="off" />

						<div class="find_medicine_result"></div>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('find_medicine_id'); ?>
                        </span>
                    </div>
                </div>
			</div>			
			
			<div class="form-group div_company" style="display:none">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Select Company
                        </label>
                    </div>
                    <div class="col-sm-8">
						<input type="text" id="find_medicine_company_id" name="find_medicine_company_id"/>

						<input type="text" class="form-control" id="medicine_company_name" name="medicine_company_name" tabindex="1" placeholder="Enter Company" autocomplete="off" />

						<div class="find_medicine_company_result"></div>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('find_medicine_company_id'); ?>
                        </span>
                    </div>
                </div>
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Select Company Division
                        </label>
                    </div>
                    <div class="col-sm-8 division_div">                        
						<select name="find_medicine_company_division" id="find_medicine_company_division" class="form-control">
							<option value="">
								Select Company Division
							</option>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('division'); ?>
                        </span>
                    </div>
                </div>
			</div>
            

            <div class="space-4"></div>
            <br /><br />
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-info submit_button" name="Submit">
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
        </form>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
<script>
function onchange_funtype()
{	
	/*********************************************** */
	$('#medicine_name').removeAttr('required');
	$("#find_medicine_id").val('');	
	$(".div_medicine").hide();
	/*********************************************** */

	/*********************************************** */
	$('#medicine_company_name').removeAttr('required');
	$("#find_medicine_company_id").val('');
	$(".div_company").hide();

	$('#find_medicine_company_division').removeAttr('required');
	/*********************************************** */
	
	let selectedValue = $("#funtype").val();
	if(selectedValue==1){
		$(".div_medicine").show();
		$('#medicine_name').attr('required', true);
	}
	if(selectedValue==2){
		$(".div_company").show();
		$('#medicine_company_name').attr('required', true);
		$('#find_medicine_company_division').attr('required', true);
	}
}
</script>