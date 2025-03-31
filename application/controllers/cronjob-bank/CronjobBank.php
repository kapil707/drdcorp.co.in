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

	public function get_invoice_or_insert_drdweb(){
		echo "get_invoice_or_insert_drdweb";
		$this->BankWhatsAppModel->get_invoice_or_insert_drdweb();
	}

	public function get_whatsapp_or_insert_drdweb(){
		echo "get_whatsapp_or_insert_drdweb";
		$this->BankWhatsAppModel->get_whatsapp_or_insert_drdweb();
	}

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
		
		$row_from_text = "9911644379@PTYES";
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
		echo $find_chemist_id;
		/*$text = "+91-9899762072 507920298106 FROM MEHAK MEDICOS AND DEPARTMENTAL STORE 9300966180 CITI0000 9052 TRANS FER TO DR KARB0000547";
		preg_match("/FROM\s+(.+?)\s+CITI/", $text, $matches);
		if (!empty($matches) && empty($received_from)){
			echo $received_from = trim($matches[1]);
			//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 2;
			echo "<br>2</br>";
		}*/
	}

	public function bank_main(){
		//$this->get_invoice();
		$check_sms = $this->BankModel->select_row("tbl_sms", array('status' => 0));
		if (!empty($check_sms)) {
			$this->BankSMSModel->get_sms();
		}else{
			$check_statment = $this->BankModel->select_row("tbl_statment", array('status' => 0));
			if (!empty($check_statment)) {
				$this->BankStatmentModel->get_statment();
			}else{
				$check_processing = $this->BankModel->select_row("tbl_bank_processing", array('process_status'=>0));
				if (!empty($check_processing)) {
					$this->BankProcessingModel->get_processing();
				}else{
					$check_whatsapp_status = $this->BankModel->select_row("tbl_whatsapp_message", array('status'=>0));
					if (!empty($check_whatsapp_status)) {
						$this->BankWhatsAppModel->whatsapp_find_upi_amount();
					}else{
						//yha whatsapp message ko insert karwata ha processing me
						$result = $this->BankModel->select_query("SELECT p.id FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.upi_no = wm.upi_no AND wm.date BETWEEN DATE_SUB(p.date, INTERVAL 1 DAY) AND DATE_ADD(p.date, INTERVAL 1 DAY) WHERE p.whatsapp_id = '' ORDER BY RAND() LIMIT 25");
						$check_whatsapp_status2 = $result->row();
						if (!empty($check_whatsapp_status2)) {
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