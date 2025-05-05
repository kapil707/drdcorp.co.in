<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeCorporateReport extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		//Load model
		$this->load->model("model-drdcorp/CorporateReport");
	}
	public function upload()
	{
		// Data ko read karna (input stream se)
		$inputData = file_get_contents("php://input");

		// JSON data ko PHP array me convert karna
		$data = json_decode($inputData, true);

		
		// Data ko check karna
		if ($data && is_array($data)) {
			// Aap yaha data ko process kar sakte hain, jaise ki database me save karna, logging karna, etc.
			
			//print_r($data);

			// Example: Data ko print karna (ya log karna)
			//file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

			$code_array = array();
			foreach ($data as $record) {
				$code_array[] = $record['code'];
				
				$report_type = $record['report_type'];
				$code = $record['code'];
				$compcode = $record['compcode'];
				$division = $record['division'];
				$company_name = $record['company_name'];
				$name = $record['name'];
				$email = $record['email'];
				$file1 = $record['file1'];
				$file2 = $record['file2'];
				$file3 = $record['file3'];
				$file_attachment = $record['file_attachment'];
				$file_name = $record['file_name'];
				$from_date = $record['from_date'];
				$to_date = $record['to_date'];
				$folder_date = $record['folder_date'];

				$insert_time = date('Y-m-d,H:i');

				$dt = array(
					'report_type' => $report_type,
					'code' => $code,
					'compcode' => $compcode,
					'division' => $division,
					'company_name' => $company_name,
					'name' => $name,
					'email' => $email,
					'file1' => $file1,
					'file2' => $file2,
					'file3' => $file3,
					'file3' => $file3,
					'file_attachment' => $file_attachment,
					'from_date' => $from_date,
					'to_date' => $to_date,
					'folder_date' => $folder_date,
					'insert_time' => $insert_time,
				);

				if (!empty($code)) {
					// Check karo agar record already exist karta hai
					//$existing_record = $this->CorporateReport->CheckRecoreds("tbl_corporate_report", array('code' => $code));
			
					/*if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('code' => $code);
						$this->CorporateReport->UpdateRecoreds("tbl_corporate_report", $dt, $where);
					} else {*/
						// Agar record exist nahi karta hai to insert karo
						$this->CorporateReport->InsertRecoreds("tbl_corporate_report", $dt);
					//}
				}
			}
			$commaSeparatedString = implode(',', $code_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}