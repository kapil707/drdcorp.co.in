foreach ($processed_data as $entry) {	
					$tr_style = "";
					$chemist_fafa[] = "";
					$search = $row->process_name;
					
					$search_escaped = preg_quote($search, '/');
					$highlighted_text = preg_replace('/(' . $search_escaped . ')/i', '<span style="background-color: yellow;">$1</span>', $row->process_value);
					
					$find = "find by ";
					if(!empty($row->find_invoice_chemist_id)){
						$find.= "<b>invoice</b>,";
					}
					if(!empty($row->find_chemist_id)){
						$find.= "<b>chemist</b>";
					}
					if(empty($find_invoice_chemist_id) && empty($row->find_chemist_id)){
						$find = "N/A";
					}
					
					$find_chemist_id2 = "";
					$find_chemist_id_array = explode(",", $row->find_chemist_id);
					$find_chemist_id_array = array_unique($find_chemist_id_array);					
					if(count($find_chemist_id_array)==1){
						$find_chemist_id2 = $find_chemist_id_array[0];
					}

					$find_invoice_chemist_id1 = "";
					$find_invoice_chemist_id2 = "";
					$find_invoice_chemist_id_array = explode(",", $row->find_invoice_chemist_id);
					foreach($find_invoice_chemist_id_array as $rows){
						$find_invoice_chemist_id1.= $rows."<br>";

						$arr = explode(":-",$rows);
						$find_invoice_chemist_id2 = $arr[0];
					}
					
					$done_chemist_id = "";
					$find_all = "";
					
					if((strtolower($find_chemist_id2)==strtolower($find_invoice_chemist_id2)) && (!empty($find_invoice_chemist_id2) && !empty($find_chemist_id2))){
						$find_all = "done";
						$tr_style = "background-color: #ffe1c0;";

						$done_chemist_id = $find_chemist_id2;
					}

					if(empty($find_all) && !empty($find_invoice_chemist_id_array) &&  !empty($find_chemist_id_array)){
						foreach($find_invoice_chemist_id_array as $rows){
							foreach($find_chemist_id_array as $rows1){
								$arr = explode(":-",$rows);
								if($arr[0]==$rows1 && !empty($arr[0]) && !empty($rows1)){
									$find_all = "new-done";
									$done_chemist_id = $rows1;

									$chemist_fafa[$done_chemist_id] = '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;font-size: 20px;"></i>';

									$tr_style = "background-color: #D9C0FF;";
								}
							}
						}
					}

					if($row->done_status==1){
						$tr_style = "background-color: #e8ffe2;";
						$done_chemist_id = $row->done_chemist_id;
					}
					?>
					<tr class="tr_css_<?php echo $row->id; ?>" style="<?php echo $tr_style ?>">
						<td><?php echo $row->id; ?> </td>
						<td>
							Status : <?= ($row->status); ?>
							<br><br>
							Date : <?= ($row->time); ?>
							<br><br>
							Upi No : <?= ($row->upi_no); ?>
							<br><br>
								
							<div style="word-wrap:break-word;width:200px;">
								Orderid : <?= ($row->orderid); ?>
							</div>

							<br><br>
							<b>Amount : <?= ($row->amount); ?></b>
						</td>
						<td>
							<?php print_r($main_sms); ?>
							<?php print_r($main_statment); ?>
							<?php /* foreach($newrow as $banktype){
								echo $banktype; 
							} */?>
							
							: <?= ($row->received_from); ?>

							<input type="hidden" value="<?php echo $row->received_from ?>" class="text_received_from_<?php echo $row->id; ?>">

							<input type="text" value="<?php echo $row->find_chemist_id; ?>" class="text_received_from_chemist_id_<?php echo $row->id; ?>" style="display:none">

							<i class="fa fa-check add_received_from_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="add_received_from_chemist_id('<?php echo $row->id; ?>')" style="display:none"></i>

							<i class="fa fa-times cancel_received_from_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="cancel_received_from_chemist_id('<?php echo $row->id; ?>')" style="display:none"></i>
							
							<i class="fa fa-pencil edit_received_from_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="edit_received_from_chemist_id('<?php echo $row->id; ?>')"></i>
							<br><br>
							<div style="word-wrap:break-word;width:250px;">
								Find : <?= ($highlighted_text); ?> || <b>(<?= ($row->find_by); ?>)</b>
							</div>
							<br><br>
							<b>Chemist : </b>
							<?php 
							if(!empty($find_chemist_id_array)){
								foreach($find_chemist_id_array as $rows){
									echo $rows;
									if(!empty($chemist_fafa[$rows])){
										echo $chemist_fafa[$rows];
									}
									echo "<br>";
								}
							}
							?>
							<br>
							<b>Invoice : </b>
							<br><br>
							<b>WhatsApp : </b>
							<?php echo $row->whatsapp_body; ?>
						</td>
						<td><?= ($find_invoice_chemist_id1); ?></td>
						<td><?= ($row->whatsapp_body2); ?></td>
						<td class="display: flex;">
							<b><?= ($find); ?></b>
							<br>
							<input type="text" value="<?php echo $done_chemist_id ?>" class="text_done_chemist_id_<?php echo $row->id; ?>" style="<?php if($row->done_status==1) { ?>display:none;<?php } ?> float: left; width: 100px;">
							
							<i class="fa fa-check add_done_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="add_done_chemist_id('<?php echo $row->id; ?>')" style="<?php if($row->done_status==1) { ?>display:none;<?php } ?> float: left;font-size: 20px;"></i>

							<span class="span_done_chemist_id_<?php echo $row->id; ?>" <?php if($row->done_status==0 || $row->done_status==2) { ?>style="display:none" <?php } ?>><?php echo $done_chemist_id ?></span>

							<i class="fa fa-pencil edit_done_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="edit_done_chemist_id('<?php echo $row->id; ?>')" <?php if($row->done_status==0 || $row->done_status==2) { ?>style="display:none" <?php } ?>></i>
						</td>
					</tr>
					<?php } ?>









					new *********

					if(($whatsapp_body_1=="N/a" || empty($whatsapp_body_1)) && $row_find_by_chemist_id=="N/a" && empty($row_find_by_invoice_chemist_id)){
						$tr_style = "background-color: darksalmon";
					}

					if((strtolower($find_chemist_id2)==strtolower($row_find_by_invoice_chemist_id)) && (!empty($row_find_by_invoice_chemist_id) && !empty($find_chemist_id2))){
						$find_all = "done";
						$tr_style = "background-color: darkseagreen;";

						$done_chemist_id = $find_chemist_id2;
					}

					if((strtolower($find_chemist_id2)==strtolower($whatsapp_body_1)) && (!empty($whatsapp_body_1) && !empty($find_chemist_id2))){
						$find_all = "done";
						$tr_style = "background-color: lightseagreen;";

						$done_chemist_id = $find_chemist_id2;
					}

					if((strtolower($find_chemist_id2)==strtolower($row_find_by_invoice_chemist_id)) && (strtolower($find_chemist_id2)==strtolower($whatsapp_body_1)) && (!empty($row_find_by_invoice_chemist_id) && !empty($find_chemist_id2) && !empty($whatsapp_body_1))){
						$find_all = "done-all";
						$tr_style = "background-color: darkkhaki;";

						$done_chemist_id = $find_chemist_id2;
					}

					if(empty($find_all) && !empty($find_invoice_chemist_id_array) &&  !empty($find_chemist_id_array)){
						foreach($find_invoice_chemist_id_array as $rows){
							foreach($find_chemist_id_array as $rows1){
								$arr = explode(":-",$rows);
								if($arr[0]==$rows1 && !empty($arr[0]) && !empty($rows1)){
									$find_all = "new-done";
									$done_chemist_id = $rows1;

									$chemist_fafa[$done_chemist_id] = '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;font-size: 20px;"></i>';

									$tr_style = "background-color: #D9C0FF;";
								}
							}
						}
					}