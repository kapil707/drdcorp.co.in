<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
class Manage_bank_statment extends CI_Controller {
	var $Page_title = "Manage Bank Statment";
	var $Page_name  = "manage_bank_statment";
	var $Page_view  = "manage_bank_statment";
	var $Page_menu  = "manage_bank_statment";
	var $page_controllers = "manage_bank_statment";
	var $Page_tbl   = "tbl_statment";
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

	public function add()
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

		$data['title1'] = $Page_title." || Edit";
		$data['title2'] = "Edit";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;
		$this->breadcrumbs->push("Edit","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Edit","admin/$page_controllers/edit");		

		$tbl = $Page_tbl;	

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$data['upload_path'] = "./uploads/$page_controllers/myfile/";
		$upload_thumbs_path = "./uploads/$page_controllers/photo/thumbs/";		
		$system_ip = $this->input->ip_address();

		$data["filename"] = "";
		extract($_POST);
		if (isset($Submit)) {
			$message_db = "";
			$time = time();
			$date = date("Y-m-d", $time);

			if (!empty($_FILES["myfile"]["name"])) {
				$upload_image = "./uploads/$page_controllers/myfile/";

				ini_set('upload_max_filesize', '10M');
				ini_set('post_max_size', '10M');
				ini_set('max_input_time', 300);
				ini_set('max_execution_time', 300);

				$config['upload_path'] = $upload_image;  // Define the directory where you want to store the uploaded files.
				$config['allowed_types'] = '*';  // You may want to restrict allowed file types.
				$config['max_size'] = 0;  // Set to 0 to allow any size.

				$new_name = time().$_FILES["myfile"]['name'];
				$config['file_name'] = $new_name;
		
				$this->load->library('upload', $config);
		
				if (!$this->upload->do_upload('myfile')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					print_r($error);
				} else {
					$data1 = $this->upload->data();
					$image = ($data1['file_name']);
					//$this->load->view('upload_success', $data);
				}
			}
			$filename = $image;

			if($formet==1){
				$account_no 			= "A";
				$branch_no 				= "B";
				$statment_date 			= "C";
				$closing_ledger_balance = "D";
				$calculated_balances 	= "E";
				$amount 				= "F";
				$enter_date 			= "G";
				$date 					= "H";  // value date
				$bank_reference 		= "I";
				$customer_reference 	= "J";
				$narrative 				= "K";
				$transaction_description= "L";
				$iban_number			= "M";

				$start_row 				= "10";

				$upload_path = "uploads/manage_bank_statment/myfile/";
				$excelFile = $upload_path.$filename;
				$i=1;
				if(file_exists($excelFile))
				{
					/*$this->excel = new PHPExcel();

					//$this->load->library('excel');
					$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
					{*/
					
					$spreadsheet = IOFactory::load($excelFile);
					$worksheet = $spreadsheet->getActiveSheet();
					
						$highestRow = $worksheet->getHighestRow();
						for ($row=$start_row; $row<=$highestRow; $row++)
						{
							$account_no1 = $worksheet->getCell($account_no.$row)->getValue();
							$branch_no1 = $worksheet->getCell($branch_no.$row)->getValue();
							$statment_date1 = $worksheet->getCell($statment_date.$row)->getValue();
							$closing_ledger_balance1 = $worksheet->getCell($closing_ledger_balance.$row)->getValue();
							$calculated_balances1 = $worksheet->getCell($calculated_balances.$row)->getValue();
							$amount1 = $worksheet->getCell($amount.$row)->getValue();
							$enter_date1 = $worksheet->getCell($enter_date.$row)->getValue();
							$date1 = $worksheet->getCell($date.$row)->getValue();
							$bank_reference1 = $worksheet->getCell($bank_reference.$row)->getValue();
							$customer_reference1 = $worksheet->getCell($customer_reference.$row)->getValue();
							$narrative1 = $worksheet->getCell($narrative.$row)->getValue();
							$transaction_description1 = $worksheet->getCell($transaction_description.$row)->getValue();
							$iban_number1 = $worksheet->getCell($iban_number.$row)->getValue();

							$date1 = date('Y-m-d', strtotime($date1));

							$currency1 = $beneficiary_remitter1 = $type1 = $branch_name1 = $payment_details1 = "";
							$dt = array(
								'account_no'=>$account_no1,
								'branch_no'=>$branch_no1,
								'statment_date'=>$statment_date1,
								'closing_ledger_balance'=>$closing_ledger_balance1,
								'calculated_balances'=>$calculated_balances1,
								'amount'=>$amount1,
								'enter_date'=>$enter_date1,
								'date'=>$date1,
								'bank_reference'=>$bank_reference1,
								'customer_reference'=>$customer_reference1,
								'narrative'=>$narrative1,
								'transaction_description'=>$transaction_description1,
								'iban_number'=>$iban_number1,
								'currency'=>$currency1,
								'beneficiary_remitter'=>$beneficiary_remitter1,
								'type'=>$type1,
								'branch_name'=>$branch_name1,
								'payment_details'=>$payment_details1,
								'formet'=>$formet,
							);
							$this->BankModel->insert_statment("tbl_statment", $dt);
						}
					//}
				}
				redirect(base_url()."admin/$page_controllers/view1");
			}
			
			if($formet==2){				
				$date 					= "A"; // value date
				$account_no 			= "B";
				$branch_no 				= "C";
				$beneficiary_remitter	= "D";
				$currency 				= "E";
				$amount 				= "F";
				$customer_reference 	= "G";
				$branch_name 			= "H";
				$statment_date			= "I";
				$type 					= "J";
				$enter_date 			= "K";
				$transaction_description= "L"; //Description
				$bank_reference 		= "M";
				$narrative				= "N"; //new

				$start_row 				= "2";

				$upload_path = "uploads/manage_bank_statment/myfile/";
				$excelFile = $upload_path.$filename;
				$i=1;
				if(file_exists($excelFile))
				{
					$this->load->library('excel');
					$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
					{
						$highestRow = $worksheet->getHighestRow();
						for ($row=$start_row; $row<=$highestRow; $row++)
						{
							$date1 = $worksheet->getCell($date.$row)->getValue();//a
							$account_no1 = $worksheet->getCell($account_no.$row)->getValue(); //b
							$branch_no1 = $worksheet->getCell($branch_no.$row)->getValue(); //c
							$beneficiary_remitter1 = $worksheet->getCell($beneficiary_remitter.$row)->getValue();//d
							$currency1 = $worksheet->getCell($currency.$row)->getValue();//e
							$amount1 = $worksheet->getCell($amount.$row)->getValue();//f
							$customer_reference1 = $worksheet->getCell($customer_reference.$row)->getValue(); //g
							$branch_name1 = $worksheet->getCell($branch_name.$row)->getValue(); //h
							$statment_date1 = $worksheet->getCell($statment_date.$row)->getValue(); //i
							$type1 = $worksheet->getCell($type.$row)->getValue();//j
							$enter_date1 = $worksheet->getCell($enter_date.$row)->getValue();//k
							$transaction_description1 = $worksheet->getCell($transaction_description.$row)->getValue();//l
							$bank_reference1 = $worksheet->getCell($bank_reference.$row)->getValue();//m
							$narrative1 = $worksheet->getCell($narrative.$row)->getValue(); //n
							
							//$date1 = DateTime::createFromFormat('d/m/Y', $date1)->format('Y-m-d');
							$date1 = DateTime::createFromFormat('d M Y', $date1)->format('Y-m-d');
							//$date1 = date('Y-m-d', strtotime($date1));
							//die();
							//change only for this
							$amount1 = str_replace(',', '', $amount1);							
							
							$dt = array(
								'date'=>$date1,
								'account_no'=>$account_no1,
								'branch_no'=>$branch_no1,
								'beneficiary_remitter'=>$beneficiary_remitter1,
								'currency'=>$currency1,
								'amount'=>$amount1,
								'customer_reference'=>$customer_reference1,
								'branch_name'=>$branch_name1,
								'statment_date'=>$statment_date1,
								'type'=>$type1,
								'enter_date'=>$enter_date1,
								'transaction_description'=>$transaction_description1,
								'bank_reference'=>$bank_reference1,
								'narrative'=>$narrative1,
								'formet'=>$formet,
							);
							$this->BankModel->insert_statment("tbl_statment", $dt);
						}
					}
				}
				redirect(base_url()."admin/$page_controllers/view2");
			}

		
			if($formet==3){
				$date 					= "A";  // value date
				$statment_date 			= "B";
				$currency 				= "C";
				$amount 				= "D";
				$beneficiary_remitter	= "E";
				$customer_reference 	= "F";
				$type 					= "G";
				$branch_no 				= "H";
				$branch_name 			= "I";
				$enter_date 			= "J";
				$bank_reference 		= "K";
				$transaction_description= "L"; //Description
				$narrative				= "M"; //new				
				$payment_details 		= "N";
				
				$start_row 				= "2";

				$upload_path = "uploads/manage_bank_statment/myfile/";
				$excelFile = $upload_path.$filename;
				$i=1;
				if(file_exists($excelFile))
				{
					$this->load->library('excel');
					$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
					{
						$highestRow = $worksheet->getHighestRow();
						for ($row=$start_row; $row<=$highestRow; $row++)
						{
							$date1 = $worksheet->getCell($date.$row)->getValue();//a
							$statment_date1 = $worksheet->getCell($statment_date.$row)->getValue(); //b
							$currency1 = $worksheet->getCell($currency.$row)->getValue();//c
							$amount1 = $worksheet->getCell($amount.$row)->getValue();//d
							$beneficiary_remitter1 = $worksheet->getCell($beneficiary_remitter.$row)->getValue();//e
							$customer_reference1 = $worksheet->getCell($customer_reference.$row)->getValue(); //f
							$type1 = $worksheet->getCell($type.$row)->getValue();//g
							$branch_no1 = $worksheet->getCell($branch_no.$row)->getValue(); //h
							$branch_name1 = $worksheet->getCell($branch_name.$row)->getValue(); //i
							$enter_date1 = $worksheet->getCell($enter_date.$row)->getValue();//j
							$bank_reference1 = $worksheet->getCell($bank_reference.$row)->getValue();//k
							$transaction_description1 = $worksheet->getCell($transaction_description.$row)->getValue();//l
							$narrative1 = $worksheet->getCell($narrative.$row)->getValue(); //m
							$payment_details1 = $worksheet->getCell($payment_details.$row)->getValue(); //n

							//$date1 = DateTime::createFromFormat('d/m/Y', $date1)->format('Y-m-d');
							$date1 = DateTime::createFromFormat('d M Y', $date1)->format('Y-m-d');
							//$date1 = date('Y-m-d', strtotime($date1));
							//die();
							//change only for this
							$amount1 = str_replace(',', '', $amount1);

							$account_no1 = $closing_ledger_balance1 = $calculated_balances1 = $iban_number1 = $iban_number1 = "";
							
							$dt = array(
								'account_no'=>$account_no1,
								'branch_no'=>$branch_no1,
								'statment_date'=>$statment_date1,
								'closing_ledger_balance'=>$closing_ledger_balance1,
								'calculated_balances'=>$calculated_balances1,
								'amount'=>$amount1,
								'enter_date'=>$enter_date1,
								'date'=>$date1,
								'bank_reference'=>$bank_reference1,
								'customer_reference'=>$customer_reference1,
								'narrative'=>$narrative1,
								'transaction_description'=>$transaction_description1,
								'iban_number'=>$iban_number1,
								'currency'=>$currency1,
								'beneficiary_remitter'=>$beneficiary_remitter1,
								'type'=>$type1,
								'branch_name'=>$branch_name1,
								'payment_details'=>$payment_details1,
								'formet'=>$formet,
							);
							$this->BankModel->insert_statment("tbl_statment", $dt);
						}
					}
				}
				redirect(base_url()."admin/$page_controllers/view3");
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
		}else{
			$date_range	= date('d-m-Y')."+to+".date('d-m-Y');
		}
		
		$data["date_range"] = $date_range;
		
		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);
	
		$start_date = $start_date->format('Y-m-d');
		$end_date 	= $end_date->format('Y-m-d');

		if(isset($_POST["checkbox-submit"])){
			$selectedCheckboxes = $_POST['checkbox'];

			// Print the IDs of selected checkbox
			foreach ($selectedCheckboxes as $upi_no) {
				//echo $upi_no;
				$where = array(
					'upi_no' => $upi_no,
				);
				$dt = array(
					'checkbox_done_status' => '1',
				);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
		
		if(isset($_POST["checkbox-delete"])){
			$upi_no = $_POST['upi_no'];
			$where = array(
				'upi_no' => $upi_no,
			);
			$dt = array(
				'checkbox_done_status' => '0',
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}

		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.invoice_text as invoice_number,p.id as pid from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date'");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}
	
	public function view1()
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

		$start_date = $end_date = date('d-m-Y');
		if(isset($_GET["date-range"])){
			$date_range = $_GET["date-range"];
	
			// `to` ke aas paas se string ko tukdon mein vibhajit karen
			$date_parts = explode(" to ", $date_range);
	
			// Start date aur end date ko extract karen
			$start_date = $date_parts[0];
			$end_date 	= $date_parts[1];
		}else{
			$date_range	= date('d-m-Y')."+to+".date('d-m-Y');
		}
		
		$data["date_range"] = $date_range;

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);
	
		$start_date = $start_date->format('Y-m-d');
		$end_date 	= $end_date->format('Y-m-d');

		if(isset($_POST["checkbox-submit"])){
			$selectedCheckboxes = $_POST['checkbox'];

			// Print the IDs of selected checkbox
			foreach ($selectedCheckboxes as $upi_no) {
				//echo $upi_no;
				$where = array(
					'upi_no' => $upi_no,
				);
				$dt = array(
					'checkbox_done_status' => '1',
				);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
		
		if(isset($_POST["checkbox-delete"])){
			$upi_no = $_POST['upi_no'];
			$where = array(
				'upi_no' => $upi_no,
			);
			$dt = array(
				'checkbox_done_status' => '0',
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}

		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.final_find_by as done_find_by,p.status as done_status,p.download_easysol as download_easysol,p.checkbox_done_status as checkbox_done_status,p.id as pid from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where formet=1 and s.date BETWEEN '$start_date' AND '$end_date'");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view1",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}
	
	public function view2()
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

		$start_date = $end_date = date('d-m-Y');
		if(isset($_GET["date-range"])){
			$date_range = $_GET["date-range"];
	
			// `to` ke aas paas se string ko tukdon mein vibhajit karen
			$date_parts = explode(" to ", $date_range);
	
			// Start date aur end date ko extract karen
			$start_date = $date_parts[0];
			$end_date 	= $date_parts[1];
		}else{
			$date_range	= date('d-m-Y')."+to+".date('d-m-Y');
		}
		
		$data["date_range"] = $date_range;

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);
	
		$start_date = $start_date->format('Y-m-d');
		$end_date 	= $end_date->format('Y-m-d');

		if(isset($_POST["checkbox-submit"])){
			$selectedCheckboxes = $_POST['checkbox'];

			// Print the IDs of selected checkbox
			foreach ($selectedCheckboxes as $upi_no) {
				//echo $upi_no;
				$where = array(
					'upi_no' => $upi_no,
				);
				$dt = array(
					'checkbox_done_status' => '1',
				);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
		
		if(isset($_POST["checkbox-delete"])){
			$upi_no = $_POST['upi_no'];
			$where = array(
				'upi_no' => $upi_no,
			);
			$dt = array(
				'checkbox_done_status' => '0',
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
		
		//echo "SELECT s.*,p.final_chemist as chemist_id,p.final_invoice as done_invoice,p.final_find_by as done_find_by,p.status as done_status,p.download_easysol as download_easysol,p.checkbox_done_status as checkbox_done_status,p.id as pid from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where formet=2 and s.date BETWEEN '$start_date' AND '$end_date'";
		
		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.final_find_by as done_find_by,p.status as done_status,p.download_easysol as download_easysol,p.checkbox_done_status as checkbox_done_status,p.id as pid from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where formet=2 and s.date BETWEEN '$start_date' AND '$end_date'");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view2",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	
	public function view3()
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

		$start_date = $end_date = date('d-m-Y');
		if(isset($_GET["date-range"])){
			$date_range = $_GET["date-range"];
	
			// `to` ke aas paas se string ko tukdon mein vibhajit karen
			$date_parts = explode(" to ", $date_range);
	
			// Start date aur end date ko extract karen
			$start_date = $date_parts[0];
			$end_date 	= $date_parts[1];
		}else{
			$date_range	= date('d-m-Y')."+to+".date('d-m-Y');
		}
		
		$data["date_range"] = $date_range;

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);
	
		$start_date = $start_date->format('Y-m-d');
		$end_date 	= $end_date->format('Y-m-d');

		if(isset($_POST["checkbox-submit"])){
			$selectedCheckboxes = $_POST['checkbox'];

			// Print the IDs of selected checkbox
			foreach ($selectedCheckboxes as $upi_no) {
				//echo $upi_no;
				$where = array(
					'upi_no' => $upi_no,
				);
				$dt = array(
					'checkbox_done_status' => '1',
				);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
		
		if(isset($_POST["checkbox-delete"])){
			$upi_no = $_POST['upi_no'];
			$where = array(
				'upi_no' => $upi_no,
			);
			$dt = array(
				'checkbox_done_status' => '0',
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
		
		//echo "SELECT s.*,p.final_chemist as chemist_id,p.final_invoice as done_invoice,p.final_find_by as done_find_by,p.status as done_status,p.download_easysol as download_easysol,p.checkbox_done_status as checkbox_done_status,p.id as pid from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where formet=2 and s.date BETWEEN '$start_date' AND '$end_date'";
		
		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.final_find_by as done_find_by,p.status as done_status,p.download_easysol as download_easysol,p.checkbox_done_status as checkbox_done_status,p.id as pid from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where formet=3 and s.date BETWEEN '$start_date' AND '$end_date'");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view3",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	
	public function statment_excel_file()
	{
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
		
		$this->BankModel->statment_excel_file("direct_download",$start_date,$end_date);
	}
	
	public function statment_excel_file1()
	{
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
		
		$this->BankModel->statment_excel_file1("direct_download",$start_date,$end_date);
	}
	
	public function statment_excel_file2()
	{
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
		
		$this->BankModel->statment_excel_file2("direct_download",$start_date,$end_date);
	}
	
	public function statment_excel_file3()
	{
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
		
		$this->BankModel->statment_excel_file3("direct_download",$start_date,$end_date);
	}
}