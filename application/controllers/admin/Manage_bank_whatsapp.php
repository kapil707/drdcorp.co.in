<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_bank_whatsapp extends CI_Controller {
	var $Page_title = "Manage Bank WhatsApp";
	var $Page_name  = "manage_bank_whatsapp";
	var $Page_view  = "manage_bank_whatsapp";
	var $Page_menu  = "manage_bank_whatsapp";
	var $page_controllers = "manage_bank_whatsapp";
	var $Page_tbl   = "";
	public function __construct()
    {
        parent::__construct();
		$this->load->model("model-drdcorp/BankModel");
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
		
		$start_date = $end_date = date('d-m-Y');
		if(isset($_GET["date-range"])){
			$date_range = $_GET["date-range"];
	
			// `to` ke aas paas se string ko tukdon mein vibhajit karen
			$date_parts = explode(" to ", $date_range);
	
			// Start date aur end date ko extract karen
			$start_date = $date_parts[0];
			$end_date 	= $date_parts[1];
		}

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);
	
		$start_date = $start_date->format('Y-m-d');
		$end_date 	= $end_date->format('Y-m-d');
		
		//$end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
		
		//echo "select * from tbl_whatsapp_message where date BETWEEN '$start_date' AND '$end_date'";
		$result = $this->BankModel->select_query("select tbl_whatsapp_message.*,tbl_bank_processing.final_chemist from tbl_whatsapp_message left join tbl_bank_processing on tbl_bank_processing.whatsapp_message_id=tbl_whatsapp_message.id where tbl_whatsapp_message.date BETWEEN '$start_date' AND '$end_date'");
		$data["result"] = $result->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}
	
	public function add_chemist()
	{
		$id 		= $_POST["row_id"];
		$chemist 	= $_POST["chemist"];
		if(!empty($id) && !empty(chemist)){
			
			$where = array('id' => $id,);
			$dt = array('set_chemist'=>$chemist);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
		}
	}
	
	public function add_amount()
	{
		$id 		= $_POST["row_id"];
		$amount 	= $_POST["amount"];
		if(!empty($id) && !empty($amount)){
			
			$where = array('id' => $id,);
			$dt = array('amount'=>$amount);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			
			$result = $this->BankModel->select_query("select date from tbl_whatsapp_message where id='$id'");
			$row = $result->row();
			//echo $row->date;
			$backDate = date('Y-m-d', strtotime('-2 days', strtotime($row->date)));
			
			$where = array('amount' => $amount,'date>=' => $backDate,);
			$dt = array('status'=>$status);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}
}