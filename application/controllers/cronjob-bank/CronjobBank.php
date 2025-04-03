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

	public function whatsapp_find_upi_amount(){
		echo "whatsapp_find_upi_amount";
		$this->BankWhatsAppModel->whatsapp_find_upi_amount();
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
		$text = "NEFT IN UTR CITIN25543558586 FROM MAXIMUM HELP PHA RMACY HDFCH00 152194269TXN REF NO 0001MAXIMUM HELP PHARM";

		$text = str_replace(array("\r", "\n"), ' ', $text);
		$from_text = "";

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

		$text = preg_replace("/N\s*0/", '', $text);
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
		$text = preg_replace('/PUNBH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/INDBN.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/INDBH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/IDFBH.*?REF NO/', ' REF NO', $text); 
		$text = preg_replace('/ID FBH.*?REF NO/', ' REF NO', $text); 
		$text = preg_replace('/ICIN.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/YES.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/POD.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/TXN.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/FOR.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/CNRBH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/N 06.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/N06.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/SBIN.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/BKIDN.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/BINH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/C\s*BINH.*?REF NO/', ' REF NO', $text);

		$text = preg_replace('/INDBH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/I\sNDBH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/IN\sDBH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/IND\sBH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/INDB\sH.*?REF NO/', ' REF NO', $text);

		$text = preg_replace('/HDFCH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/H DFCH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/HD FCH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/HDF CH.*?REF NO/', ' REF NO', $text);
		$text = preg_replace('/HDFC H.*?REF NO/', ' REF NO', $text);

		$text = preg_replace('/SB2.*?-UPI/', ' UPI', $text);
		echo "<br>".$text;

		preg_match("/FROM\s+(.+?)\s+REF/", $text, $matches);
		if (!empty($matches) && empty($received_from)){
			echo $received_from = trim($matches[0]);
			//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 2;
			echo "<br>2</br>";
		}
	}

	public function bank_main(){
		//$this->get_invoice();
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