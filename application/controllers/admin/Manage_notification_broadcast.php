<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_notification_broadcast extends CI_Controller {
	var $Page_title = "Manage Notification Broadcast";	
	var $Page_name  = "manage_notification_broadcast";
	var $Page_view  = "manage_notification_broadcast";
	var $Page_menu  = "manage_notification_broadcast";
	var $page_controllers = "manage_notification_broadcast";
	var $Page_tbl   = "tbl_broadcast";
	public function __construct(){
		parent::__construct();
		$this->load->model("model-drdweb/BroadcastModel");
	}
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
	}
	public function add()
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

		$this->Admin_Model->permissions_check_or_set($Page_title,$Page_name,$user_type);	

		$data['title1'] = $Page_title." || Add";
		$data['title2'] = "Add";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;	

		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Add","admin/$page_controllers/add");		

		$tbl = $Page_tbl;
		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";	

		$system_ip = $this->input->ip_address();
		extract($_POST);
		if(isset($Submit))
		{	
			$message = nl2br($message);
			$message = str_replace("'","&#39;",$message);
			$message = str_replace("\r\n","<br>",$message);		

			$message_db = "";
			
			$result = "";
			if(empty($find_chemist_id))
			{ 
				$message_db = "Select Chemist";
				$message = "Select Chemist";
				$this->session->set_flashdata("message_type","error");
			} else {
				if(!empty($find_chemist_id) && $find_chemist_id!="all" && $find_chemist_id!="all login")
				{
					$query1 = $this->db->query("select * from tbl_chemist where slcd='CL' and altercode='$find_chemist_id' order by name asc")->result();
				}
				if($find_chemist_id=="all")
				{
					$query1 = $this->db->query("select * from tbl_chemist where slcd='CL' order by name asc")->result();
				}
				if($find_chemist_id=="all login")
				{
					$query1 = $this->db->query("SELECT tbl_chemist.* FROM tbl_chemist_other INNER join `tbl_chemist` on tbl_chemist.code=tbl_chemist_other.code order by tbl_chemist.name asc")->result();
				}
				foreach($query1 as $row1)
				{
					$chemist_id = $row1->altercode;
					
					$result = $this->BroadcastModel->insert_broadcast($title,$message,$chemist_id,'Admin');
				}
				if($result)
				{
					$message_db = "() - Add Successfully.";
					$message = "Add Successfully.";
					$this->session->set_flashdata("message_type","success");
				}
				else
				{
					$message_db = "() - Not Add.";
					$message = "Not Add.";
					$this->session->set_flashdata("message_type","error");
				}
			}
			if($message_db!="")
			{
				$message = $Page_title." - ".$message;
				$message_db = $Page_title." - ".$message_db;
				$this->session->set_flashdata("message_footer","yes");
				$this->session->set_flashdata("full_message",$message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if($result)
				{
					redirect(base_url()."admin/$page_controllers/view");
				}
			}
		}		

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/manage_user_chemist/find_chemist",$data);
	}
	public function view()
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

		$this->Admin_Model->permissions_check_or_set($Page_title,$Page_name,$user_type);

		$data['title1'] = $Page_title." || View";
		$data['title2'] = "View";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;

		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("View","admin/$page_controllers/view");	

		$tbl = $Page_tbl;	

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}
	
	public function add2($page_type="")
	{
		/******************session***********************/
		$user_id = $this->session->userdata("user_id");
		$user_type = $this->session->userdata("user_type");
		/******************session***********************/		

		$Page_title = $this->Page_title;
		$Page_name 	= $this->Page_name;
		$Page_view 	= $this->Page_view;
		$Page_menu 	= $this->Page_menu;
		$Page_tbl 	= "tbl_website";
		$page_controllers 	= $this->page_controllers;		

		$this->Admin_Model->permissions_check_or_set($Page_title,$Page_name,$user_type);
		$Page_menu  = $page_type;
		
		$data['title1'] = $Page_title." || Add";
		$data['title2'] = "Add";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;	

		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Add","admin/$page_controllers/add");		

		$tbl = $Page_tbl;

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";		

		if($page_type=="broadcast_title")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Broadcast Title";
			$data["placeholderpg"] = "Broadcast Title";
			$data["pagetextpg"] = "";
		}

		if($page_type=="broadcast_message")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Broadcast Message";
			$data["placeholderpg"] = "Broadcast Message";
			$data["pagetextpg"] = "";
		}

		if($page_type=="broadcast_status")
		{
			$data["type"] = "checkbox";
			$data["titlepg"] = "Broadcast Status";
			$data["placeholderpg"] = "Broadcast Status";
			$data["pagetextpg"] = "";
		}

		extract($_POST);
		if(isset($Submit))
		{
			if($page_type=="send_whatsapp_message_all_chemist")
			{
				$this->Scheme_Model->send_whatsapp_message_all_chemist($mydata);
			}
			
			$message_db = "";
			if($type=="image")
			{
				if (!empty($_FILES["image"]["name"]))
				{
					$x = $_FILES["image"]['name'];
					$y = $_FILES["image"]['tmp_name'];
					$mydata = $this->Scheme_Model->photo_up("photo",$x,$y,$upload_path);
				}
				else
				{
					$mydata = $old_mydata;
				}
			}
			$mydata = base64_encode($mydata);
			$time = time();
			$date = date("Y-m-d",$time);
			
			$result = "";
			$dt = array('mydata'=>$mydata,'page_type'=>$page_type,'update_date'=>$date,'update_time'=>$time,);	

			$change_text = "";
			if($old_mydata!=$mydata)
			{
				if($data["type"]=="text")
				{
					$change_text = $data["titlepg"]." - ($old_mydata to ".base64_decode($mydata).")";
				}
				if($data["type"]=="image")
				{
					$change_text = $data["titlepg"]." - (Upload) ";
					$url_path = "./uploads/$page_controllers/photo/";
					$query = $this->db->query("select * from $tbl where page_type='$page_type'");
					$row11 = $query->row();
					$filename = $url_path.base64_decode($row11->mydata);
					unlink($filename);
				}
			}		

			$query = $this->db->query("select * from $tbl where page_type='$page_type'");
			$row = $query->row();
			if(empty($row->id))
			{
				$result = $this->Scheme_Model->insert_fun($tbl,$dt);
			}
			else
			{
				$where = array('page_type'=>$page_type);
				$result = $this->Scheme_Model->edit_fun($tbl,$dt,$where);
			}
			if($result)
			{
				$message_db = "$change_text - Set Successfully.";
				$message = "Set Successfully.";
				$this->session->set_flashdata("message_type","success");
			}
			else
			{
				$message_db = "$change_text - Not Set.";
				$message = "Not Set.";
				$this->session->set_flashdata("message_type","error");
			}
			if($message_db!="")
			{
				$message = $Page_title." - ".$message;
				$message_db = $Page_title." - ".$message_db;
				$this->session->set_flashdata("message_footer","yes");
				$this->session->set_flashdata("full_message",$message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if($result)
				{
					redirect(current_url());
				}
			}
		}
		$data["mydata"] = "";
		$query = $this->db->query("select * from $tbl where page_type='$page_type'");
		$row = $query->row();
		if(!empty($row->id))
		{
			$data["mydata"] = base64_decode($row->mydata);
		}		

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add2",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}

	public function view_api() {
		
		$i = 1;
		$Page_tbl = $this->Page_tbl;
		if(!empty($_REQUEST)){
			$from_date 	= $_REQUEST["from_date"];
			$to_date	= $_REQUEST['to_date'];

			$jsonArray = array();

			$items = "";
			if(!empty($from_date) && !empty($to_date)){

				$result = $this->db->query("SELECT $Page_tbl.*,tbl_chemist.name FROM $Page_tbl left join tbl_chemist on tbl_chemist.altercode=$Page_tbl.chemist_id  WHERE $Page_tbl.date BETWEEN '$from_date' and '$to_date' order by $Page_tbl.id desc");
				$result = $result->result();

				foreach($result as $row){

					$sr_no = $i++;
					$id = $row->id;
					$user = $row->name."(".$row->chemist_id.")";
					$title = $row->title;
					$message = $row->message;
					$insert_type = $row->insert_type;
					$datetime = date("d-M-y @ H:i:s", $row->timestamp);

					$dt = array(
						'sr_no' => $sr_no,
						'id' => $id,
						'user' => $user,
						'title' => $title,
						'message'=>$message,
						'insert_type'=>$insert_type,
						'datetime'=>$datetime,
					);
					$jsonArray[] = $dt;
				}
			}

			$items = $jsonArray;
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
			);
		}else{
			$response = array(
				'success' => "0",
				'message' => '502 error',
			);
		}

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
	
	public function delete_rec()
	{
		$id = $_POST["id"];
		$Page_title = $this->Page_title;
		$Page_tbl = $this->Page_tbl;
		$result = $this->db->query("delete from $Page_tbl where id='$id'");
		if($result)
		{
			$message = "Delete Successfully.";
		}
		else
		{
			$message = "Not Delete.";
		}
		$message = $Page_title." - ".$message;
		$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}
}