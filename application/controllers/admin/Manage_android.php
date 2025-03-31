<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_android extends CI_Controller {
	var $Page_title = "Manage Android";
	var $Page_name  = "manage_android";
	var $Page_view  = "manage_android";
	var $Page_menu  = "manage_android";
	var $page_controllers = "manage_android";
	var $Page_tbl   = "tbl_website";
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

		$this->Admin_Model->permissions_check_or_set($Page_title,$Page_name,$user_type);
	}
	public function add($page_type="")
	{
		error_reporting(0);
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

		$data["type"] = "text";
		if($page_type=="android_mobile")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Android Mobile";
			$data["placeholderpg"] = "Android Mobile";
			$data["pagetextpg"] = "";
		}

		if($page_type=="android_email")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Android Email";
			$data["placeholderpg"] = "Android Email";
			$data["pagetextpg"] = "";
		}

		if($page_type=="android_whatsapp")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Android Whatsapp";
			$data["placeholderpg"] = "Android Whatsapp";
			$data["pagetextpg"] = "";
		}

		if($page_type=="force_update_title")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Android Force Update Title";
			$data["placeholderpg"] = "Android Force Update Title";
			$data["pagetextpg"] = "";
		}

		if($page_type=="force_update_message")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Android Force Update Message";
			$data["placeholderpg"] = "Android Force Update Message";
			$data["pagetextpg"] = "";
		}

		if($page_type=="force_update")
		{
			$data["type"] = "checkbox";
			$data["titlepg"] = "Android Force Update";
			$data["placeholderpg"] = "Android Force Update";
			$data["pagetextpg"] = "";
		}

		if($page_type=="android_versioncode")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Android Version";
			$data["placeholderpg"] = "Android Version";
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
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
}