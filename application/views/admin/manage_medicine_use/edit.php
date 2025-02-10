<div class="row">
	<div class="col-xs-12">
	    <a href="<?php echo base_url(); ?>admin/<?= $Page_name ?>/view">
			<button type="button" class="btn btn-w-m btn-info"><< Back</button>
		</a>
	</div>
	<div class="col-xs-12">
		<h2>
			<?php echo $row->item_name; ?> (<?php echo $row->item_code; ?>)
		</h2>
	</div>
    <div class="col-xs-12">
		<?php
		$item_code = $row->i_code;
		$items = "";
		$php_files = glob('./medicine_use/'.$item_code.'/*');
		foreach($php_files as $file) {
			$file = str_replace("./","",$file);
			//$file = base_url().$file;
			
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if($ext=="jpg"){
				$file_type = "image";
				?>
				<div class="col-sm-2 medicine_use_div">
					<img src="<?php echo base_url().$file ?>" width="100%">
				</div>
				<?php
			}
			if($ext=="mp4"){
				$file_type = "video";
				?>
				<div class="col-sm-4 medicine_use_div1">
					<video width="100%" height="250" controls="" poster="">
						<source src="<?php echo base_url().$file ?>" type="video/mp4">
						Your browser does not support the video tag.
					</video>
				</div>
				<?php
			}
		}
		?>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div>