<div class="row">
	<div class="col-xs-12">
		<button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
			
			<div class="form-group">
				<div class="col-sm-12">
                    <div class="col-sm-2 text-right">
                        <label class="control-label" for="form-field-1">
                            Author
                        </label>
                    </div>
                    <div class="col-sm-10">
						<textarea class="form-control" id="author" name="author" tabindex="1" placeholder="Author" autocomplete="off"><?php echo set_value('author') ?></textarea>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('author'); ?>
                        </span>
                    </div>
                </div>
			</div>
			<div class="form-group">	
				<div class="col-sm-12">
                    <div class="col-sm-2 text-right">
                        <label class="control-label" for="form-field-1">
                            Description
                        </label>
                    </div>
                    <div class="col-sm-10">
						<textarea class="form-control" id="description" name="description" tabindex="1" placeholder="Description" autocomplete="off"><?php echo set_value('description') ?></textarea>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('description'); ?>
                        </span>
                    </div>
                </div>
			</div>
			
			<div class="form-group">	
				<div class="col-sm-12">
                    <div class="col-sm-2 text-right">
                        <label class="control-label" for="form-field-1">
                            Keywords
                        </label>
                    </div>
                    <div class="col-sm-10">
						<textarea class="form-control" id="keywords" name="keywords" tabindex="1" placeholder="Keywords" autocomplete="off"><?php echo set_value('keywords') ?></textarea>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('keywords'); ?>
                        </span>
                    </div>
                </div>
			</div>
			
			<div class="form-group">	
				<div class="col-sm-12">
                    <div class="col-sm-2 text-right">
                        <label class="control-label" for="form-field-1">
                            Url
                        </label>
                    </div>
                    <div class="col-sm-10">
						<input type="text" class="form-control" id="url" name="url" tabindex="1" placeholder="Url" autocomplete="off" value="<?php echo set_value('url') ?>" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('url'); ?>
                        </span>
                    </div>
                </div>
			</div>
			
			<div class="form-group">	
				<div class="col-sm-12">
                    <div class="col-sm-2 text-right">
                        <label class="control-label" for="form-field-1">
                            Seo Google
                        </label>
                    </div>
                    <div class="col-sm-10">
						<textarea class="form-control" id="seo_google" name="seo_google" tabindex="1" placeholder="Seo Google" autocomplete="off"><?php echo set_value('seo_google') ?></textarea>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('seo_google'); ?>
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
                        <select name="status" id="status" class="form-control">
							<option value="1" <?php if(set_value('status')==1) { ?> selected <?php } ?>>
								Active
							</option>
							<option value="0" <?php if(set_value('status')==0) { ?> selected <?php } ?>>
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
        </form>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
<script>
function call_search_item()
{	
	item_name = $("#item_name").val();
	$(".call_search_item_result").html("Loading....");
	if(item_name=="")
	{
		$(".call_search_item_result").html("");
	}
	else
	{
		$.ajax({
		type       : "POST",
		data       :  {item_name:item_name},
		url        : "<?= base_url()?>admin/<?= $Page_name?>/call_search_item",
		cache	   : false,
		success    : function(data){
			$(".call_search_item_result").html(data);
			}
		});
	}
}
function additem(i_code,name)
{
	name = atob(name);
	$("#i_code").val(i_code);
	$("#item_name").val(name);
	$(".call_search_item_result").html("");
}

function call_search_company()
{	
	company_name = $("#company_name").val();
	$(".call_search_company_result").html("Loading....");
	if(company_name=="")
	{
		$(".call_search_company_result").html("");
	}
	else
	{
		$.ajax({
		type       : "POST",
		data       :  {company_name:company_name},
		url        : "<?= base_url()?>admin/<?= $Page_name?>/call_search_company",
		cache	   : false,
		success    : function(data){
			$(".call_search_company_result").html(data);
			}
		});
	}
}
function addcompany(id,name)
{
	name = atob(name);
	$("#compid").val(id);
	$("#company_name").val(name);
	$(".call_search_company_result").html("");
	get_company_division();
}
function get_company_division()
{	
	compid = $("#compid").val();
	$(".division_div").html("Loading....");
	$.ajax({
	type       : "POST",
	data       :  {compid:compid},
	url        : "<?= base_url()?>admin/<?= $Page_name?>/get_company_division",
	cache	   : false,
	success    : function(data){
		$(".division_div").html(data);
		}
	});
}
</script>