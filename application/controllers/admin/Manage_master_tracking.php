<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_master_tracking extends CI_Controller {
	var $Page_title = "Manage Tracking";
	var $Page_name  = "manage_master_tracking";
	var $Page_view  = "manage_master_tracking";
	var $Page_menu  = "manage_master_tracking";
	var $page_controllers = "manage_master_tracking";
	var $Page_tbl   = "tbl_tracking";
	public function __construct()
    {
        parent::__construct();
		
    }
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
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

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";
		
		$db_master = $this->load->database('db_master', TRUE);
		
		$mydate = date("Y-m-d");
		if(isset($_GET["mydate"])){
			$mydate = $_GET["mydate"];
		}
		$data["mydate"] = $mydate;
		
		$result = $db_master->query("select DISTINCT user_altercode from tbl_tracking where date='$mydate' and latitude!='0.0'")->result();
		$jsonArray = array();
		foreach($result as $row){
			$jsonArray[] = $row->user_altercode;
		}
		$data["result"] = $jsonArray;
		$data["db_master"] = $db_master;
		//print_r($data);die;

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}

	public function view2($user_altercode)
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

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";
		
		$db_master = $this->load->database('db_master', TRUE);
		
		$mydate = date("Y-m-d");
		if(isset($_GET["mydate"])){
			$mydate = $_GET["mydate"];
		}
		$data["mydate"] = $mydate;
		
		$f_lat = $f_lng = "0.0";
		$query = $db_master->query("select * from tbl_tracking where date='$mydate' and user_altercode='$user_altercode'");
		$row = $query->row();
		if(!empty($row)){
			$f_lat = $row->latitude;
			$f_lng = $row->longitude;
		}
		$result = $query->result();
		foreach($result as $row){
			$l_lat = $row->latitude;
			$l_lng = $row->longitude;
		}
		$data["f_lat"] = $f_lat;
		$data["f_lng"] = $f_lng;
		$data["l_lat"] = $l_lat;
		$data["l_lng"] = $l_lng;
		// $jsonArray = array();
		// foreach($result as $row){
		// 	$jsonArray[] = $row->user_altercode;
		// }
		$data["result"] = $result;
		$data["db_master"] = $db_master;
		//print_r($data);die;

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view2",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
}