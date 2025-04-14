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

	public function whatsapp_update_upi(){
		echo "whatsapp_update_upi";
		$this->BankWhatsAppModel->whatsapp_update_upi();
	}

	public function whatsapp_update_reply_message(){
		echo "whatsapp_update_reply_message";
		$this->BankWhatsAppModel->whatsapp_update_reply_message();
	}
	
	public function recommended_to_find(){
		echo "recommended_to_find";
		$this->BankProcessingModel->recommended_to_find();
	}

	public function testing(){
		
		$text = "NEFT IN UTR CITIN25547158484 FROM VIKRAM MEDICOS A   UBLH09821432415TXN REF NO Money Transfer";
		$upi_no = "AUBLH09821432415";

		echo "full text: $text <br>";
		echo "upi0: $upi_no <br>";
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
			$withSpace = substr($upi_no1, 0, $i) . '  ' . substr($upi_nupi_no1o, $i);
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
			$withSpace = substr($upi_no1, 0, $i) . '  ' . substr($upi_nupi_no1o, $i);
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