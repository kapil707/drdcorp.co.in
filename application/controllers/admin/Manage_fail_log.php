<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_fail_log extends CI_Controller {
	var $Page_title = "Manage Fail Log";	
	var $Page_name  = "manage_fail_log";
	var $Page_view  = "manage_fail_log";
	var $Page_menu  = "manage_fail_log";
	var $page_controllers = "manage_fail_log";
	var $Page_tbl   = "tbl_whatsapp_email_fail";
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
	}
	public function view()
	{
		error_reporting(0);
		/******************session***********************/
		$user_id = $this->session->userdata("user_id");
		$user_type = $this->session->userdata("user_type");
		/******************session***********************/		

		$_SESSION["latitude"] = 
		$_SESSION["longitude"] = "";	

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

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";

		$this->load->library('pagination');

		$result = $this->db->query("select * from $tbl order by altercode asc")->result();
		
		$config['total_rows'] = count($result);
		$data["count_records"] = count($result);
		$config['per_page'] = 100;

		if($num!=""){
			$config['per_page'] = $num;
		}
		$config['full_tag_open']="<ul class='pagination'>";
		$config['full_tag_close']="</ul>";
		$config['first_tag_open']='<li>';
		$config['first_tag_close']='</li>';
		$config['last_tag_open']='<li>';
		$config['last_tag_close']='</li>';
		$config['next_tag_open']='<li>';
		$config['next_tag_close']='</li>';
		$config['prev_tag_open']='<li>';
		$config['prev_tag_close']='</li>';
		$config['num_tag_open']='<li>';
		$config['num_tag_close']='</li>';
		$config['cur_tag_open']="<li class='active'><a>";
		$config['cur_tag_close']='</a></li>';
		$config['num_links'] = 100;    
		$config['page_query_string'] = TRUE;
		$per_page=$_GET["pg"];
		if($per_page=="")
		{
			$per_page = 0;
		}

		$data['per_page']=$per_page;
		
		$data['user_id'] = $user_id;

		$query = $this->db->query("select * from $tbl order by altercode asc LIMIT $per_page,100");
  
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
}