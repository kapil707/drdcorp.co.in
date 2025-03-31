<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_stock_low extends CI_Controller {	
	var $Page_title = "Manage Stock Low";	
	var $Page_name  = "manage_stock_low";	
	var $Page_view  = "manage_stock_low";	
	var $Page_menu  = "manage_stock_low";	
	var $page_controllers = "manage_stock_low";	
	var $Page_tbl   = "tbl_stock_low";	
	
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model('model-drdweb/StockLowModel');
	}
	
	public function index()	{		
		$page_controllers = $this->page_controllers;		
		redirect("admin/$page_controllers/view");	
	}
	public function view()	{
		/******************session***********************/
		$user_id = $this->session->userdata("user_id");
		$user_type = $this->session->userdata("user_type");		/******************session***********************/
		
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
		
		$result = $this->StockLowModel->get_stock_low_report_admin();
		
		$data["result"] = $result;	
		$this->load->view("admin/header_footer/header",$data);	
		$this->load->view("admin/$Page_view/view",$data);	
		$this->load->view("admin/header_footer/footer",$data);	
		$this->load->view("admin/$Page_view/footer2",$data);	
	}	
}