<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_bank_processing extends CI_Controller {
	var $Page_title = "Manage Bank Processing";
	var $Page_name  = "manage_bank_processing";
	var $Page_view  = "manage_bank_processing";
	var $Page_menu  = "manage_bank_processing";
	var $page_controllers = "manage_bank_processing";
	var $Page_tbl   = "tbl_bank_processing";
	public function __construct()
    {
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
		error_reporting(-1);
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
		
		//$query = $this->BankModel->select_query("SELECT * FROM `tbl_bank_processing` where date BETWEEN '$start_date' AND '$end_date' order by statment_id asc");
		$query = $this->BankModel->select_query("SELECT tbl_whatsapp_message.vision_text as whatsapp_text,tbl_whatsapp_message.from_number as whatsapp_number,
		tbl_whatsapp_message.timestamp as whatsapp_timestamp,tbl_whatsapp_message.set_chemist as whatsapp_set_chemist,tbl_bank_processing.* FROM `tbl_bank_processing` left JOIN tbl_whatsapp_message ON tbl_whatsapp_message.id = tbl_bank_processing.whatsapp_id WHERE tbl_bank_processing.date BETWEEN '$start_date' AND '$end_date' order by tbl_bank_processing.statment_id asc");
		$data["result"] = $query->result();

		$query = $this->BankModel->select_query("SELECT COUNT(tbl_bank_processing.id) AS total_processing, COUNT(tbl_whatsapp_message.vision_text) AS total_whatsapp, SUM(CASE WHEN tbl_bank_processing.from_sms IS NOT NULL AND tbl_bank_processing.from_sms != '' THEN 1 ELSE 0 END) AS total_sms, SUM(CASE WHEN tbl_bank_processing.from_statment IS NOT NULL AND tbl_bank_processing.from_statment != '' THEN 1 ELSE 0 END) AS total_statment, SUM(CASE WHEN tbl_bank_processing.whatsapp_chemist IS NOT NULL AND tbl_bank_processing.whatsapp_chemist != '' THEN 1 ELSE 0 END) AS total_whatsapp_chemist, SUM(CASE WHEN tbl_bank_processing.invoice_id IS NOT NULL AND tbl_bank_processing.invoice_id != '' THEN 1 ELSE 0 END) AS total_invoice FROM tbl_bank_processing LEFT JOIN tbl_whatsapp_message ON tbl_whatsapp_message.id = tbl_bank_processing.whatsapp_id WHERE tbl_bank_processing.date BETWEEN '$start_date' AND '$end_date' ORDER BY tbl_bank_processing.statment_id ASC");
		$myrow = $query->row();
		$data["total_processing"] = $myrow->total_processing;
		$data["total_sms"] = $myrow->total_sms;
		$data["total_statment"] = $myrow->total_statment;
		$data["total_whatsapp"] = $myrow->total_whatsapp;
		$data["total_whatsapp"] = $myrow->total_whatsapp;
		$data["total_whatsapp_chemist"] = $myrow->total_whatsapp_chemist;
		$data["total_invoice"] = $myrow->total_invoice;

		$query = $this->BankModel->select_query("SELECT COUNT(id) AS total FROM tbl_sms WHERE date BETWEEN '$start_date' AND '$end_date' ORDER BY id ASC");
		$myrow = $query->row();
		$data["other_total_sms"] = $myrow->total;

		$query = $this->BankModel->select_query("SELECT COUNT(id) AS total FROM tbl_statment WHERE date BETWEEN '$start_date' AND '$end_date' ORDER BY id ASC");
		$myrow = $query->row();
		$data["other_total_statment"] = $myrow->total;

		$query = $this->BankModel->select_query("SELECT COUNT(id) AS total FROM tbl_whatsapp_message WHERE date BETWEEN '$start_date' AND '$end_date' ORDER BY id ASC");
		$myrow = $query->row();
		$data["other_total_whatsapp"] = $myrow->total;

		$query = $this->BankModel->select_query("SELECT COUNT(id) AS total FROM tbl_invoice WHERE date BETWEEN '$start_date' AND '$end_date' ORDER BY id ASC");
		$myrow = $query->row();
		$data["other_total_invoice"] = $myrow->total;

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	public function add_final_chemist()
	{
		$id 			= $_POST["id"];
		$final_chemist	= $_POST["chemist_id"];
		if(!empty($id) && !empty($final_chemist)){

			$query = $this->BankModel->select_query("SELECT * FROM `tbl_bank_processing` where id='$id'");
			$row = $query->row();
			$upi_no = $row->upi_no;
			
			/********************************************* */			
			if(!empty($upi_no)){
				$where = array(
					'upi_no' => $upi_no,
				);
				$dt = array(
					'final_chemist'=>$final_chemist,
					'process_status'=>'4',
				);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
	}

	public function add_from_text_chemist_id()
	{
		$chemist_id = $_POST["chemist_id"];
		$from_text 	= $_POST["from_text"];
		if(!empty($chemist_id) && !empty($from_text)){

			$query = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` where string_value='$from_text'");
			$row = $query->row();
			if(empty($row)){
				$dt = array(
					'chemist_id' =>$chemist_id,
					'string_value' =>$from_text,
					'date'=>date('Y-m-d'),
					'time'=>date('H:i'),
					'timestamp'=>time(),
					'user_id'=>$this->session->userdata("user_id")
				);
				$this->BankModel->insert_fun("tbl_bank_chemist", $dt);
			}

			/********************************************* */
			// agar kisi from user ko chmist say add kartay ha to jitnay be from user ha sab ka status 0 ho jaya or wo re-process hota ha 
			$where = array(				
				'from_text'=>$from_text,
			);
			$dt = array(
				'process_status'=>'0',
				'whatsapp_id'=>0,
				'whatsapp_chemist'=>'',
				'whatsapp_recommended'=>'',
				'invoice_id'=>'',
				'invoice_chemist'=>'',
				'invoice_recommended'=>'',
				'invoice_text'=>'',
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}

	public function row_refresh(){
		$id = $_POST["id"];
		if(!empty($id)){

			$query = $this->BankModel->select_query("SELECT * FROM `tbl_bank_processing` where id='$id'");
			$row = $query->row();
			$upi_no = $row->upi_no;
			
			/********************************************* */
			if(!empty($upi_no)){
				$where = array(
					'upi_no' => $upi_no,
				);
				$dt = array(
					'process_status'=>0,
					'from_text_find'=>'',
					'from_text_find_match'=>'',
					'from_text_find_chemist'=>'',
					'final_chemist'=>'',
					'whatsapp_id'=>0,
					'whatsapp_chemist'=>'',
					'whatsapp_recommended'=>'',
					'invoice_id'=>'',
					'invoice_chemist'=>'',
					'invoice_recommended'=>'',
					'invoice_text'=>'',
				);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
	}

	public function get_whatsapp_message(){
		$row_whatsapp_id = $_POST["row_whatsapp_id"];
		if(!empty($row_whatsapp_id)){
			$row1 = $this->BankModel->select_query("SELECT from_number,timestamp FROM `tbl_whatsapp_message` WHERE id='$row_whatsapp_id'");
			$row1 = $row1->row();
			$from_number = $row1->from_number;
			//$timestamp = date('Y-m-d H:i:s', $row1->timestamp);
			$timestamp = $row1->timestamp;

			//echo "SELECT from_number,body,ist_timestamp,REPLACE(vision_text, '\n', ' ') AS vision_text,screenshot_image FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) LIMIT 0, 25";

			$result = $this->BankModel->select_query("SELECT from_number, body, ist_timestamp, vision_text, screenshot_image FROM tbl_whatsapp_message WHERE from_number = '$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB(FROM_UNIXTIME($timestamp), INTERVAL 7 MINUTE) AND DATE_ADD(FROM_UNIXTIME($timestamp), INTERVAL 7 MINUTE) LIMIT 25 OFFSET 0");
			$result = $result->result();
			
			$items = $result;
	
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
			);
	
			// Send JSON response
			header('Content-Type: application/json');
			echo json_encode($response);
		}
	}

	public function add_whatsapp_chemist_id()
	{
		$id 				= $_POST["row_id"];
		$whatsapp_chemist 	= $_POST["whatsapp_chemist"];
		if(!empty($id) && !empty($whatsapp_chemist)){

			$query = $this->BankModel->select_query("SELECT * FROM `tbl_bank_processing` where id='$id'");
			$row = $query->row();
			$upi_no = $row->upi_no;

			/********************************************* */
			if(!empty($upi_no)){
				$where = array(
					'upi_no' => $upi_no,
				);
				$dt = array(
					'whatsapp_chemist'=>$whatsapp_chemist
				);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
	}
}