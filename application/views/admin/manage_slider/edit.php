<div class="row">
	<div class="col-xs-12">
		<button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
        <?php
        foreach ($result as $row)
        { ?>
            <input type="hidden" name="old_image" value="<?= $row->image; ?>" />
			<div class="form-group">
				<div class="col-sm-6">
					<div class="col-sm-4 text-right">
						<label class="control-label" for="form-field-1">Slider Type</label></div>
						<div class="col-sm-8">
							<select name="slider_type" id="slider_type" class="form-control">
								<option value="1" <?php if($row->slider_type==1) { ?> selected <?php } ?>>Slider 1</option>
								<option value="2" <?php if($row->slider_type==2) { ?> selected <?php } ?>>Slider 2</option>
						</select>
					</div>
					<div class="help-inline col-sm-12 has-error">
						<span class="help-block reset middle"> 
						<?= form_error('slider_type'); ?>
						</span>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="col-sm-4 text-right">
						<label class="control-label" for="form-field-1">
							Short Order
						</label>
					</div>
					<div class="col-sm-8">
						<input type="number" class="form-control" id="short_order" name="short_order" placeholder="Short Order" value="<?= $row->short_order;?>" />
					</div>
					<div class="help-inline col-sm-12 has-error">
						<span class="help-block reset middle">
							<?= form_error('short_order'); ?>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">	
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Function Type
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="funtype" id="funtype" class="form-control" onchange="onchange_funtype()">
							<option value="0" <?php if($row->funtype=="0"){ ?> selected <?php } ?>>
								Not Need
							</option>
							<option value="1" <?php if($row->funtype=="1"){ ?> selected <?php } ?>>
								Select Medicine
							</option>							
							<option value="2" <?php if($row->funtype=="2"){ ?> selected <?php } ?>>
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
				<div class="col-sm-6 div_medicine" <?php if($row->funtype!=1) { ?>style="display:none;" <?php } ?>>
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Select Medicine
                        </label>
                    </div>
                    <div class="col-sm-8">
						<input type="text" id="find_medicine_id" name="find_medicine_id" value="<?= $row->item_code?>" />

						<?php 
						$medicine_name = "";
						$row1 = $this->db->query ("select item_name,i_code from tbl_medicine where i_code='$row->item_code'")->row();
						if(!empty($row1)){
							$medicine_name = $row1->item_name."($row1->i_code)";
						}
						?>

						<input type="text" class="form-control" id="medicine_name" name="medicine_name" tabindex="1" placeholder="Enter Medicine" autocomplete="off" value="<?= $medicine_name?>" />

						<div class="find_medicine_result"></div>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('find_medicine_id'); ?>
                        </span>
                    </div>
                </div>
			</div>
			
			<div class="form-group div_company" <?php if($row->funtype!=2) { ?> style="display:none;" <?php } ?>>
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Select Company
                        </label>
                    </div>
                    <div class="col-sm-8">
						<input type="text" id="find_medicine_company_id" name="find_medicine_company_id" value="<?= $row->comp_code ?>"/>

						<?php 
						$medicine_company_name = "";
						$row1 =  $this->db->query ("select company_full_name from tbl_medicine where compcode='$row->comp_code'")->row();
						if(!empty($row1)){
							$medicine_company_name = $row1->company_full_name;
						}
						?>

						<input type="text" class="form-control" id="medicine_company_name" name="medicine_company_name" tabindex="1" placeholder="Enter Company" autocomplete="off" value="<?= $medicine_company_name?>" />

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
                    <div class="col-sm-8">                        
						<select name="find_medicine_company_division" id="find_medicine_company_division" class="form-control">
							<option value="">
								Select Company Division
							</option>
							<?php
							$result1 =  $this->db->query("select DISTINCT division from tbl_medicine where compcode='$row->comp_code' order by division asc")->result();
							foreach($result1 as $row1)
							{
								$division = $row1->comp_division;
								?>
								<option value="<?= $division ?>" <?php if($division==$row->division) { ?>selected <?php } ?>>
									<?= $division ?>
								</option>
								<?php
							}?>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('division'); ?>
                        </span>
                    </div>
                </div>
			</div>
           
            <div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Image
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="form-field-1" placeholder="Image" name="image" />
                    </div>
                    <div class="col-sm-2" id="imgchange">
                    	<img src="<?= $url_path ?><?= $row->image; ?>" class="img-responsive" />
                    	<?php if($row->image!="default.jpg") { ?>
                    	<Br /><a href="javascript:void(0)" onclick="delete_photo('<?= $row->id; ?>')"><i class="fa fa-remove"></i>Delete</a>
                        <?php } ?>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('Image'); ?>
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
                        <select name="status" id="status" class="form-control">
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