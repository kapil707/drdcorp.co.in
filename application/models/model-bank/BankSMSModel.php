<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankSMSModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_sms(){

		//echo " get_sms";
		$date = date('Y-m-d');

		$result = $this->BankModel->select_query("select * from tbl_sms where status='0' limit 250");
		$result = $result->result();
		foreach($result as $row){
			echo $sms_text = $message_body = $row->message_body;
			$message_body = str_replace(",","",$message_body);
			
			$pattern = '/INR (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$amount = $matches[1];
			} else {
				$amount = "Amount not found";
			}

			$pattern = '/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/';
			if (preg_match($pattern, $message_body, $matches)) {
				$getdate = $matches[1];
			} else {
				$getdate = "Date not found";
			}

			// Regex pattern to extract time
			$pattern = "/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/";

			// Extracting time using preg_match
			if (preg_match($pattern, $message_body, $matches)) {
				$gettime = $matches[0];
			} else {
				$gettime = "Time not found.";
			}

			$pattern = '/received from (\S+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$from_text = $matches[1];
			} else {
				$from_text = "Received from information not found";
			}

			$pattern = '/UPI Ref No\. (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$upi_no = $matches[1];
			} else {
				$upi_no = "UPI reference number not found";
			}

			$pattern = '/OrderId (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$orderid = $matches[1];
			} else {
				$orderid = "orderid not found";
			}
			echo "<br>".$from_text."<br>";
			$statment_id = $row->id;
			
			$row_new = $this->BankModel->select_query("select id from tbl_bank_processing where upi_no='$upi_no'");
			$row_new = $row_new->row();

			$amount = str_replace([",", ".00"], "", $amount);
			$amount = trim($amount);
			
			if(empty($row_new->id) && $from_text!="Remitter" && $from_text != "Received from information not found"){
				$dt = array(
					'amount'=>$amount,
					'date'=>$getdate,
					'time'=>$gettime,
					'from_text'=>$from_text,
					'upi_no'=>$upi_no,
					'orderid'=>$orderid,
					'statment_id'=>$statment_id,
					'from_sms'=>1,
					'sms_text'=>$sms_text,
				);
				try{
					$this->BankModel->insert_fun("tbl_bank_processing", $dt);
				} catch (Exception $e) {
					// Catch error
					echo 'duplicate records Error: ' . $email->ErrorInfo;
				}
			}

			/****************************************************** */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>'1',
				'amount'=>$amount,
				'upi_no'=>$upi_no,
			);
			$this->BankModel->edit_fun("tbl_sms", $dt,$where);
		}
	}

	public function get_sms_again(){
		
		$result = $this->BankModel->select_query("select * from tbl_sms WHERE `upi_no`='UPI reference number not found' and status=1 limit 10");
		$result = $result->result();
		foreach($result as $row){
			$sms_text = $message_body = $row->message_body;
			$message_body = str_replace(",","",$message_body);
			
			$pattern = '/INR (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$amount = $matches[1];
			} else {
				$amount = "Amount not found";
			}

			//new added by 2025-05-19
			if($amount=="Amount not found")
			{
				$pattern = '/Rs\.?([0-9,]+)/';
				if (preg_match($pattern, $message_body, $matches)) {
					$amount = $matches[1];
				} else {
					$amount = "Amount not found";
				}
			}

			$pattern = '/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/';
			if (preg_match($pattern, $message_body, $matches)) {
				$getdate = $matches[1];
			} else {
				$getdate = "Date not found";
			}

			// Regex pattern to extract time
			$pattern = "/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/";

			// Extracting time using preg_match
			if (preg_match($pattern, $message_body, $matches)) {
				$gettime = $matches[0];
			} else {
				$gettime = "Time not found.";
			}

			$pattern = '/received from (\S+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$from_text = $matches[1];
			} else {
				$from_text = "Received from information not found";
			}

			$pattern = '/UPI Ref No\. (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$upi_no = $matches[1];
			} else {
				$upi_no = "UPI reference number not found";
			}

			$pattern = '/OrderId (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$orderid = $matches[1];
			} else {
				$orderid = "orderid not found";
			}

			//new added by 2025-05-19
			if($from_text=="Remitter")
			{
				$pattern = '/(CITIN[0-9]+)/';
				if (preg_match($pattern, $message_body, $matches)) {
					$from_text = $upi_no = $orderid = $matches[1];
				} else {
					$from_text = "n/a";
				}
			}

			if($from_text=="n/a")
			{
				$pattern = '/bearing\s+(\d+)/';
				if (preg_match($pattern, $message_body, $matches)) {
					$from_text = $upi_no = $orderid = $matches[1];
				} else {
					$from_text = "n/a";
				}
			}

			$status 		= 1;
			$from_chemist 	= "";
			$chemist_id 	= "";
			$newrow = $this->BankModel->select_query("SELECT tbl_bank_processing.from_text,tbl_bank_processing.final_chemist FROM `tbl_statment` left join tbl_bank_processing on tbl_statment.customer_reference=tbl_bank_processing.upi_no WHERE tbl_statment.narrative like '%$upi_no%'");
			$newrow = $newrow->row();
			if(!empty($newrow)){
				$from_chemist 	= $newrow->from_text;
				$chemist_id 	= $newrow->final_chemist;
				if(!empty($chemist_id)){
					$status = 2; // agar chemist mil jata ha to 
				}
			}
			
			if($amount=="Amount not found" && $upi_no=="UPI reference number not found")
			{
				$status = 3; //jab kuch be nahi mila tab
			}

			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>$status,
				'amount'=>$amount,
				'upi_no'=>$upi_no,
				'set_chemist'=>$chemist_id,
			);
			$this->BankModel->edit_fun("tbl_sms", $dt,$where);

			echo "<br>Amount : ".$amount." From : ".$from_text." upi_no : ".$upi_no." orderid : ".$orderid." from_chemist : ".$from_chemist." chemist_id : ".$chemist_id;
		}
	}
}	