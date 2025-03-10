<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_bank_chemist extends CI_Controller {
	var $Page_title = "Manage Bank Chemist";
	var $Page_name  = "manage_bank_chemist";
	var $Page_view  = "manage_bank_chemist";
	var $Page_menu  = "manage_bank_chemist";
	var $page_controllers = "manage_bank_chemist";
	var $Page_tbl   = "tbl_bank_chemist";
	public function __construct()
    {
        parent::__construct();
		$this->load->model("model-local/BankModel");
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
		
		//$result = $this->BankModel->select_query("select * from $tbl");
		//$data["result"] = $result->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}
	
	public function view_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;
		$Page_tbl = $this->Page_tbl;

		$result = $this->BankModel->select_query("select * from tbl_bank_chemist");
		$result = $result->result();
		foreach($result as $row) {

			$sr_no = $i++;
			$id = $row->id;
			
			$string_value = $row->string_value;
			$chemist_id = $row->chemist_id;
			
			$time = $row->time;
			$datetime = date("d-M-y @ H:i:s", $time);

			$dt = array(
				'sr_no' => $sr_no,
				'id' => $id,
				'string_value' => $string_value,
				'chemist_id' => $chemist_id,
				'datetime'=>$datetime,
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