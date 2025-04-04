<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_bank_user_chemist extends CI_Controller {
	public $Page_title = "Manage Bank User Chemist";
	public $Page_name  = "manage_bank_user_chemist";
	public $Page_view  = "manage_bank_user_chemist";
	public $Page_menu  = "manage_bank_user_chemist";
	public $page_controllers = "manage_bank_user_chemist";
	public $Page_tbl   = "tbl_chemist";
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->model("model-bank/BankModel");
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
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
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

				$result = $this->BankModel->select_query("SELECT * FROM $Page_tbl ORDER BY id DESC");
				$result = $result->result();

				foreach($result as $row){

					$sr_no = $i++;
					$id = $row->id;
					$chemist_id = $row->altercode;
					$name = $row->name;
					$telephone = $row->telephone;
					$telephone1 = $row->telephone1;
					$mobile = $row->mobile;
					$email = $row->email;

					$time = time();
					$datetime = date("d-M-y @ h:i a",strtotime($time));

					$dt = array(
						'sr_no' => $sr_no,
						'id' => $id,
						'chemist_id' => $chemist_id,
						'name'=>$name,
						'telephone'=>$telephone,
						'telephone1'=>$telephone1,
						'mobile'=>$mobile,
						'email'=>$email,
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
}