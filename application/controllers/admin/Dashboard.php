<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	var $Page_title = "Dashboard";
	var $Page_name  = "dashboard";
	var $Page_view  = "dashboard";
	var $Page_menu  = "dashboard";
	var $Page_tbl   = "";
	var $page_controllers = "dashboard";
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->model("model-drdweb/InvoiceModel");

		$user_type = $this->session->userdata("user_type");
		if(empty($user_type)){
			redirect(base_url()."admin");
		}
    }
	public function index()
	{
		/******************session***********************/
		$user_id = $this->session->userdata("user_id");
		$user_type = $this->session->userdata("user_type");
		/******************session***********************/	

		$Page_title = $this->Page_title;
		$Page_name 	= $this->Page_name;
		$Page_view 	= $this->Page_view;
		$Page_menu 	= $this->Page_menu;
		$Page_tbl 	= $this->Page_tbl;
		$page_controllers 	= $this->page_controllers;	

		$data['title1'] = $Page_title." || Dashboard";
		$data['title2'] = "Dashboard";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;
		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		//$this->breadcrumbs->push("Add","admin/$page_controllers/add");	

		$tbl = $Page_tbl;

		$this->load->view('admin/header_footer/header_dashbord',$data);
		if($user_type=="Super_Admin" || $user_type=="Admin"){
			$this->load->view("admin/$Page_view/index",$data);
		}
		$this->load->view('admin/header_footer/footer_dashbord',$data);		
		$this->load->view("admin/$Page_view/".$Page_view."_footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}	

	public function edit_profile()
	{
		/******************session***********************/
		$user_id = $this->session->userdata("user_id");
		/******************session***********************/		

		$Page_title = $this->Page_title;
		$Page_name 	= $this->Page_name;
		$Page_view 	= $this->Page_view;
		$Page_menu 	= $this->Page_menu;
		$Page_tbl 	= $this->Page_tbl;
		$page_controllers 	= $this->page_controllers;		

		$data['title1'] = $Page_title." || Edit Profile";
		$data['title2'] = "Edit Profile";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;
		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Edit Profile","admin/$page_controllers/add");	

		$tbl = $Page_tbl;
		$data['url_path'] = base_url()."uploads/manage_users/photo/";
		$upload_path = "./uploads/manage_users/photo/";
		$upload_thumbs_path = "./uploads/manage_users/photo/thumbs/";		

		extract($_POST);
		if(isset($Submit))
		{
			$result = "";
			$this->form_validation->set_rules('name','Name',"required");
			if($password!="")
			{
				$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|alpha_numeric',
				array(
				"min_length"=>"<p class='font-bold  alert alert-danger m-b-sm'>Min 6 charter length.</p>"
				));
				$this->form_validation->set_rules('password1','Password Confirmation','trim|required|matches[password]');				

				$password = md5($password);
				$password = sha1($password);
				$password = md5($password);
			}
			else
			{
				$password = $old_password;
			}			

			if ($this->form_validation->run() == FALSE)
			{
				$message = "Check Validation.";
				$this->session->set_flashdata("message_type","warning");
			}
			else
			{
				$image = "";
				if (!empty($_FILES["photo"]["name"]))
				{
					$name1 = "photo";
					$x = $_FILES["photo"]['name'];
					$y = $_FILES["photo"]['tmp_name'];
					$image = $this->Scheme_Model->photo_up($name1,$x,$y,$upload_path,$upload_thumbs_path,"60");
				}
				else
				{
					$image = $old_photo;
				}			

				$where = array('id'=>$user_id);
				$dt = array('name'=>$name,'image'=>$image,'password'=>$password,);
				$result = $this->Scheme_Model->edit_fun("tbl_user",$dt,$where);
				if($result)
				{
					$this->Admin_Model->admin_login($old_email,$password);
					$message = "Edit Successfully.";
					$this->session->set_flashdata("message_type","success");
				}
				else
				{
					$message = "Not Add.";
					$this->session->set_flashdata("message_type","error");
				}
			}
			$message = "Edit Profile"." - ".$message;
			$this->session->set_flashdata("message_footer","yes");
			$this->session->set_flashdata("full_message",$message);
			$this->Admin_Model->Add_Activity_log($message);
			if($result)
			{
				redirect(base_url()."admin/$page_controllers/edit_profile");
			}
		}		

		$query = $this->db->query("select * from tbl_user where id='$user_id'");
  		$data["result"] = $query->result();						

		$this->load->view('admin/header_footer/header',$data);
		$this->load->view("admin/$Page_view/edit_profile",$data);
		$this->load->view('admin/header_footer/footer',$data);
	}	

	public function delete_photo()
	{
		$id = $this->session->userdata("user_id");
		$result = $this->db->query("update tbl_user set photo='default.jpg' where id='$id'");
		if($result)
		{
			$message = "Update Successfully.";
		}
		else
		{
			$message = "Not Update.";
		}
		$message = $Page_title." - ".$message;
		$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}

	public function check_login()
	{
		$user_id = $this->session->userdata('user_id');
		$user_password = $this->session->userdata('user_password');
		if(!empty($user_id) && $user_id !='')
		{
			$query = $this->db->query("select * from tbl_user where id='$user_id' and password='$user_password'");
  			$row = $query->row();
			if($row)
			{
				if($row->updateme==1)
				{
					$id = $row->id;
					$this->db->query("update tbl_user set updateme='0' where id='$user_id' and password='$user_password'");				

					$user_password = $row->password;
					$user_type = $row->user_type;				

					$session_arr = array('user_id'=>$row->id,'name'=>$row->name,'user_email'=>$row->email,'username'=>$row->username,'user_type'=>$user_type,'user_password'=>$user_password);
					$this->session->set_userdata($session_arr);
					echo "update";
				}
				else
				{
					$make_interest_count = $contact_form_count = $feedback_count = 0;
					$query = $this->db->query("select * from tbl_make_interest where status='0'");
					$result = $query->result();
					foreach($result as $row)
					{
						$make_interest_count++;
					}
					$query = $this->db->query("select * from tbl_feedback where status='0'");
					$result = $query->result();
					foreach($result as $row)
					{
						$feedback_count++;
					}
					$query = $this->db->query("select * from tbl_contact_form where status='0'");
					$result = $query->result();
					foreach($result as $row)
					{
						$contact_form_count++;
					}
					if($make_interest_count==0)
					{
						$make_interest_count="";
					}
					if($feedback_count==0)
					{
						$feedback_count="";
					}
					if($contact_form_count==0)
					{
						$contact_form_count="";
					}
					?>
                    <script>
					$("#make_interest_count").html("<?= $make_interest_count ?>");
					$("#feedback_count").html("<?= $feedback_count ?>");
					$("#contact_form_count").html("<?= $contact_form_count ?>");
					</script>
                    <?php
				}
			}
			else
			{
				echo "notok";
				$this->session->sess_destroy();
			}
		}
		else
		{
			echo "notok";
			$this->session->sess_destroy();
		}
		//$this->Auto_Time_Table_Model->Process();
	}

	public function notify()
	{
		$pgtype = $_POST["pgtype"];
		if($pgtype!="")
		{
			if($pgtype=="make_interest")
			{
				$query = $this->db->query("select * from tbl_make_interest where status='0' order by id desc limit 4");
				$result = $query->result();
				foreach($result as $row)
				{
					?>
				<li>
					<div class="dropdown-messages-box">
						<a href="<?= base_url(); ?>admin/manage_make_interest/edit/<?php echo $row->id; ?>">
							<div class="media-body">
                            	<?php
								$type = $row->type;
								$property_id = $row->property_id;
								if($type=="property")
								{
									$query = $this->db->query("select * from tbl_property where id='$property_id'");
								}
								if($type=="vehicles")
								{
									$query = $this->db->query("select * from tbl_vehicles where id='$property_id'");
								}
								$row1 = $query->row();
								?>
								<strong><?php echo base64_decode($row->name); ?>
                                </strong> 
                                interested on your 
                                <?php if($type=="property") { ?>
								<?= base64_decode($row1->property_title); ?> property.
                                <?php } ?>                                

                                <?php if($type=="vehicles") { ?>
								<?= base64_decode($row1->title); ?> vehicles.
                                <?php } ?>
                                <br>
								<small class="text-muted">
								<?php
								$display_time_H = date("H",$row->time);
								$display_time_i = date("i",$row->time);
								echo $time= date("d-M-Y",$row->time)." at ".$this->Scheme_Model->time_conveter($display_time_H,$display_time_i);
								?>
								</small>
							</div>
						</a>
					</div>
				</li>			

				<li class="divider"></li>
				<?php } ?>
				<li>
					<div class="text-center link-block">
						<a href="<?= base_url(); ?>admin/manage_make_interest">
							<i class="fa fa-envelope"></i> <strong>
                            See All People Interest
                            </strong>
						</a>
					</div>
				</li>
				<?php
			}			

			if($pgtype=="feedback")
			{
				$query = $this->db->query("select * from tbl_feedback where status='0' order by id desc limit 4");
				$result = $query->result();
				foreach($result as $row)
				{
					?>
				<li>
					<div class="dropdown-messages-box">
						<a href="<?= base_url(); ?>admin/manage_feedback/edit/<?= $row->id ?>">
							<div class="media-body">
								<strong><?php echo base64_decode($row->name); ?></strong> send feadback. <br>
								<small class="text-muted">
								<?php
								$display_time_H = date("H",$row->time);
								$display_time_i = date("i",$row->time);
								echo $time= date("d-M-Y",$row->time)." at ".$this->Scheme_Model->time_conveter($display_time_H,$display_time_i);
								?>
								</small>
							</div>
						</a>
					</div>
				</li>			

				<li class="divider"></li>
				<?php } ?>
				<li>
					<div class="text-center link-block">
						<a href="<?= base_url(); ?>admin/manage_feedback">
							<i class="fa fa-envelope"></i> <strong>
                            	See All Submited Feedbacks
                            </strong>
						</a>
					</div>
				</li>
				<?php
			}		

			if($pgtype=="contact_form")
			{
				$query = $this->db->query("select * from tbl_contact_form where status='0' order by id desc limit 4");
				$result = $query->result();
				foreach($result as $row)
				{
					?>
				<li>

					<div class="dropdown-messages-box">
						<a href="<?= base_url(); ?>admin/manage_contact_form/edit/<?= $row->id ?>">
							<div class="media-body">
								<strong><?php echo base64_decode($row->email); ?></strong> send contact from. <br>
								<small class="text-muted">
								<?php
								$display_time_H = date("H",$row->time);
								$display_time_i = date("i",$row->time);
								echo $time= date("d-M-Y",$row->time)." at ".$this->Scheme_Model->time_conveter($display_time_H,$display_time_i);
								?>
								</small>
							</div>
						</a>
					</div>
				</li>				

				<li class="divider"></li>
				<?php } ?>
				<li>
					<div class="text-center link-block">
						<a href="<?= base_url(); ?>admin/manage_contact_form">
							<i class="fa fa-envelope"></i> <strong>
                            	See All Submited Contacts
                            </strong>
						</a>
					</div>
				</li>
				<?php
			}		

			$this->db->query("update tbl_$pgtype set status='1' where status='0'");
		}
	}

	public function set_theme()
	{
		$id = $_POST["selected_theme"];
		$this->db->query("update tbl_theme set selected_theme='0' where selected_theme='1'");
		$this->db->query("update tbl_theme set selected_theme='1' where id='$id'");
		echo "ok";
	}

	public function set_website_type()
	{
		$id = $_POST["website_type"];
		$this->db->query("update tbl_website_type set selected_theme='0' where selected_theme='1'");
		$this->db->query("update tbl_website_type set selected_theme='1' where id='$id'");
		echo "ok";
	}

	public function set_website_type1()
	{
		$id = $_POST["website_type"];
		$this->db->query("update tbl_website_type1 set selected_theme='0' where selected_theme='1'");
		$this->db->query("update tbl_website_type1 set selected_theme='1' where id='$id'");
		echo "ok";
	}

	public function view_active_user_api() {
		
		$i = 1;
		$Page_tbl = $this->Page_tbl;
		$jsonArray = array();

		$items = "";

		$result = $this->db->query("SELECT chemist_id, salesman_id, date, MAX(time) AS time FROM tbl_activity_logs WHERE timestamp >= (UNIX_TIMESTAMP() - 300) GROUP BY chemist_id, salesman_id, date ORDER BY MAX(timestamp) DESC LIMIT 0, 100");
		$result = $result->result();

		foreach($result as $row){

			$sr_no = $i++;
			$chemist_id = $row->chemist_id;
			$salesman_id = $row->salesman_id;
			if(empty($chemist_id)){
				$chemist_id = "Guest User";
			}
			if(empty($salesman_id)){
				$salesman_id = "N/a";
			}
			$datetime = date("d-M-y",strtotime($row->date)) . " @ " .$row->time;

			$dt = array(
				'sr_no' => $sr_no,
				'chemist_id' => $chemist_id,
				'salesman_id'=>$salesman_id,
				'datetime'=>$datetime,
			);
			$jsonArray[] = $dt;
		}

		$items = $jsonArray;
		$response = array(
			'success' => "1",
			'message' => 'Data load successfully',
			'items' => $items,
		);

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}

	public function get_data() {

		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_medicine');	
		$query = $this->db->get();
		$total_medicine = $query->num_rows();
		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_chemist');
		$this->db->where('slcd', 'CL');
		$query = $this->db->get();
		$total_chemist = $query->num_rows();
		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_users');
		$query = $this->db->get();
		$total_salesman = $query->num_rows();
		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_master');
		$this->db->where('slcd', 'SM');
		$this->db->where('altercode !=', '');
		$query = $this->db->get();
		$total_rider = $query->num_rows();
		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_cart');
		$this->db->where('status', '0');
		$query = $this->db->get();
		$total_cart = $query->num_rows();
		/****************************************** */
		$this->db->distinct();
		$this->db->select('chemist_id');
		$this->db->from('tbl_cart_order');
		$this->db->where('date', date('Y-m-d'));	
		$query = $this->db->get();
		$total_unique_order = $query->num_rows();
		/****************************************** */
		$this->db->select('COUNT(id) as total, SUM(total) as total_amt');
		$this->db->from('tbl_cart_order');
		$this->db->where('date', date('Y-m-d'));
		$query = $this->db->get();
		$order_data = $query->row_array();
		$total_order = $order_data['total'];
		$total_order_amount = utf8_encode(money_format('%!.0n',$order_data['total_amt']));
		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_cart_order');
		$this->db->where('date', date('Y-m-d'));
		$this->db->where('download_status', '0');
		$query = $this->db->get();
		$total_order_download = $query->num_rows();
		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_cart');
		$this->db->where('date', date('Y-m-d'));
		$this->db->where('status', '1');
		$query = $this->db->get();
		$total_order_items = $query->num_rows();
		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_cart_order');
		$this->db->where('date', date('Y-m-d'));
		$this->db->where('order_type','pc_mobile');	
		$query = $this->db->get();
		$total_pc_mobile_order = $query->num_rows();
		/****************************************** */
		$this->db->select('id');
		$this->db->from('tbl_cart_order');
		$this->db->where('date', date('Y-m-d'));
		$this->db->where('order_type','android');	
		$query = $this->db->get();
		$total_android_order = $query->num_rows();
		/****************************************** */
		$this->db->select('COUNT(id) as total, SUM(amt) as total_amt');
		$this->db->from('tbl_invoice');
		$this->db->where('date', date('Y-m-d'));
		$query = $this->db->get();
		$invoice_data = $query->row_array();
		$total_invoices = $invoice_data['total'];
		$total_invoices_amount = utf8_encode(money_format('%!.0n',$invoice_data['total_amt']));
		/****************************************** */
		$this->db->distinct();
		$this->db->select('chemist_id');
		$this->db->from('tbl_activity_logs');
		$this->db->where('timestamp >=', time() - 300); // Last 5 minutes	
		$query = $this->db->get();
		$active_user_count = $query->num_rows();
		/***************************************** */
		$this->db->distinct();
		$this->db->select('chemist_id');
		$this->db->from('tbl_activity_logs');
		$this->db->where('date', date('Y-m-d'));	
		$query = $this->db->get();
		$today_active_user_count = $query->num_rows();
		/****************************************** */

		// Combine both results into a single array
		$result = array(
			'total_medicine' => $total_medicine,
			'total_chemist' => $total_chemist,
			'total_salesman' => $total_salesman,
			'total_rider' => $total_rider,
			'total_cart' => $total_cart,
			'total_unique_order' => $total_unique_order,
			'total_order' => $total_order,
			'total_order_download' => $total_order_download,
			'total_order_amount' => $total_order_amount,
			'total_order_items' => $total_order_items,
			'total_pc_mobile_order' => $total_pc_mobile_order,
			'total_android_order' => $total_android_order,
			'total_invoices' => $total_invoices,
			'total_invoices_amount' => $total_invoices_amount,
			'active_user_count' => $active_user_count,
			'today_active_user_count' => $today_active_user_count,
		);

		// Output the result as JSON
		echo json_encode($result);
	}
}