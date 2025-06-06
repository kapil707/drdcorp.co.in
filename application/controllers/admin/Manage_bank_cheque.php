<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Manage_bank_cheque extends CI_Controller {
	public $Page_title = "Manage Bank Cheque";
	public $Page_name  = "manage_bank_cheque";
	public $Page_view  = "manage_bank_cheque";
	public $Page_menu  = "manage_bank_cheque";
	public $page_controllers = "manage_bank_cheque";
	public $Page_tbl   = "tbl_cheque";
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

			
			$party_code 			= "A";
			$party_name				= "B";
			$date 					= "C";
			$bank_name 				= "D";
			$ifsc_code 				= "E";
			$amount_in_words 		= "F";
			$amount_in_digits 		= "G";
			$reciever 				= "H";
			$account_number 		= "I";
			$cheque_number 			= "J";

			$start_row 				= "2";

			$upload_path = "uploads/$page_controllers/myfile/";
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
						$party_code1 = $worksheet->getCell($party_code.$row)->getValue();
						$party_name1 = $worksheet->getCell($party_name.$row)->getValue();
						$date1 = $worksheet->getCell($date.$row)->getValue();
						$bank_name1 = $worksheet->getCell($bank_name.$row)->getValue();
						$ifsc_code1 = $worksheet->getCell($ifsc_code.$row)->getValue();
						$amount_in_words1 = $worksheet->getCell($amount_in_words.$row)->getValue();
						$amount_in_digits1 = $worksheet->getCell($amount_in_digits.$row)->getValue();
						$reciever1 = $worksheet->getCell($reciever.$row)->getValue();
						$account_number1 = $worksheet->getCell($account_number.$row)->getValue();
						$cheque_number1 = $worksheet->getCell($cheque_number.$row)->getValue();

						$date1 = date('Y-m-d', strtotime($date1));

						$currency1 = $beneficiary_remitter1 = $type1 = $branch_name1 = $payment_details1 = "";
						$dt = array(
							'party_code'=>$party_code1,
							'party_name'=>$party_name1,
							'date'=>$date1,
							'bank_name'=>$bank_name1,
							'ifsc_code'=>$ifsc_code1,
							'amount_in_words'=>$amount_in_words1,
							'amount_in_digits'=>$amount_in_digits1,
							'reciever'=>$reciever1,
							'account_number'=>$account_number1,
							'cheque_number'=>$cheque_number1,
						);
						/*$row1 = $this->BankModel->select_query("select id from tbl_statment where customer_reference='$customer_reference'");
						$row1 = $row1->row();
						if(empty($row1->id)){*/
							$this->BankModel->insert_fun($Page_tbl, $dt);
						//}
					}
				//}
			}
			redirect(base_url()."admin/$page_controllers/view");
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


		$query = $this->BankModel->select_query("SELECT * from $Page_tbl where date BETWEEN '$start_date' AND '$end_date' order by id asc");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	function convertToYmd($input_date) {
		$timestamp = strtotime($input_date);
		if ($timestamp) {
			return date('Y-m-d', $timestamp);
		} else {
			return $input_date; // invalid date
		}
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
		
		$this->BankModel->statment_excel_file1("direct_download",$start_date,$end_date);
	}
}