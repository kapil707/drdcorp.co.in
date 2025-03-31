<?php /*<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>            
        </a>
   	</div>
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example">
                <thead>
                    <tr>
                    	<th>
                        	Sno.
                        </th>
						<th>
							Slider Type
						</th>
						<th>
							Function Type
						</th>
						<th>
							Select
						</th>
						<th>
							Image
						</th>					
                        <th>
                        	Update
                        </th>
						<th>
							Action
						</th>
                    </tr>
                </thead>

                <tbody>
                <?php
				/*$i=1;
                foreach ($result as $row)
                {
					?>
                    <tr id="row_<?= $row->id; ?>">
                    	<td>
                        	<?= $row->short_order ?>
                        </td>
						<td>
							Slider <?php echo $row->slider_type ?>
						</td>
						<td>
                        	<?if($row->funtype=="0"){ ?>Not Need<?php } ?>
							<?if($row->funtype=="1"){ ?>Medicine (<?= $row->item_code; ?>)<?php } ?>
							<?if($row->funtype=="2"){ ?>Company (<?= $row->comp_code; ?>)<?php } ?>
                        </td>
						<td>
							<?if($row->funtype=="1"){ ?>
								<?php 
								$row1 =  $this->db->query ("select item_name,i_code from tbl_medicine where i_code='$row->item_code'")->row();?>	
								<?= ($row1->item_name); ?> (<?= ($row1->i_code); ?>)
							<?php } ?>
							<?if($row->funtype=="2"){ ?>
								<?php 
								$row1 = $this->db->query("select company_name from tbl_medicine where compcode='$row->comp_code'")->row();?>
								<?= $row1->company_name?> / <?= $row->comp_division; ?>
							<?php } ?>
                        </td>
						<td>
						    <img src="<?= $url_path ?><?= $row->image; ?>" width="100px" />
						</td>
						<td>
							<?= date('d-M-Y @ H:i:s', $row->timestamp); ?> 
						</td>
                        <td>
                        	<div class="btn-group">
								<a href="edit/<?= $row->id; ?>" class="btn-white btn btn-xs">Edit
								</a>
								<a href="javascript:void(0)" onclick="delete_rec('<?= $row->id; ?>')" class="btn-white btn btn-xs">Delete</i> </a>
							</div>
                        </td> 
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div> */ ?>
<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
		<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a>
   	</div>
	<div class="col-xs-12">
		<div class="row">
			<div class="col-sm-12  pt-1 pb-5">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="example-table">
						<thead>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>