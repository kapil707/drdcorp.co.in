<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankWhatsAppModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_whatsapp_or_insert_rishav(){

		$start_date = date('d-m-Y', strtotime('-1 day'));
		$end_date = date('d-m-Y');

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);

		$start_date = $start_date->format('d/m/Y');
		$end_date 	= $end_date->format('d/m/Y');

		$sender_name_place = "Online%20Details";

		//Created a GET API
		$url = "http://192.46.214.43:5000/get_messages_by_status?start_date=$start_date&end_date=$end_date&group=$sender_name_place&status=true";

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
					'Content-Type: application/json',
					'Authorization: Bearer THIRTEENWOLVESWENTHUNTINGBUT10CAMEBACK'
				),
			)
		);

		$response = curl_exec($curl);
		//print_r($response);
		curl_close($curl);

		$data1 = json_decode($response, true);

		if (isset($data1['messages'])) {
			foreach ($data1['messages'] as $message) {
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
				
				// Decode "quoted_text" JSON
				$quoted = json_decode($message["quoted_text"], true);
				// Extract "wid"
				$reply_id = isset($quoted['wid']) ? $quoted['wid'] : "0";

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

	public function whatsapp_find_upi_amount(){

		//$result = $this->BankModel->select_query("SELECT id,vision_text FROM `tbl_whatsapp_message` where id='31435'");
		//SELECT id,vision_text FROM `tbl_whatsapp_message` where upi_no='' and vision_text!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 100
		$start_date = date('Y-m-d', strtotime('-3 day'));
		$end_date = date('Y-m-d');
		//"SELECT id,vision_text FROM `tbl_whatsapp_message` where status='0' and date BETWEEN '$start_date' and '$end_date' limit 100"

		$result = $this->BankModel->select_query("SELECT id,vision_text FROM `tbl_whatsapp_message` where status='0' and date BETWEEN '$start_date' and '$end_date' limit 100");
		$result = $result->result();
		foreach($result as $row) {

			$text = trim($row->vision_text);

			$upi_no = "";
			$amount = "0.0";
			//********amount********** */
			// Regular Expression to extract amount.
			preg_match('/[₹\?]([\d,]+)/', $text, $matches);
			// Check if match is found
			if (!empty($matches[1])) {
				$amount = $matches[1];
			}
			if($amount == "0.0"){
				preg_match('/[₹\?]([\d,]+(?:\.\d{1,2})?)/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/Amount:\s*([\d,]+)/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/\*\*Transfer Amount:\*\* ([\d,]+\.\d{2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/\*\*Transfer Amount:\*\* ([\d,]+\.\d{2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match_all('/\?[\s]*([\d,.]+)/', $text, $matches);

				if (!empty($matches[1])) {
					$amount = !empty($matches[1][0]) ? $matches[1][0] : $matches[1][1] ?? '0.0';
				}
			}

			if($amount == "0.0"){
				preg_match('/Rs ([\d,]+\.?\d{0,2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}

			if($amount == "0.0"){
				preg_match('/INR ([\d,]+\.?\d{0,2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}

			if($amount == "0.0"){
				preg_match('/\bRs\.\s?(\d{1,3}(?:,\d{3})*\.\d{2})\b/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}

			if($amount == "0.0"){
				preg_match('/\b(\d{1,3}(?:,\d{3})*\.\d{2})\b/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}

			$type = 0;
			/************************************************** */
			// Regular Expression to extract UTR No.
			preg_match('/Reference No\. \(UTR No\.\/RRN\): (\S+)/', $text, $matches);
			// Check if match is found
			if (!empty($matches[1])) {
				$upi_no = $matches[1];
				$type = 1;
				//echo "UTR Number: " . $matches[1]; // Output: KKBKH25070930804
			}

			if(empty($upi_no)){
				preg_match('/UTR:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 2;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI transaction ID:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 3;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/Transaction ID:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 4;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref\. No:\s*([\d\s]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 5;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI txn id:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 6;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref ID:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 7;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI transaction ID\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 8;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref\. No:\s*([\d\s]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 9;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				// Regex se UPI Reference Number extract karna
				preg_match('/\*\*UPI Ref\. No:\*\*\s*([\d\s]+)/', $text, $matches);

				if (!empty($matches[1])) {
					$upi_no = preg_replace('/\s+/', '', $matches[1]); // Space remove karna
					$type = 10;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se UPI Reference Number extract karna
				preg_match('/\*\*\s*Reference No\. \(UTR No\.\/RRN\):\s*\*\*\s*(\w+\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 11;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\b\d{12}\b/', $text, $matches);
				if (!empty($matches[0])) {
					$upi_no = $matches[0];
					$type = 12;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\*\*Transaction ID:\*\*\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 13;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Transaction ID\s*\n*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 14;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\*\*Reference Number:\*\*\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 15;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Reference\s*Number\s*([A-Z0-9]+)/i', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 16;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/UTR:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 17;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Reference Number:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 18;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\*\*Transaction ID\*\*\s*(\S+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 19;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			$amount = str_replace([",", ".00"], "", $amount);
			$amount = trim($amount);

			$upi_no = trim($upi_no);
			echo $row->id."-".$amount."-".$upi_no."-".$type;
			echo "<br>";

			$where = array(
				'id' => $row->id,
			);
			$dt = array(
				'upi_no'=>$upi_no,
				'amount'=>$amount,
				'status'=>1
			);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			/*********************************************************** */
		}
	}

	public function whatsapp_insert_in_processing(){

		$result = $this->BankModel->select_query("SELECT p.id, wm.id AS whatsapp_id, wm.body AS body, wm.vision_text, wm.timestamp, wm.from_number, p.from_text_find_chemist FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.upi_no = wm.upi_no AND wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) WHERE p.whatsapp_id = '' ORDER BY RAND() LIMIT 25");
		$result = $result->result();
		foreach($result as $row) {

			$id = $row->id;
			$whatsapp_id = trim($row->whatsapp_id);
			$whatsapp_body = trim($row->body);
			$whatsapp_chemist = trim($row->body);
			$from_number = $row->from_number;
			$from_text_find_chemist = $row->from_text_find_chemist;
			$timestamp = date('Y-m-d H:i:s', $row->timestamp);

			$from_text_find_chemist = str_replace("/", " || ",$from_text_find_chemist);
			$parts = explode(" || ", $from_text_find_chemist);
			foreach($parts as $from_text_find_chemist_new) {
				if(!empty($whatsapp_chemist)  && !empty($from_text_find_chemist_new)){
					//agar body m chemist id nahi aa rahi ha to next say find karta ha yha
					if($whatsapp_chemist!=$from_text_find_chemist_new){
						$whatsapp_chemist = "";
						echo "xx1";
					}
					if(empty($whatsapp_chemist)){
						//agar pura naam milay to he next prcess karta ha
						if (strpos($whatsapp_body, $from_text_find_chemist_new) !== false) {
							echo $whatsapp_chemist = $from_text_find_chemist_new;
							echo "xx2";
						} 
					}
				}

				if(empty($whatsapp_chemist)){
					$row1 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body='$from_text_find_chemist_new' LIMIT 0, 25");
					$row1 = $row1->row();
					if(!empty($row1)){
						$whatsapp_chemist = trim($row1->body);
						echo "xx3";
					}
				}

				if(empty($whatsapp_chemist)){
					$row1 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and REPLACE(TRIM(body), ' ', '')='$from_text_find_chemist_new' LIMIT 0, 25");
					$row1 = $row1->row();
					if(!empty($row1)){
						$whatsapp_chemist = trim($row1->body);
						$whatsapp_chemist = str_replace(" ","",$whatsapp_chemist);
						echo "xx4";
					}
				}
				
				if(empty($whatsapp_chemist)){
					$row1 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' LIMIT 0, 25");
					$row1 = $row1->row();
					if(!empty($row1)){
						$text = trim($row1->body);
						if(!empty($text) && !empty($from_text_find_chemist_new)){
							//agar pura naam milay to he next prcess karta ha
							if (strpos($text, $from_text_find_chemist_new) !== false) {
								$whatsapp_chemist = $from_text_find_chemist_new;
								echo "xx5";
							} 
						}
					}
				}

				if(empty($whatsapp_chemist)){
					$row1 = $this->BankModel->select_query("SELECT (SELECT body FROM tbl_whatsapp_message WHERE from_number = wm.from_number AND REPLACE(TRIM(body), ' ', '') = '$from_text_find_chemist_new' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB(FROM_UNIXTIME(wm.timestamp), INTERVAL 7 MINUTE) AND DATE_ADD(FROM_UNIXTIME(wm.timestamp), INTERVAL 7 MINUTE) LIMIT 1) AS text FROM tbl_whatsapp_message AS wm WHERE wm.id = $whatsapp_id");
					$row1 = $row1->row();
					if(!empty($row1)){
						$text = trim($row1->text);
						if(!empty($text) && !empty($from_text_find_chemist_new)){
							//agar pura naam milay to he next prcess karta ha
							if (strpos($text, $from_text_find_chemist_new) !== false) {
								$whatsapp_chemist = $from_text_find_chemist_new;
								echo "xx6";
							} 
						}
					}
				}
			}

			$whatsapp_recommended = "";
			if(empty($whatsapp_chemist)){
				$whatsapp_recommended = $whatsapp_body;
			}
			// gar chemist he find nahi hua ho to 
			if(empty($from_text_find_chemist)){
				$whatsapp_chemist = "";
				$whatsapp_recommended = $whatsapp_body;
			}

			$where = array(
				'id' => $id,
			);
			$dt = array(
				'process_status'=>2,
				'whatsapp_id'=>$whatsapp_id,
				'whatsapp_chemist'=>$whatsapp_chemist,
				'whatsapp_recommended'=>$whatsapp_recommended,
			);
			echo "my01";
			print_r($dt);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}

	public function whatsapp_update_upi(){

		$working = 0;
		if($working==0){
			// **UPI Ref. No:** 5070336 94491 = 50703369449111 (11)date h =>507033694491 agar iss ke pichay date add ho kar aa rahi ha to wo oss ko delete kar ke upi no sahi karta ha
			//amount or vision or body text say karta ha search
			$result = $this->BankModel->select_query("SELECT p.upi_no,wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) and (REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.upi_no), '%') or REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.orderid), '%')) and REPLACE(TRIM(wm.body), ' ', '') LIKE CONCAT('%', TRIM(p.from_text_find_chemist), '%') where p.whatsapp_id='' and wm.upi_no!='' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;

				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);

				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}
		//die();
		if($working==0){
			// **UPI Ref. No:** 5070336 94491 = 50703369449111 (11)date h =>507033694491 agar iss ke pichay date add ho kar aa rahi ha to wo oss ko delete kar ke upi no sahi karta ha
			//amount or vision text say karta ha search
			$result = $this->BankModel->select_query("SELECT p.upi_no,wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) and (REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.upi_no), '%') or REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.orderid), '%')) where p.whatsapp_id='' and wm.upi_no!='' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;

				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);

				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}
		//die();
		if($working==0){
			//jab amount or vision text or body sab kuch sahi say match kary to
			//amount or vision or body text me say upi no find karna
			$result = $this->BankModel->select_query("SELECT p.upi_no,wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) and (REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.upi_no), '%') or REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.orderid), '%')) and REPLACE(TRIM(wm.body), ' ', '') LIKE CONCAT('%', TRIM(p.from_text_find_chemist), '%') where p.whatsapp_id='' and wm.upi_no='' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;

				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);

				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}
		//die();

		if($working==0){
			//other upi no xx 1234 amout say amount
			//amount or vision or body text me say upi no find karna
			$result = $this->BankModel->select_query("SELECT p.upi_no,wm.id AS whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) AND (REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%xx', RIGHT(TRIM(p.upi_no), 4), '%') or REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%xx', RIGHT(TRIM(p.orderid), 4), '%')) and REPLACE(TRIM(wm.body), ' ', '') LIKE CONCAT('%', TRIM(p.from_text_find_chemist), '%') WHERE p.whatsapp_id = '' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;

				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);

				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}
		//die();

		if($working==0){
			//other upi no xx 1234 amout say amount
			//amount or vision text me say upi no find karna
			$result = $this->BankModel->select_query("SELECT p.upi_no, wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) AND (REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%xx', RIGHT(TRIM(p.upi_no), 4), '%') or REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%xx', RIGHT(TRIM(p.orderid), 4), '%')) and p.whatsapp_id='' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;

				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);

				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}
		//die();

		if($working==0){
			//jab chemist id or amont match kar jaya to upi id set hoti ha
			//amount or body text me say upi no find karna
			$result = $this->BankModel->select_query("SELECT p.upi_no,wm.id AS whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) AND REPLACE(TRIM(wm.body), ' ', '') LIKE CONCAT('%', TRIM(p.from_text_find_chemist), '%') WHERE p.whatsapp_id = '' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;

				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);
				
				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}
		//die();

		if($working==0){
			//amount or upi id say find karta ha "CARTMEDICSHEALTHCAREPRIVATELIMITED.9873069729.IBZ@ICICI"
			$result = $this->BankModel->select_query("SELECT p.upi_no, wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) and REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.from_text_find_match), '%') and p.whatsapp_id='' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;
			
				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);
				
				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}
		//die();
		
		if($working==0){
			//jab whatsapp ki photo me bhut saray trastion aya 
			//https://api.wassi.chat/v1/chat/66faf180345d460e9984e4ac/files/67d056d71031ceec86dcd4fe/download?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e
			$result = $this->BankModel->select_query("SELECT p.upi_no,wm.id AS whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) and REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.upi_no), '%') WHERE p.whatsapp_id = '' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;

				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);
				
				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}

		if($working==0){
			//jab amount or vision text me chemist id mil kaya to 
			$result = $this->BankModel->select_query("SELECT p.upi_no,wm.amount, wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) and REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.from_text_find_chemist), '%') and p.whatsapp_id='' and p.from_text_find_chemist!='' limit 50");
			$result = $result->result();
			foreach($result as $row) {
				$working = 1;

				$upi_no = trim($row->upi_no);
				$whatsapp_id = trim($row->whatsapp_id);
				
				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'upi_no'=>$upi_no,
				);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
			if($working==1){
				$this->whatsapp_insert_in_processing();
			}
		}
	}

	public function whatsapp_update_reply_message(){
		
		$result = $this->BankModel->select_query("SELECT reply.body AS reply_body, wm.id as whatsapp_id FROM tbl_whatsapp_message AS wm LEFT JOIN tbl_bank_processing AS bp ON bp.whatsapp_id = wm.id LEFT JOIN tbl_whatsapp_message AS reply ON reply.reply_id = wm.message_id WHERE wm.reply_status=0 and reply.body!=''");
		$result = $result->result();
		foreach($result as $row) {
			if($row->reply_body){

				$whatsapp_id 	= $row->whatsapp_id;
				$reply_body 	= trim($row->reply_body);

				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'reply_body'=>$reply_body,
					'reply_status'=>1,
				);
				$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			}
		}
	}

	public function get_Whatsapp_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;

		$result = $this->BankModel->select_query("select * from tbl_whatsapp_message limit 10");
		$result = $result->result();
		foreach($result as $row) {
			
			$message_id = $row->message_id;
			$body 		= $row->body;
			$date 		= $row->date;
			$extracted_text = $row->extracted_text;
			$from_number = $row->from_number;
			$ist_timestamp = $row->ist_timestamp;
			$screenshot_image = $row->screenshot_image;
			$sender_name_place = $row->sender_name_place;
			$timestamp = $row->timestamp;
			$vision_text = $row->vision_text;

			$dt = array(
				'message_id' => $message_id,
				'body' => $body,
				'date' => $date,
				'extracted_text' => $extracted_text,
				'from_number'=>$from_number,
				'ist_timestamp'=>$ist_timestamp,
				'screenshot_image'=>$screenshot_image,
				'sender_name_place'=>$sender_name_place,
				'timestamp'=>$timestamp,
				'vision_text'=>$vision_text,
			);
			$jsonArray[] = $dt;
		}
		if(!empty($jsonArray)){
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