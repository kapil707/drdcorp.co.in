<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExePendingOrderReport extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		//Load model
		$this->load->model("model-drdcorp/PendingOrderReport");
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

			$compcode = $acno = "";
			//$acno_array = array();
			foreach ($data as $record) {
				//$acno_array[] = $record['acno'];
				
				$uname 		= $record['uname'];
				$uemail 	= $record['uemail'];
				$umobile 	= $record['umobile'];
				$acno 		= $record['acno'];
				$compcode 	= $record['compcode'];
				$company_full_name = $record['company_full_name'];

				$insert_time = date('Y-m-d,H:i');

				$email_send_time = date("YmdHi", strtotime('+5 minutes', time()));

				$dt = array(
					'uname' => $uname,
					'uemail' => $uemail,
					'umobile' => $umobile,
					'acno' => $acno,
					'compcode' => $compcode,
					'company_full_name' => $company_full_name,
					'email_send_time' => $email_send_time,
					'insert_time' => $insert_time,
				);

				if (!empty($acno)) {
					// Check karo agar record already exist karta hai
					//$existing_record = $this->CorporateReport->CheckRecoreds("tbl_corporate_report", array('code' => $code));
			
					/*if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('code' => $code);
						$this->CorporateReport->UpdateRecoreds("tbl_corporate_report", $dt, $where);
					} else {*/
						// Agar record exist nahi karta hai to insert karo
						$this->PendingOrderReport->InsertRecoreds("tbl_pending_order_report", $dt);
					//}
				}
			}
			//$commaSeparatedString = implode(',', $acno_array);
			// Response dena
			if(!empty($acno) && !empty($compcode)){
				echo json_encode(["acno" => $acno,"compcode" => $compcode,"status" => "success", "message" => "Data received successfully"]);
			}else{
				echo json_encode(["code" => "error","status" => "error", "message" => "Invalid data"]);
			}
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}