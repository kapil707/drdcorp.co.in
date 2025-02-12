<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeCorporate extends CI_Controller
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
				
				$code = $record['code'];
				$compcode = $record['compcode'];

				$insert_time = date('Y-m-d,H:i');

				$dt = array(
					'code' => $code,
					'compcode' => $compcode,
					'insert_time' => $insert_time,
				);

				if (!empty($code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->CheckRecoreds("tbl_corporate_report", array('code' => $code));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('code' => $code);
						$this->Scheme_Model->edit_fun("tbl_corporate_report", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_corporate_report", $dt);
					}
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