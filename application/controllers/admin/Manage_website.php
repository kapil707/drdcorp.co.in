<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_website extends CI_Controller {
	var $Page_title = "Manage Website";
	var $Page_name  = "manage_website";
	var $Page_view  = "manage_website";
	var $Page_menu  = "manage_website";
	var $page_controllers = "manage_website";
	var $Page_tbl   = "tbl_website";
	
	var $Image_Width   = "500";
	var $Image_Height  = "120";
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
		
		redirect(base_url()."admin/dashboard");
	}
	public function add($page_type="")
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
		
		$Page_menu  = $page_type;
		
		$data['title1'] = $Page_title." || Add";
		$data['title2'] = "Add";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;		
		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Add","admin/$page_controllers/add");
		
		$tbl = $Page_tbl;
		
		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/main/";
		$data['url_resize'] = base_url()."uploads/$page_controllers/photo/resize/";
		$upload_path 		= "./uploads/$page_controllers/photo/main/";
		$upload_resize 		= "./uploads/$page_controllers/photo/resize/";
		$data["Image_Width"]  = $this->Image_Width;
		$data["Image_Height"] = $this->Image_Height;
		
		$data["type"] = "text";
		if($page_type=="title")
		{
			$data["titlepg"] = "Website Title";
			$data["placeholderpg"] = "Website Title";
			$data["pagetextpg"] = "";
		}
		if($page_type=="logo")
		{
			$data["type"] = "image";
			$data["titlepg"] = "Website Logo";
			$data["placeholderpg"] = "Website Logo";
			$data["pagetextpg"] = "width : $this->Image_Width Px<br>Height : $this->Image_Height Px";
		}
		if($page_type=="icon")
		{
			$data["type"] = "image";
			$data["titlepg"] = "Website Icon";
			$data["placeholderpg"] = "Website Icon";
			$data["pagetextpg"] = "";
		}
		
		extract($_POST);
		if(isset($Submit))
		{
			$message_db = "";
			if($type=="image")
			{
				if (!empty($_FILES["image"]["name"]))
				{
					$this->Image_Model->uploadTo = $upload_path;
					$photo = $this->Image_Model->upload($_FILES['image']);
					$photo = str_replace($upload_path,"",$photo);
					
					$this->Image_Model->newPath = $upload_resize;
					$this->Image_Model->newWidth  = $this->Image_Width;
					$this->Image_Model->newHeight = $this->Image_Height;
					$this->Image_Model->resize();
				
					$mydata = $photo;
				}
				else
				{
					$mydata = $old_mydata;
				}
			}

			$timestamp = time();
			$date = date("Y-m-d",$timestamp);
			$time = date("H:i",$timestamp);
			
			$result = "";
			$dt = array('data'=>$mydata,'page_type'=>$page_type,'date'=>$date,'time'=>$time,'timestamp'=>$timestamp,);
			
			$change_text = "";
			if($old_mydata!=$mydata)
			{
				if($data["type"]=="text")
				{
					$change_text = $data["titlepg"]." - ($old_mydata to ".$mydata.")";
				}
				if($data["type"]=="image")
				{
					$change_text = $data["titlepg"]." - (Upload) ";											
					$url_path = "./uploads/$page_controllers/photo/";					
					$query = $this->db->query("select * from $tbl where page_type='$page_type'");
					$row11 = $query->row();
					$filename = $url_path.($row11->data);
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
		$query = $this->db->query("select data from $tbl where page_type='$page_type'");
		$row = $query->row();
		if(!empty($row->data))
		{
			$data["mydata"] = $row->data;
		}
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
}