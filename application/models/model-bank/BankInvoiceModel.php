<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankInvoiceModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_invoice_find_user(){

		/*$this->invoice_find_in_total('0','T102','2663');
		die();*/

		$start_date = date('Y-m-d', strtotime('-10 day'));
		$end_date = date('Y-m-d');

		$working = 0;
		if($working == 0){
			$result = $this->BankModel->select_query("select id,from_text_find_chemist,amount from tbl_bank_processing where invoice_id='' and from_text_find_chemist!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 500");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->from_text_find_chemist;
				$amount 	= $row->amount;
				$working = $this->invoice_find($id,$chemist_id,$amount);
			}
		}

		if($working == 0){
			$result = $this->BankModel->select_query("select id,from_text_find_chemist,amount from tbl_bank_processing where invoice_id='' and from_text_find_chemist!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 500");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->from_text_find_chemist;
				$amount 	= $row->amount;
				$working = $this->invoice_find_in_total($id,$chemist_id,$amount);
			}
		}

		//find from recommended
		if($working == 0){
			$result = $this->BankModel->select_query("select id,whatsapp_recommended,amount from tbl_bank_processing where invoice_id='' and from_text_find_chemist!='' and whatsapp_recommended!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 100");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->whatsapp_recommended;
				$amount 	= $row->amount;
				$working 	= $this->recommended_invoice_find($id,$chemist_id,$amount);
			}
		}

		if($working == 0){
			$result = $this->BankModel->select_query("select id,whatsapp_recommended,amount from tbl_bank_processing where invoice_id='' and whatsapp_recommended!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 100");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->whatsapp_recommended;
				$amount 	= $row->amount;
				$working 	= $this->recommended_invoice_find($id,$chemist_id,$amount);
			}
		}
	}

	public function invoice_find($id,$chemist_id,$amount){

		$status = 0;
		$start_date = date('Y-m-d', strtotime('-10 day'));
		$end_date = date('Y-m-d');

		$parts = explode(" || ", $chemist_id);
		foreach($parts as $chemist_id_new) {

			$result = $this->BankModel->select_query("SELECT id,gstvno,amt,chemist_id FROM `tbl_invoice` WHERE `chemist_id`='$chemist_id_new' and REPLACE(TRIM(amt), '.00', '')='$amount' and date BETWEEN '$start_date' and '$end_date'");
			$result = $result->result();
			foreach($result as $row) {
				if($row->id){
					$status = 1;
					$amount = str_replace(".00", "", $row->amt);

					$invoice_id = $row->id;
					$invoice_chemist = $row->chemist_id;
					$invoice_text = "GstvNo:".$row->gstvno." Amount:".$amount."/-";

					$where = array(
						'id' => $id,
					);
					$dt = array(
						'process_status'=>3,
						'invoice_id'=>$invoice_id,
						'invoice_chemist'=>$invoice_chemist,
						'invoice_text'=>$invoice_text,
					);
					print_r($dt);
					$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
				}
			}
		}
		return $status;
	}

	public function invoice_find_in_total($id,$chemist_id,$amount){
		
		$status = 0;
		$start_date = date('Y-m-d', strtotime('-10 day'));
		$end_date = date('Y-m-d');

		$json_invoice_id = [];
		$json_invoice_text = [];

		$targetValue = $amount;
		$found = false;
		$selectedValues = [];

		$parts = explode(" || ", $chemist_id);
		foreach($parts as $chemist_id_new) {

			$result = $this->BankModel->select_query("SELECT GROUP_CONCAT(id) AS invoice_id, SUM(amt) AS total_invoice_amount,chemist_id FROM tbl_invoice WHERE chemist_id = '$chemist_id_new' and date BETWEEN '$start_date' and '$end_date' HAVING total_invoice_amount = '$amount'");
			$myrow = $result->row();
			if(!empty($romyroww)){
				
				$mychemist  = $myrow->chemist_id;
				$invoice_id = $myrow->invoice_id;
				$result = $this->BankModel->select_query("SELECT id,gstvno,amt FROM `tbl_invoice` WHERE id in($invoice_id)");
				$result = $result->result();
				foreach($result as $row) {
					$amount = str_replace(".00", "", $row->amt);
					$json_invoice_id[]   = $row->id;
					$json_invoice_text[] = "GstvNo:".$row->gstvno." Amount:".$amount."/-";
				}

			} else {
				$resultArray = [];
				$result = $this->BankModel->select_query("SELECT * FROM `tbl_invoice` WHERE `chemist_id`='$chemist_id_new' and date BETWEEN '$start_date' and '$end_date'");
				$result = $result->result();
				foreach($result as $row) {
					$amount = str_replace(".00", "", $row->amt);
					$resultArray[] = [
						'id' => $row->id,
						'chemist_id' => $row->chemist_id,
						'gstvno' => $row->gstvno,
						'amount' => $amount
					];		
				}

				for ($i = 0; $i < count($resultArray); $i++) {
					for ($j = $i + 1; $j < count($resultArray); $j++) {
						if ($resultArray[$i]['amount'] + $resultArray[$j]['amount'] == $targetValue) {
							$selectedValues[] = [$resultArray[$i], $resultArray[$j]];
							$found = true;
							break 2; // Exit both loops
						}
					}
				}

				if ($found) {
					for ($i = 0; $i < count($selectedValues[0]); $i++) {
						$rt = $selectedValues[0][$i];
						$json_invoice_id[] = $rt['id'];
						$json_invoice_text[] = "GstvNo:".$rt['gstvno']." Amount:".$rt['amount']."/-";
						$mychemist  = $rt['chemist_id'];
					}
				}
			}

			if(!empty($json_invoice_id)){

				$status = 1;
				$invoice_id = implode(',', $json_invoice_id);
				$invoice_text = implode('||', $json_invoice_text);
				$invoice_chemist = $mychemist;

				$where = array(
					'id' => $id,
				);
				$dt = array(
					'process_status'=>3,
					'invoice_id'=>$invoice_id,
					'invoice_chemist'=>$invoice_chemist,
					'invoice_text'=>$invoice_text,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
		return $status;
	}

	public function recommended_invoice_find($id,$chemist_id,$amount){
		
		$status = 0;

		$start_date = date('Y-m-d', strtotime('-10 day'));
		$end_date = date('Y-m-d');

		$parts = explode(" || ", $chemist_id);
		foreach($parts as $chemist_id_new) {

			$result = $this->BankModel->select_query("SELECT id,gstvno,chemist_id FROM `tbl_invoice` WHERE `chemist_id`='$chemist_id_new' and REPLACE(TRIM(amt), '.00', '')='$amount' and date BETWEEN '$start_date' and '$end_date'");
			$result = $result->result();
			foreach($result as $row) {
				if($row->id){
					$status = 1;

					$invoice_id = $row->id;
					$invoice_recommended = $row->chemist_id;
					$invoice_text = $row->gstvno." Amount.".$amount;

					$where = array(
						'id' => $id,
					);
					$dt = array(
						'process_status'=>3,
						'invoice_id'=>$invoice_id,
						'invoice_text'=>$invoice_text,
						'invoice_recommended'=>$invoice_recommended,
					);
					print_r($dt);
					$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
				}
			}
		}
		return $status;
	}

	public function get_invoice_or_insert_drdcorp(){

		//Created a GET API
		$url = "https://drdweb.co.in/bank_api/Api01/get_invoice_api";

		$parmiter = '';
		$curl = curl_init();
		
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL =>$url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 0,
				CURLOPT_TIMEOUT => 300,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_POSTFIELDS => $parmiter,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				),
			)
		);

		$response = curl_exec($curl);
		//print_r($response);
		curl_close($curl);

		$data1 = json_decode($response, true);

		print_r($data1);
		die();
		if (isset($data1['items'])) {
			foreach ($data1['items'] as $message) {
				$message_id = isset($message['message_id']) ? $message['message_id'] : "Body not found";
				
				$body = isset($message['body']) ? $message['body'] : "Body not found";
				$date = isset($message['date']) ? $message['date'] : "Date not found";
				$extracted_text = isset($message['extracted_text']) ? $message['extracted_text'] : "extracted_text not found";
				$from_number = isset($message['from_number']) ? $message['from_number'] : "Date not found";
				$ist_timestamp = isset($message['ist_timestamp']) ? $message['ist_timestamp'] : "timestamp not found";
				$screenshot_image = isset($message['screenshot_image']) ? $message['screenshot_image'] : "screenshot_image not found";
				$sender_name_place = isset($message['sender_name_place']) ? $message['sender_name_place'] : "sender_name_place not found";
				$timestamp = isset($message['timestamp']) ? $message['timestamp'] : "timestamp not found";
				$vision_text = isset($message['vision_text']) ? $message['vision_text'] : "vision_text not found";	
				
				$reply_id = isset($message["quoted_text"]) ? $message["quoted_text"] : "0";

				//$extracted_text = str_replace("\n", "<br>", $extracted_text);
				//$vision_text = str_replace("\n", "<br>", $vision_text);

				//$date = date('Y-m-d H:i:s', strtotime($date));
				// Convert date
				$date = new DateTime($date);
				// Format as "YYYY-MM-DD"
				$date = $date->format("Y-m-d");

				$dt = array(
					'message_id' => $message_id,
					'body' => $body,
					'date' => $date,
					'extracted_text' => $extracted_text,
					'from_number' => $from_number,
					'ist_timestamp' => $ist_timestamp,
					'screenshot_image' => $screenshot_image,
					'sender_name_place' => $sender_name_place,
					'timestamp' => $timestamp,
					'vision_text' => $vision_text,
					'reply_id'=>$reply_id,
				);

				if (!empty($message_id)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->BankModel->select_row("tbl_whatsapp_message", array('message_id' => $message_id));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('message_id' => $message_id);
						$this->BankModel->edit_fun("tbl_whatsapp_message", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->BankModel->insert_fun("tbl_whatsapp_message", $dt);
					}
				}
			}
		}
	}
}	