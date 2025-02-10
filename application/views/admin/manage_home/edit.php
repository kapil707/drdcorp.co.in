<div class="row">
	<div class="col-xs-12">
	    <a href="<?php echo base_url(); ?>admin/<?= $Page_name ?>/view">
		<button type="button" class="btn btn-w-m btn-info"><< Back</button>
		</a>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
        <?php
        foreach ($result as $row)
        { ?>
            <div class="form-group">	
                <div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Seq Id
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="name" name="seq_id" placeholder="Seq Id" value="<?php echo $row->seq_id;?>">
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('seq_id'); ?>
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Category
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="category_id" id="category_id" data-placeholder="Select Status" class="form-control" required>
							<option value="">
                                Select Category
							</option>
                            <option value="menu" <?php if($row->type=="menu") { ?> selected <?php } ?>>
                                Menu
							</option>
                            <option value="slider_1" <?php if($row->type=="slider" && $row->category_id=="1") { ?> selected <?php } ?>>
                                Slider 1
							</option>
                            <option value="slider_2" <?php if($row->type=="slider" && $row->category_id=="2") { ?> selected <?php } ?>>
                                Slider 2
							</option>
                            <?php 
                            $result1 =  $this->db->query ("select * from tbl_item_category where status=1")->result(); 
                            foreach($result1 as $row1) {?>
                            <option value="itemcategory_<?php echo $row1->id;?>" <?php if($row->type=="itemcategory" && $row->category_id==$row1->id) { ?> selected <?php } ?>>
                                <?php echo $row1->name;?> (Item Category)
							</option>
                            <?php } ?>
                            <?php 
                            $result1 =  $this->db->query ("select * from tbl_division_category where status=1")->result(); 
                            foreach($result1 as $row1) {?>
                            <option value="divisioncategory_<?php echo $row1->id;?>" <?php if($row->type=="divisioncategory" && $row->category_id==$row1->id) { ?> selected <?php } ?>>
                                <?php echo $row1->name;?> (Division Category)
							</option>
                            <?php } ?>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('category_id'); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Status
                        </label>
                    </div>
                    <div class="col-sm-8">
                     <select name="status" id="status" data-placeholder="Select Status" class="form-control">
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