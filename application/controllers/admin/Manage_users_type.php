<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_users_type extends CI_Controller {
	var $Page_title = "Manage Users Type";
	var $Page_name  = "manage_users_type";
	var $Page_view  = "manage_users_type";
	var $Page_menu  = "manage_users_type";
	var $page_controllers = "manage_users_type";
	var $Page_tbl   = "tbl_user_type";
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
		$upload_thumbs_path = "./uploads/$page_controllers/photo/thumbs/";
		
		extract($_POST);
		if(isset($Submit))
		{
			$message_db = "";
			$result = "";
			/*$this->form_validation->set_rules('user_type_title','User Type Title',"required|is_unique[$tbl.user_type_title]",
			array(
			"is_unique"=>"<p class='font-bold  alert alert-danger m-b-sm'>This User Type already exists.</p>"
			));
			if ($this->form_validation->run() == FALSE)
			{
				$message = "Check Validation.";
				$this->session->set_flashdata("message_type","warning");
			}
			else{*/
				$timestamp = time();
				$date = date("Y-m-d",$timestamp);
				$time = date("H:i",$timestamp);
				
				$user_type = str_replace(" ","_",$user_type_title);

				$dt = array('user_type_title'=>$user_type_title,'user_type'=>$user_type,'user_id'=>$user_id,'date'=>$date,'time'=>$time,'timestamp'=>$timestamp,'status'=>1);
				$result = $this->Scheme_Model->insert_fun($tbl,$dt);
				if($result)
				{
					$message_db = "($user_title) Add Successfully.";
					$message = "Add Successfully.";
					$this->session->set_flashdata("message_type","success");
				}
				else
				{
					$message_db = "$user_title Not Add.";
					$message = "Not Add.";
					$this->session->set_flashdata("message_type","error");
				}
			//}
			if(!empty($message_db))
			{
				$message = $Page_title." - ".$message;
				$message_db = $Page_title." - ".$message_db;
				$this->session->set_flashdata("message_footer","yes");
				$this->session->set_flashdata("full_message",$message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if(!empty($result))
				{
					redirect(base_url()."admin/$page_controllers/view");
				}
			}
		}
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
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
	
	public function edit($id)
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
		
		$data['title1'] = $Page_title." || Edit";
		$data['title2'] = "Edit";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;		
		$this->breadcrumbs->push("Edit","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Edit","admin/$page_controllers/edit");
		
		$tbl = $Page_tbl;
		
		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";
		$upload_thumbs_path = "./uploads/$page_controllers/photo/thumbs/";
		
		extract($_POST);
		if(isset($Submit))
		{
			$message_db = "";
			$result = "";
			/*if($old_user_type_title!=$user_type_title)
			{
				$this->form_validation->set_rules('user_type_title','User Type Title',"required|is_unique[$tbl.user_type_title]",
				array(
					"is_unique"=>"<p class='font-bold  alert alert-danger m-b-sm'>This User Type already exists.</p>"
				));
			}
			else
			{
				$this->form_validation->set_rules('user_type_title','User Type',"required");
			}*/

			/*if ($this->form_validation->run() == FALSE)
			{
				$message = "Check Validation.";
				$this->session->set_flashdata("message_type","warning");
			}
			else
			{*/	
				$timestamp = time();
				$date = date("Y-m-d",$timestamp);
				$time = date("H:i",$timestamp);
					
				$user_type = str_replace(" ","_",$user_type_title);
				
				$dt = array('user_type_title'=>$user_type_title,'user_type'=>$user_type,'user_id'=>$user_id,'date'=>$date,'time'=>$time,'timestamp'=>$timestamp,'status'=>1);
				$where = array('id'=>$id);
				$result = $this->Scheme_Model->edit_fun($tbl,$dt,$where);
				if($result)
				{
					$message_db = "($old_user_title to $user_title) - Edit Successfully.";
					$message = "Edit Successfully.";
					$this->session->set_flashdata("message_type","success");
				}
				else
				{
					$message_db = "$old_user_title to $user_title - Not Add.";
					$message = "Not Add.";
					$this->session->set_flashdata("message_type","error");
				}
			//}
			if(!empty($message_db))
			{
				$message = $Page_title." - ".$message;
				$message_db = $Page_title." - ".$message_db;
				$this->session->set_flashdata("message_footer","yes");
				$this->session->set_flashdata("full_message",$message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if(!empty($result))
				{
					//redirect(current_url());
					redirect(base_url()."admin/$page_controllers/edit/".$id);
				}
			}
		}
		
		$query = $this->db->query("select * from $tbl where id='$id' and user_id='$user_id' order by id desc");
		$data["result"] = $query->result();
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/edit",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
	
	public function delete_rec()
	{
		$id = $_POST["id"];
		$Page_title = $this->Page_title;
		$Page_tbl = $this->Page_tbl;
		
		$query = $this->db->query("select * from $Page_tbl where id='$id'");
		$row1 = $query->row();
		$name = ucfirst($row1->user_title);
		
		$result = $this->db->query("delete from $Page_tbl where id='$id'");
		if($result)
		{
			$message = "$name - Delete Successfully.";
		}
		else
		{
			$message = "$name - Not Delete.";
		}
		$message = $Page_title." - ".$message;
		$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}

	public function view_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;
		$Page_tbl = $this->Page_tbl;

		$user_id = $this->session->userdata("user_id");

		$query  = $this->db->query("select * from $Page_tbl where user_id='$user_id'");
  		$result = $query->result();
		foreach($result as $row) {

			$sr_no = $i++;
			$id = $row->id;
			
			$user_type_title = $row->user_type_title;
			$user_type 	= $row->user_type;
			$isdefault 	= $row->isdefault;
			
			$timestamp = $row->timestamp;
			if(empty($timestamp)){
				$timestamp = time();
			}
			$timestamp = date("d-M-y @ H:i:s", $timestamp);

			$dt = array(
				'sr_no' => $sr_no,
				'id' => $id,
				'user_type_title' => $user_type_title,
				'user_type' => $user_type,
				'isdefault' => $isdefault,
				'timestamp' => $timestamp,
			);
			$jsonArray[] = $dt;
		}
		if(!empty($jsonArray)){
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
}