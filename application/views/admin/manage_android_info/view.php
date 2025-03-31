<div class="row">
    <!-- <div class="col-xs-12" style="margin-bottom:5px;">
		<a href="<?php echo base_url(); ?>admin/manage_chemist_map/view">
            <button type="submit" class="btn btn-info">
            	View all Users
            </button>
        </a>
   	</div> -->
	<div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover dataTables-example">
                <thead>
                    <tr>
                    	<th>
                        	Sno.
                        </th>
						<th>
							User
						</th>
						<th>
							Date / Time
						</th>
						<th>
							Version
						</th>
						<th>
							Logout
						</th>
						<th>
							Clear Data
						</th>
						<th>
							View On Map
						</th>
                    </tr>
                </thead>
				<tbody>
                <?php
				$i=1;
                foreach ($result as $row)
                {
					?>
                    <tr id="row_<?= $row->id; ?>">
                    	<td>
                        	<?= $i++; ?>
                        </td>
						<td>
							<?php if($row->user_type=="chemist") { ?>
							<?php
							$row1 = $this->db->query("select name from tbl_chemist where altercode='$row->chemist_id'")->row();
							?>
							<?= $row1->name; ?> (<?= $row->chemist_id; ?>) - chemist
							<?php } ?>
							
							<?php if($row->user_type=="sales") { ?>
							<?php
							$row1 = $this->db->query("select * from tbl_users where customer_code='$row->chemist_id'")->row();
							?>
							<?= $row1->firstname; ?> <?= $row1->lastname; ?> (<?= $row->chemist_id; ?>) - SalesMan
							<?php } ?>
						</td>
						<td>
							<?= date("d-M-y h:i a ",$row->time); ?>
                        </td>
						<td>
							<?= $row->versioncode; ?>
                        </td>
						<td>
							<?php if($row->logout==0) { ?>
							<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Do you really want to Logout?');">
							<input type="hidden" name="id1" value="<?=$row->id; ?>"/>
							<button type="submit" class="btn btn-info submit_button" name="Submit1">
							<i class="ace-icon fa fa-check bigger-110"></i>
							Logout
							</button>
							</form>
							<?php } else { ?>
							Logout Ready To Process
							<?php } ?>
                        </td>
						<td>
							<?php if($row->clear_database==0) { ?>
							<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Do you really want to submit the Clear Database?');">
							<input type="hidden" name="id2" value="<?=$row->id; ?>"/>
							<button type="submit" class="btn btn-info submit_button" name="Submit2">
							<i class="ace-icon fa fa-check bigger-110"></i>
							Clear Database
							</button>
							</form>
							<?php } else { ?>
							Clear Database Ready To Process
							<?php } ?>
                        </td>
						<td>
							<a href="view2/<?= $row->chemist_id; ?>"  class="btn btn-info submit_button">
							<i class="ace-icon fa fa-check bigger-110"></i>
							View On Map
							</a>
						</td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>