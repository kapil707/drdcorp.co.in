<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobBank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-drdcorp/BankModel");
	}

	public function insert_whatsapp(){
		echo "work";
		$start_date = $end_date = date('d-m-Y');

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);

		$start_date = $start_date->format('d/m/Y');
		$end_date 	= $end_date->format('d/m/Y');

		$sender_name_place = "Online%20Details";

		//Created a GET API
		echo $url = "http://192.46.214.43:5000/messages?start_date=$start_date&end_date=$end_date&sender_name_place=$sender_name_place&status=false";
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

		$data1 = json_decode($response, true); // Convert JSON string to associative array
	
		if (isset($data1['messages'])) {
			foreach ($data1['messages'] as $message) {
				$body = isset($message['body']) ? $message['body'] : "Body not found";

				$date = isset($message['date']) ? $message['date'] : "Date not found";
			}
		}
	}
	
	public function bank_processing(){
		//$this->get_invoice();
		$this->get_sms();
		$this->get_statment();
		$this->get_whatsapp();
		$this->get_main();
	}
	
	public function get_main(){
		
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='0' limit 1");
		$result = $result->result();
		foreach($result as $row){

			$received_from 	= $row->from_text;

			$find_by = "";
			$find_chemist_id = "";
			$process_value = "";
			$process_name = "";

			if(!empty($received_from)){
				$result = $this->find_by_full_name($received_from);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name-done";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$result = $this->find_by_name($received_from);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = str_replace(' ', '%', $received_from);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$result = $this->find_by_name($before_at);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -1);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -2);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -3);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -4);
				$result = $this->find_by_name($before_at);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 4";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$newString = substr($received_from, 0, -1);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($received_from, 0, -2);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($received_from, 0, -3);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($received_from, 0, -4);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table4";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$pattern = '/(\d{10})/';
				preg_match($pattern, $received_from, $matches);
				if (isset($matches[1])) {
					$result = $this->find_by_title($matches[1]);
					$find_chemist_id = $result["find_chemist_id"];
					$find_by = "Chemist mobile";
					$process_value = $result["process_value"];
					$process_name = $result["process_name"];
				}
			}

			if(empty($find_chemist_id)){
				$pattern = '/(\d{10})/';
				preg_match($pattern, $received_from, $matches);
				if (isset($matches[1])) {
					$result = $this->find_by_acm_tbl($matches[1]);
					$find_chemist_id = $result["find_chemist_id"];
					$find_by = "Acm mobile";
					$process_value = $result["process_value"];
					$process_name = $result["process_name"];
				}
			}
			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>1,				
				'from_value'=>$process_value,
				'from_value_find'=>$process_name,
				'chemist'=>$find_chemist_id,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}
	
	public function get_invoice(){
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' limit 1");
		$result = $result->result();
		foreach($result as $row){
			
			$amount 		= $row->amount;
			$date 			= $row->date;

			$start_date = date('Y-m-d', strtotime($date . ' -2 day'));
			$end_date = date('Y-m-d', strtotime($date));
			
			$chemist = str_replace("/",",",$row->chemist);

			/************************************************* */
			$result = $this->find_by_invoice($amount,$start_date,$end_date,$chemist);
			$invoice = $result["find_invoice_chemist_id"];
		
			if(!empty($chemist)){
				if(empty($invoice)){
					$result = $this->find_by_invoice_amount($amount,$start_date,$end_date,$chemist);
					$invoice = $result["find_invoice_chemist_id"];
				}
			}

			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>2,				
				'invoice'=>$invoice,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}
	
	public function get_sms(){

		//echo " get_sms";
		$date = date('Y-m-d');

		$result = $this->BankModel->select_query("select * from tbl_sms where status='0' limit 50");
		$result = $result->result();
		foreach($result as $row){
			echo $message_body = $row->message_body;
			$message_body = str_replace(",","",$row->message_body);
			
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
				$received_from = $matches[1];
			} else {
				$received_from = "Received from information not found";
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
			echo "<br>".$received_from."<br>";
			$statment_id = $row->id;
			
			$row_new = $this->BankModel->select_query("select id from tbl_bank_processing where upi_no='$upi_no'");
			$row_new = $row_new->row();
			
			if(empty($row_new->id) && $received_from!="Remitter" && $received_from != "Received from information not found"){
				$type = "SMS";
				$dt = array(
					'type'=>$type,
					'status'=>1,
					'amount'=>$amount,
					'date'=>$getdate,
					'time'=>$gettime,
					'received_from'=>$received_from,
					'upi_no'=>$upi_no,
					'orderid'=>$orderid,
					'statment_id'=>$statment_id,
					'from_sms'=>1,
				);
				$this->BankModel->insert_fun("tbl_bank_processing", $dt);
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

	public function get_statment(){

		//echo " get_statment";
		$result = $this->BankModel->select_query("select * from tbl_statment where status='0' limit 100");
		$result = $result->result();
		foreach($result as $row){
		
			echo $row->id."----<br>";
			$amount1 = $row->amount;
			$date = $row->date;
			echo $text = $row->narrative;
			$text = str_replace(array("\r", "\n"), '', $text);
			$upi_no = $orderid = $row->customer_reference;
			$received_from = "";

			/**********************************************/
			$text = preg_replace("/KKBKH\d+/", "", $text);
			$text = preg_replace("/KK\s*BKH\d+/", "", $text);
			$text = preg_replace("/KKB\s*KH\d+/", "", $text);
			$text = preg_replace("/KKBK\s*H\d+/", "", $text);
			$text = preg_replace("/KKBKH\s*\d+/", "", $text);

			$text = preg_replace("/9300966180/", '', $text);
			$text = preg_replace("/\s*9300966180/", '', $text);
			$text = preg_replace("/9\s*300966180/", '', $text);
			$text = preg_replace("/93\s*00966180/", '', $text);
			$text = preg_replace("/930\s*0966180/", '', $text);
			$text = preg_replace("/9300\s*966180/", '', $text);
			$text = preg_replace("/93009\s*66180/", '', $text);
			$text = preg_replace("/930096\s*6180/", '', $text);
			$text = preg_replace("/9300966\s*180/", '', $text);
			$text = preg_replace("/93009661\s*80/", '', $text);
			$text = preg_replace("/930096618\s*0/", '', $text);

			$text = preg_replace("/N2632432758889/", '', $text);
			$text = preg_replace("/N\s*2632432758889/", '', $text);
			$text = preg_replace("/N2\s*632432758889/", '', $text);
			$text = preg_replace("/N26\s*32432758889/", '', $text);
			$text = preg_replace("/N263\s*2432758889/", '', $text);
			$text = preg_replace("/N2632\s*432758889/", '', $text);
			$text = preg_replace("/N26324\s*32758889/", '', $text);
			$text = preg_replace("/N263243\s*2758889/", '', $text);
			$text = preg_replace("/N2632432\s*758889/", '', $text);
			$text = preg_replace("/N26324327\s*58889/", '', $text);
			$text = preg_replace("/N263243275\s*8889/", '', $text);
			$text = preg_replace("/N2632432758\s*889/", '', $text);
			$text = preg_replace("/N26324327588\s*89/", '', $text);
			$text = preg_replace("/N263243275888\s*9/", '', $text);
			$text = preg_replace("/N2632432758889\s*/", '', $text);

			$text = preg_replace("/AXOMB2639/", '', $text);
			$text = preg_replace("/A\s*XOMB2639/", '', $text);
			$text = preg_replace("/AX\s*OMB2639/", '', $text);
			$text = preg_replace("/AXO\s*MB2639/", '', $text);
			$text = preg_replace("/AXOM\s*B2639/", '', $text);
			$text = preg_replace("/AXOMB\s*2639/", '', $text);
			$text = preg_replace("/AXOMB2\s*639/", '', $text);
			$text = preg_replace("/AXOMB26\s*39/", '', $text);
			$text = preg_replace("/AXOMB263\s*9/", '', $text);
			$text = preg_replace("/AXOMB2639\s*/", '', $text);
			/**********************************************/
			$text = preg_replace('/\s+\d+TXN\s+REF NO/', ' REF NO', $text);
			$text = preg_replace('/\s+\d+\s+REF NO/', ' REF NO', $text);
			$text = preg_replace('/AX.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/N00.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/PUNBY.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/INDBN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/ICIN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/YES.*?REF NO/', ' REF NO', $text);

			$text = preg_replace('/SB2.*?-UPI/', ' UPI', $text);
			echo "<br>".$text;

			preg_match("/FROM\s+(.+?)\s+CITI/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 1;
				echo "<br>1</br>";
			}
			
			preg_match("/FROM\s+(.+?)\s*+PAYMENT/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 2;
				echo "<br>2</br>";
			}

			preg_match("/FROM\s+(.+?)\s+SENT/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 3;
				echo "<br>3</br>";
			}

			preg_match("/FROM\s+(.+?)\s+UPI/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 4;
				echo "<br>4</br>";
			}

			preg_match("/FROM\s+(.+?)\s+REF/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 5;
				echo "<br>5</br>";
			}

			preg_match("/FROM\s+(.+?)\s+PAY/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 6;
				echo "<br>6</br>";
			}
			
			preg_match("/FROM\s+(\d+)@\s+(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 7;
				echo "<br>7</br>";
			}
			
			preg_match("/FROM\s+(\d+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find2: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 8;
				echo "<br>8</br>";
			}

			preg_match("/FROM\s+(\w+)\d+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find3: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 9;
				echo "<br>9</br>";
			}

			preg_match("/FROM\s+([^\s@]+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find4: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 10;
				echo "<br>10</br>";
			}

			preg_match("/FROM\s+([^\@]+)@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find5: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 11;
				echo "<br>11</br>";
			}

			preg_match("/FROM\s+(.*?)\s+PUNBQ/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 12;
				echo "<br>12</br>";
			}

			preg_match("/FROM\s+([\w\s]+)\s+[A-Z0-9]+\s+REF NO/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 13;
				echo "<br>13</br>";
			}

			preg_match("/FROM\s+(.*?)\s+CITI0000/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 14;
				echo "<br>14</br>";
			}

			preg_match("/FROM\s+(.*)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 15;
				echo "<br>15</br>";
			}

			echo $received_from."<br>";
			//die();

			$statment_id = $row->id;
			if(!empty($received_from)){
				$row_new = $this->BankModel->select_query("select id,type,status,received_from from tbl_bank_processing where upi_no='$upi_no'");
				$row_new = $row_new->row();
				
				if(empty($row_new->id)){
					$type = "Statment";
					$dt = array(
						'type'=>$type,
						'status'=>1,
						'amount'=>$amount1,
						'date'=>$date,
						'received_from'=>$received_from,
						'upi_no'=>$upi_no,
						'orderid'=>$orderid,
						'statment_id'=>$statment_id,
						'from_statment'=>1,
						'statment_type'=>$statment_type,
					);
					$this->BankModel->insert_fun("tbl_bank_processing", $dt);
				}else{
					$where = array('upi_no'=>$upi_no);
					$status = 0;
					$type = $row_new->type;
					if($type=="SMS")
					{
						$type = "SMS or Update With Statment";
					}
					if(strtolower($row_new->received_from)==strtolower($received_from)){
						$status = $row_new->status;
					}
					$dt = array(
						'type'=>$type,
						'status'=>$status,
						'received_from'=>$received_from,
						'orderid'=>$orderid,
						'statment_id'=>$statment_id,
					);
					$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
				}
			}
			/****************************************************** */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>'1',
			);
			$this->BankModel->edit_fun("tbl_statment", $dt,$where);
		}
	}

	public function get_whatsapp(){
		echo " get_whatsapp ";
		//$result = $this->BankModel->select_query("SELECT tbl_whatsapp_message.id,tbl_whatsapp_message.vision_text,tbl_bank_processing.upi_no,tbl_bank_processing.id as myid FROM tbl_bank_processing, tbl_whatsapp_message WHERE tbl_whatsapp_message.vision_text LIKE CONCAT('%', tbl_bank_processing.upi_no, '%') and tbl_bank_processing.status=1");

		$result = $this->BankModel->select_query("SELECT id,upi_no,amount,orderid from tbl_bank_processing where status=2 limit 1");
		$result = $result->result();
		foreach($result as $row){

			$upi_no = trim($row->upi_no);
			$orderid= trim($row->orderid);
			$amount = $row->amount;

echo "SELECT * FROM `tbl_whatsapp_message` WHERE REPLACE(`vision_text`, ' ', '') LIKE '%$upi_no%' or REPLACE(`vision_text`, ' ', '') LIKE '%$orderid%'";

			$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE REPLACE(`vision_text`, ' ', '') LIKE '%$upi_no%' or REPLACE(`vision_text`, ' ', '') LIKE '%$orderid%'");
			$row1 = $row1->row();

			if(empty($row1)){
				$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE `vision_text` LIKE '%$upi_no%' or `vision_text` LIKE '%$orderid%'");
				$row1 = $row1->row();
			}

			if(empty($row1)){
				$last_four_digits = substr($upi_no, -4);
				$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE (REPLACE(`vision_text`, ' ', '') LIKE '%XX$last_four_digits%' and REPLACE(`vision_text`, ',', '') LIKE '%$amount%')");
				$row1 = $row1->row();
			}
			
			$whatsapp_id = 0;
			$whatsapp_body = $whatsapp_image = $whatsapp_body2 = "";
			if(!empty($row1)){
				$whatsapp_id = $row1->id;
				$whatsapp_body = $row1->body;
				$whatsapp_image = $row1->screenshot_image;
				$whatsapp_body2 = $row1->vision_text;

				$from_number = $row1->from_number;
				$timestamp = date('Y-m-d H:i:s', $row1->timestamp);

				if(empty($whatsapp_body)){
					$row2 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' LIMIT 0, 25");
					//echo "SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' LIMIT 0, 25";
					$row2 = $row2->row();
					$whatsapp_body = $row2->body;
				}
			}



			if(empty($row1)){
				$row2 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE REPLACE(`body`, ' ', '') LIKE '%$upi_no%'");
				$row2 = $row2->row();

				if(!empty($row2)){
					$whatsapp_id = $row2->id;
					$whatsapp_body = "";
					$whatsapp_image = $row2->screenshot_image;
					$whatsapp_body2 = $row2->body;

					$from_number = $row2->from_number;
					$timestamp = date('Y-m-d H:i:s', $row2->timestamp);

					if(empty($whatsapp_body)){
						$row2 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' and id!='$whatsapp_id' LIMIT 0, 25");
						//echo "SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' LIMIT 0, 25";
						$row2 = $row2->row();
						$whatsapp_body = $row2->body;
					}
				}
			}

			$whatsapp_body  = str_replace(',', '', $whatsapp_body);
			$whatsapp_body2 = str_replace(',', '', $whatsapp_body2);
			echo "<br>";

			$where = array(
				'id' => $row->id,
			);
			$dt = array(
				'status'=>3,
				'whatsapp_message_id'=>$whatsapp_id,
				'whatsapp'=>$whatsapp_body2,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}

	/***************************************************************************************************/
	function find_by_full_name($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";
		
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_name($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_title($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		$received_from = str_replace(' ', '', $received_from);
		
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
			//$chemist_id = $tt->chemist_id;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_acm_tbl($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `telephone` like '%$received_from%' ");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->altercode;
			$process_value = $tt->telephone;
			//$chemist_id = $tt->chemist_id;
		}

		if(empty($process_value)){
			$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `telephone1` like '%$received_from%' ");
			$rr = $rr->result();
			foreach($rr as $tt){
				$jsonArray[] = $tt->altercode;
				$process_value = $tt->telephone1;
				//$chemist_id = $tt->chemist_id;
			}
		}

		if(empty($process_value)){
			$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `mobile` like '%$received_from%' ");
			$rr = $rr->result();
			foreach($rr as $tt){
				$jsonArray[] = $tt->altercode;
				$process_value = $tt->mobile;
				//$chemist_id = $tt->chemist_id;
			}
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_invoice($amount,$start_date,$end_date,$find_chemist_id){

		$jsonArray = array();

		$where = "";
		if(!empty($find_chemist_id)){
			$fruits_array = explode(",", $find_chemist_id);
			foreach($fruits_array as $rows){
				$find_chemist_id = "'$rows',";
			}
			$find_chemist_id = substr($find_chemist_id, 0, -1);
			$where = " and chemist_id in ($find_chemist_id)";
		}

		//$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where amt='$amount' and (vdt BETWEEN '$start_date' and '$end_date') $where");
		//echo "select * from tbl_invoice_new where amt='$amount' and (vdt BETWEEN '$start_date' and '$end_date') $where";
		$rr = $this->db->query("select * from tbl_invoice_new where amt='$amount' and (vdt BETWEEN '$start_date' and '$end_date') $where");
		$rr = $rr->result();
		foreach($rr as $tt){			
			$jsonArray[] = $tt->chemist_id.":-".$tt->gstvno." Amt.".$tt->amt;
		}

		$find_invoice_chemist_id = "";
		if(!empty($jsonArray)){
			$find_invoice_chemist_id = implode('||', $jsonArray);
		}

		$return["find_invoice_chemist_id"] = $find_invoice_chemist_id;

		return $return;
	}

	function find_by_invoice_amount($amount,$start_date,$end_date,$find_chemist_id){

		$jsonArray = array();

		$where = "";
		if(!empty($find_chemist_id)){
			$fruits_array = explode(",", $find_chemist_id);
			foreach($fruits_array as $rows){
				$find_chemist_id = "'$rows',";
			}
			$find_chemist_id = substr($find_chemist_id, 0, -1);
			$where = " and chemist_id in ($find_chemist_id)";
		}

		$find_invoice_chemist_id = "";
		$resultArray = [];
		//$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where (vdt BETWEEN '$start_date' and '$end_date') $where");
		$rr = $this->db->query("select * from tbl_invoice_new where (vdt BETWEEN '$start_date' and '$end_date') $where");
		$rr = $rr->result();
		foreach($rr as $tt){
			$resultArray[] = [
				'chemist_id' => $tt->chemist_id,
				'gstvno' => $tt->gstvno,
				'amount' => $tt->amt
			];
		}

		$targetValue = $amount;
		$found = false;
		$selectedValues = [];

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
				$jsonArray[] = $rt['chemist_id'].":-".$rt['gstvno']." Amt-x.".$rt['amount'];
			}
		}
		$find_invoice_chemist_id = implode('||', $jsonArray);

		$return["find_invoice_chemist_id"] = $find_invoice_chemist_id;

		return $return;
	}

	public function test($chemist_id,$amount,$dt1,$dt2){

		$resultArray = [];
		$rr = $this->InvoiceModel->select_query("SELECT * FROM `tbl_invoice_new` WHERE (vdt BETWEEN '$dt1' and '$dt2') and `chemist_id`='$chemist_id'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$resultArray[] = [
				'chemist_id' => $tt->chemist_id,
				'gstvno' => $tt->gstvno,
				'amount' => $tt->amt
			];
		}
		echo "<pre>";
		print_r($resultArray);

		$targetValue = $amount;
		$found = false;
		$selectedValues = [];

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
			print_r($selectedValues);
		} else {
			echo "No two values found with the total of $targetValue";
		}
	}

	public function bank_processing_done(){
	
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' and done_status=0 limit 1");
		$result = $result->result();
		foreach($result as $row){
			$find_chemist_id2 = "";
			$find_chemist_id_array = explode(",", $row->find_chemist_id);
			$find_chemist_id_array = array_unique($find_chemist_id_array);
			foreach($find_chemist_id_array as $rows){
				$find_chemist_id2 = $rows;
			}

			$find_invoice_chemist_id2 = "";
			$find_invoice_chemist_id_array = explode(",", $row->find_invoice_chemist_id);
			foreach($find_invoice_chemist_id_array as $rows){
				$arr = explode(":-",$rows);
				$find_invoice_chemist_id2 = $arr[0];
			}
			
			$done_status = "2";
			$done_chemist_id = "";
			if((strtolower($find_chemist_id2)==strtolower($find_invoice_chemist_id2)) && (!empty($find_invoice_chemist_id2) && !empty($find_chemist_id2))){
				$done_status = "1";
				$done_chemist_id = $find_chemist_id2;
			}

			$where = array(
				'id' => $row->id,
			);
			$dt = array(
				'done_chemist_id'=>$done_chemist_id,
				'done_status'=>$done_status,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}
}