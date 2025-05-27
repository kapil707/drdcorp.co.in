<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobBank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
		$this->load->model("model-bank/BankSMSModel");
		$this->load->model("model-bank/BankStatmentModel");
		$this->load->model("model-bank/BankProcessingModel");
		$this->load->model("model-bank/BankWhatsAppModel");
		$this->load->model("model-bank/BankInvoiceModel");		
	}

	// yha whatsapp download karta ha rishav server say drdweb server par
	public function get_whatsapp_or_insert_rishav(){
		echo "get_whatsapp_or_insert_rishav";
		$this->BankWhatsAppModel->get_whatsapp_or_insert_rishav();
	}

	public function whatsapp_update_reply_message(){
		echo "whatsapp_update_reply_message";
		$this->BankWhatsAppModel->whatsapp_update_reply_message();
	}

	public function get_sms_again(){
		echo "get_sms_again";
		$this->BankSMSModel->get_sms_again();
	}

	public function testing(){

		$sms_text = $message_body = "Rs.38500 received from Remitter ID bearing CITIN25563340500 has been successfully credited to D. R. DISTRIBUTORS PVT LTD's account with Citibank on 12/05/25. Thank you!";
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
			$pattern = '/received from Remitter ID bearing\s+(\S+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$upi_no = $orderid = $matches[1];
			} else {
				$from_text = "n/a";
			}
		}

		echo "<br>Amount : ".$amount." From : ".$from_text." upi_no : ".$upi_no." orderid : ".$orderid."<br>";

		die();
		
		$text = "'+91-9810350383 510818340185 FROM TERA HI TERA PHAR MACY U O DHAN GURU NANAK SEWA MISSION 9300966180 C ITI0000 7217 STOP CHECK PART '";
		$upi_no = "AUBLH09821432415";

		$text = preg_replace('/\s*\n/', ' ', $text);
		$text = str_replace($upi_no, ' ', $text);
		$text = str_replace(' TXN REF NO', ' TXN REF NO', $text);
		$text = str_replace('T XN REF NO', ' TXN REF NO', $text);
		$text = str_replace('TX N REF NO', ' TXN REF NO', $text);
		$from_text = "";

		// yha check karta ha upi no or oss ko remove karta ha string me say
		$length = strlen($upi_no);
		for ($i = 1; $i < $length; $i++) {
			$withSpace = substr($upi_no, 0, $i) . ' ' . substr($upi_no, $i);
			$text = str_replace($withSpace, ' ', $text);
		}
		$length = strlen($upi_no);
		for ($i = 1; $i < $length; $i++) {
			$withSpace = substr($upi_no, 0, $i) . '  ' . substr($upi_no, $i);
			$text = str_replace($withSpace, ' ', $text);
		}
		/******************************* */
		//KK BKH2509190011 5TXN
		$upi_no1 = substr($upi_no, 0, -1);
		echo "upi1: $upi_no1 <br>";
		$length = strlen($upi_no1);
		for ($i = 1; $i < $length; $i++) {
			$withSpace = substr($upi_no1, 0, $i) . ' ' . substr($upi_no1, $i);
			$text = str_replace($withSpace, ' ', $text);
		}
		$length = strlen($upi_no1);
		for ($i = 1; $i < $length; $i++) {
			$withSpace = substr($upi_no1, 0, $i) . '  ' . substr($upi_no1, $i);
			$text = str_replace($withSpace, ' ', $text);
		}
		/******************************* */
		//N 0922507454800 21TXN
		$upi_no1 = substr($upi_no, 0, -2);
		echo "upi1: $upi_no1 <br>";
		$length = strlen($upi_no1);
		for ($i = 1; $i < $length; $i++) {
			$withSpace = substr($upi_no1, 0, $i) . ' ' . substr($upi_no1, $i);
			$text = str_replace($withSpace, ' ', $text);
		}
		$length = strlen($upi_no1);
		for ($i = 1; $i < $length; $i++) {
			$withSpace = substr($upi_no1, 0, $i) . '  ' . substr($upi_no1, $i);
			$text = str_replace($withSpace, ' ', $text);
		}
		/******************************* */
		// yha last 2 ko delete karta ha 
		$upi_no2 = substr($upi_no,-2);
		echo "upi2: $upi_no2 <br>";
		$text = str_replace($upi_no2."TXN", 'TXN', $text);
		echo "text: $text <br>";
		/******************************* */
		// yha last 1 ko delete karta ha 
		$upi_no2 = substr($upi_no,-1);
		echo "upi2: $upi_no2 <br>";
		$text = str_replace($upi_no2."TXN", 'TXN', $text);
		echo "text: $text <br>";
		/**********************************************
		$text = preg_replace("/KKBKH\d+/", "", $text);
		$text = preg_replace("/KK\s*BKH\d+/", "", $text);
		$text = preg_replace("/KKB\s*KH\d+/", "", $text);
		$text = preg_replace("/KKBK\s*H\d+/", "", $text);
		$text = preg_replace("/KKBKH\s*\d+/", "", $text); 

		/**********************************************/
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
		
		preg_match("/FROM\s+(.+?)\s+TXN REF NO/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 1;
			echo "statment type:1</br>";
		}

		preg_match("/FROM\s+(.+?)\s+REF/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 2;
			echo "statment type:2</br>";
		}

		preg_match("/FROM\s+(.+?)\s+CITI/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 3;
			echo "statment type:3</br>";
		}

		preg_match("/FROM\s+(.+?)\s+C ITI/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 31;
			echo "statment type:31</br>";
		}
		
		preg_match("/FROM\s+(.+?)\s*+PAYMENT/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 4;
			echo "statment type:4</br>";
		}

		preg_match("/FROM\s+(.+?)\s+SENT/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 5;
			echo "statment type:5</br>";
		}

		preg_match("/FROM\s+(.+?)\s+UPI/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 6;
			echo "statment type:6</br>";
		}

		preg_match("/FROM\s+(.+?)\s+PAY/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 7;
			echo "statment type:7</br>";
		}
		
		preg_match("/FROM\s+(\d+)@\s+(\w+)/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1])."@".trim($matches[2]);
			$from_text = str_replace("'", "", $from_text);
			$from_text = str_replace(" ", "", $from_text);
			$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 8;
			echo "statment type:8</br>";
		}
		
		preg_match("/FROM\s+(\d+)\s+@\s*(\w+)/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1])."@".trim($matches[2]);
			$from_text = str_replace("'", "", $from_text);
			$from_text = str_replace(" ", "", $from_text);
			$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find2: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 9;
			echo "statment type:9</br>";
		}

		/*//preg_match("/FROM\s+(\w+)\d+@\s*(\w+)/", $text, $matches);
		preg_match("/FROM\s+([\d]+)@\s*([\w]+)/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1])."@".trim($matches[2]);
			$from_text = str_replace("'", "", $from_text);
			$from_text = str_replace(" ", "", $from_text);
			$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find3: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 10;
			echo "<br>10</br>";
		}

		preg_match("/FROM\s+([^\s@]+)\s+@\s*(\w+)/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1])."@".trim($matches[2]);
			$from_text = str_replace("'", "", $from_text);
			$from_text = str_replace(" ", "", $from_text);
			$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find4: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 11;
			echo "statment type:11</br>";
		}*/

		preg_match("/FROM\s+([^\@]+)@\s*(\w+)/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1])."@".trim($matches[2]);
			$from_text = str_replace("'", "", $from_text);
			$from_text = str_replace(" ", "", $from_text);
			$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find5: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 12;
			echo "statment type:12</br>";
		}
		/*
		preg_match("/FROM\s+(.*?)\s+PUNBQ/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_text = str_replace("'", "", $from_text);
			//$from_text = str_replace(" ", "", $from_text);
			//$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 13;
			echo "statment type:13</br>";
		}

		preg_match("/FROM\s+([\w\s]+)\s+[A-Z0-9]+\s+REF NO/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_text = str_replace("'", "", $from_text);
			//$from_text = str_replace(" ", "", $from_text);
			//$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 14;
			echo "statment type:14</br>";
		}

		preg_match("/FROM\s+(.*?)\s+CITI0000/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_text = str_replace("'", "", $from_text);
			//$from_text = str_replace(" ", "", $from_text);
			//$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 15;
			echo "statment type:15</br>";
		}*/

		preg_match("/FROM\s+(.*?)\s+9300\d+/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_text = str_replace("'", "", $from_text);
			//$from_text = str_replace(" ", "", $from_text);
			//$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 16;
			echo "statment type:16</br>";
		}

		/*
		preg_match("/FROM\s+(.*)/", $text, $matches);
		if (!empty($matches) && empty($from_text)){
			$from_text = trim($matches[1]);
			//$from_text = str_replace("'", "", $from_text);
			//$from_text = str_replace(" ", "", $from_text);
			//$from_text = str_replace("\n", "", $from_text);
			//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 17;
			echo "statment type:17</br>";
		}*/

		echo "find: $from_text <br>";
		die();

		/*$row_from_text = "9911644379@PTYES";
		$row_from_text_find_match = "9911644379";

		$row_from_text_find_match = preg_quote($row_from_text_find_match, '/');
		$row_from_text_find_match = preg_replace('/(' . $row_from_text_find_match . ')/i', '<span style="background-color: blue;">$1</span>', $row_from_text);
		if(empty($row_from_text)){
			$row_from_text_find_match = "N/a";
		}
		echo $row_from_text_find_match;
		/*
		$find_chemist_id = "G196/a633 || A633";
		$find_chemist_id = str_replace("/", " || ", $find_chemist_id);
		$array = explode("||", $find_chemist_id);
		$array = array_map('trim', $array);
		$array = array_map('strtolower', $array);
		$array = array_unique($array);
		$find_chemist_id = "";
		foreach($array as $myrow){
			$find_chemist_id.= ucfirst($myrow)." || ";
		}
		$find_chemist_id = substr($find_chemist_id, 0, -4);
		echo $find_chemist_id; */
		$text = "FROM ONE 97 COMMUNICAT IONS LIMITED SETTLEMENT ACCOUNT 9300966180 CITI000 0 7217 API TXN YESB0000001";

		$text = str_replace(array("\r", "\n"), ' ', $text);
		$from_text = "";

		echo "<br>".$text;
		/*****/
		echo "<br>";

		preg_match("/FROM\s+(.*?)\s+9300\d+/", $text, $matches);
		if (!empty($matches) && empty($received_from)){
			echo $received_from = trim($matches[1]);
			//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 1;
			echo "<br>1</br>";
		}
	}

	public function bank_main(){
		//$this->get_invoice();
		$whatsapp_find_upi_amount = $this->BankModel->select_row("tbl_whatsapp_message", array('status' => 0));
		if (!empty($whatsapp_find_upi_amount)) {
			echo "whatsapp_find_upi_amount<br>";
			$this->BankWhatsAppModel->whatsapp_find_upi_amount();
			die();
		}else{
			$check_sms = $this->BankModel->select_row("tbl_sms", array('status' => 0));
			if (!empty($check_sms)) {
				echo "get_sms<br>";
				$this->BankSMSModel->get_sms();
			}else{
				$check_statment = $this->BankModel->select_row("tbl_statment", array('status' => 0));
				if (!empty($check_statment)) {
					echo "get_statment<br>";
					$this->BankStatmentModel->get_statment();
				}else{
					$check_processing = $this->BankModel->select_row("tbl_bank_processing", array('process_status'=>0));
					if (!empty($check_processing)) {
						echo "tbl_bank_processing<br>";
						$this->BankProcessingModel->get_processing();
					}else{
						//yha whatsapp message ko insert karwata ha processing me
						$result = $this->BankModel->select_query("SELECT p.id FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.upi_no = wm.upi_no AND wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) WHERE p.whatsapp_id = '' ORDER BY RAND() LIMIT 25");
						$check_whatsapp_status2 = $result->row();
						if (!empty($check_whatsapp_status2)) {
							echo "whatsapp_insert_in_processing<br>";
							$this->BankWhatsAppModel->whatsapp_insert_in_processing();
						}else{
							$this->BankInvoiceModel->get_invoice_find_user();

							echo "whatsapp_update_upi";
							$this->BankWhatsAppModel->whatsapp_update_upi();

							echo "recommended_to_find";
							$this->BankProcessingModel->recommended_to_find();
						}
					}
				}
			}
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