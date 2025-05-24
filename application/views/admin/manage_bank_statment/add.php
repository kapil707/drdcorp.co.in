<div class="row">
	<div class="col-xs-12">
		<button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
			<div class="form-group">			
           		<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            File
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="form-field-1" placeholder="Name" name="myfile" value="<?= set_value('myfile'); ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('name'); ?>
                        </span>
                    </div>
                </div>
          	</div>
			
			<div class="form-group">			
           		<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Select Formet
                        </label>
                    </div>
                    <div class="col-sm-8">
                       <select name="formet" id="formet" class="form-control">
							<option value="1">
								Formet 1
							</option>
							<option value="2">
								Formet 2
							</option>
							<option value="3">
								Formet 3 (d/m/Y) <?php echo date('d/m/Y'); ?>
							</option>
							<option value="4">
								Formet 4 (d m Y) <?php echo date('d m Y'); ?>
							</option>
							<option value="5">
								Formet 5 (d F Y) <?php echo date('d F Y'); ?>
							</option>
							<option value="6">
								Formet 6 (d-F-Y) <?php echo date('d-F-Y'); ?>
							</option>
						</Select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('name'); ?>
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
		<?php

		/*$chemist = "B";
		$itemname = "C";
		$filename1 = "kapilji.xlsx";
		$upload_path = "./uploads/";
		$excelFile = $upload_path.$filename1;
		$rows = array();
		if(file_exists($excelFile))
		{
			$this->load->library('excel');
			$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				for ($row=2; $row<=$highestRow; $row++)
				{
					$itemname1 = $worksheet->getCell($itemname.$row)->getValue();
					$chemist1 = $worksheet->getCell($chemist.$row)->getValue();
					//$rows[$chemist1] = $itemname1;

					$dt = array(
						'string_value'=>$itemname1,
						'chemist_id'=>$chemist1,
					);
					//$this->Scheme_Model->insert_fun("tbl_upload_sms",$dt);
					$this->BankModel->insert_fun("tbl_chemist", $dt);
				}
			}
		}*/
		//print_r($rows);

		/*foreach($rows as $mydt){
			echo $mydt;
			echo "<br>";
		}*/

		
		?>
    </div><!-- /.col -->
</div><!-- /.row -->
<script>
function url_change()
{
	name = $(".name1").val();
	name = name.replace(/&/g,'and');
	name = name.trim(name).replace(/ /g,'-');
	name = encodeURI(name).replace(/[!\/\\#,+()$~%.'":*?<>{}]/g,'');
	$(".url1").html(name)
	$(".url").val(name)
	a_href_change(name)
}
function a_href_change(name)
{
	document.getElementById("url1").href= "<?= base_url(); ?>products/"+name+".html"; 
}
var error2 = 1;
function change_url()
{
	error2 = 0;
	disabled_submit_button();
	$('.url_error').html("");
	url1 = $('.url').val();
	name = url1;
	name = name.replace(/&/g,'and');
	name = name.trim(name).replace(/ /g,'-');
	name = encodeURI(name).replace(/[!\/\\#,+()$~%.'":*?<>{}]/g,'');
	$(".url1").html(name)
	$(".url").val(name)
	a_href_change(name)
	$.ajax({
	type       : "POST",
	data       :  { url1 : url1,id : '<?= $row->id; ?>',} ,
	url        : "<?= base_url()?>admin/<?= $Page_name?>/change_url",
	success    : function(data){
			if(data!="")
			{
				//java_alert_function("success","Delete Successfully");
				//$('.product_code_error').html("This Product SKU Already Taken");
				if(data=="Error")
				{
					java_alert_function("error","This Product Url Already Taken")
					$('.url_error').html("This Product Url Already Taken");
				}
				if(data=="ok")
				{
					java_alert_function("success","Product Url Ok");
					$('.url_error').html("Product Url Ok");
					error2 = 1;
					disabled_submit_button();
				}
			}					
			else
			{
				java_alert_function("error","Something Wrong")
				$('.url_error').html("Something Wrong");
			}
		}
	});
}
function disabled_submit_button()
{
	if(error2==1)
	{
		$(".submit_button").prop('disabled', false);
	}
	else
	{
		$(".submit_button").prop("disabled", true);
	}
}
</script>