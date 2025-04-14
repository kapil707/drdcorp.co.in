<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankProcessingModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
		$this->load->model("model-bank/BankWhatsAppModel");
		$this->load->model("model-bank/BankInvoiceModel");	
	}

	public function get_processing(){
		
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where process_status='0' limit 75");
		$result = $result->result();
		foreach($result as $row){

			echo $from_text = $row->from_text;
			$from_text = str_replace("'", "", $from_text);

			$find_by = "";
			$find_chemist_id = "";
			$process_value = "";
			$process_name = "";

			if(!empty($from_text)){
				$result = $this->find_by_full_name($from_text);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name-done";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$result = $this->find_by_name($from_text);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = str_replace(' ', '%', $from_text);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$result = $this->find_by_name($before_at);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -1);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -2);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -3);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
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
				$newString = substr($from_text, 0, -1);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($from_text, 0, -2);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($from_text, 0, -3);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($from_text, 0, -4);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table4";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$pattern = '/(\d{10})/';
				preg_match($pattern, $from_text, $matches);
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
				preg_match($pattern, $from_text, $matches);
				if (isset($matches[1])) {
					$result = $this->find_by_acm_tbl($matches[1]);
					$find_chemist_id = $result["find_chemist_id"];
					$find_by = "Acm mobile";
					$process_value = $result["process_value"];
					$process_name = $result["process_name"];
				}
			}
			echo $find_by;
			if(!empty($find_chemist_id)){
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
			}

			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'process_status'=>1,				
				'from_text_find'=>$process_value,
				'from_text_find_match'=>$process_name,
				'from_text_find_chemist'=>$find_chemist_id,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}

	public function recommended_to_find(){
		
		$date = date("Y-m-d");
		// jab invoice or whatsapp mil jaya but find chemist ek say jada hoya to yha automatick user ko add karta ha v153 || v155 = v155
		$result = $this->BankModel->select_query("SELECT id,invoice_chemist FROM `tbl_bank_processing` WHERE `invoice_chemist`=`whatsapp_chemist` and invoice_chemist!='' and whatsapp_chemist!='' and invoice_chemist!=from_text_find_chemist and date='$date' and recommended_status=0");
		$result = $result->result();
		foreach($result as $row){
			if(!empty($row->id)){
				
				$id = $row->id;
				$chemist_id = $row->invoice_chemist;
				$where = array('id'=>$id);
				$dt = array(				
					'recommended_status'=>'1',	
					'recommended'=>$chemist_id,
				);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}

		$result = $this->BankModel->select_query("SELECT id,from_text,from_text_find_chemist,invoice_recommended FROM `tbl_bank_processing` WHERE `invoice_recommended`=`whatsapp_recommended` and invoice_recommended!='' and whatsapp_recommended!='' and recommended='' and date='$date'");
		$result = $result->result();
		foreach($result as $row){
			if(!empty($row->id)){
				$id = $row->id;
				$from_text = $row->from_text;
				$from_text_find_chemist = $row->from_text_find_chemist;
				$recommended = $row->invoice_recommended;
				
				if(empty($from_text_find_chemist)){
					// agar recommended user match ho jaya or chemist id find na hoya to

					echo $chemist_id = $recommended;
					echo "<br>";
					$dt = array(
						'chemist_id' => $chemist_id,
						'string_value' => $from_text,
						'date'=>date('Y-m-d'),
						'time'=>date('H:i'),
						'timestamp'=>time(),
						'user_id'=>$this->session->userdata("user_id"),
						'recommended'=>'1'
					);
					$this->BankModel->insert_fun("tbl_bank_chemist", $dt);

					/******************************* */
					$where = array('id'=>$id);
					$dt = array(
						'process_status'=>0,
						'recommended'=>$chemist_id,
						'recommended_status'=>'2',
						'whatsapp_id'=>0,
						'whatsapp_chemist'=>'',
						'invoice_id'=>'',
						'invoice_chemist'=>'',
						'invoice_text'=>'',
					);
					$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);

				}else{

					$chemist_id = "";
					$array = explode(" || ", $from_text_find_chemist);
					foreach($array as $myid){
						if(strtolower($myid)==strtolower($recommended))
						{
							$chemist_id = $recommended;
							$recommended_status = 3; //jab 2 user me say kisi ek ko select karta ha to 
						}
					}

					if(empty($chemist_id)){
						$chemist_id = $recommended;
						$recommended_status = 4; //jab user galt aa raha ho to 
					}

					if(!empty($chemist_id)){
					
						echo $chemist_id;
						echo "<br>";

						$where = array('id'=>$id);
						$dt = array(					
							'recommended'=>$chemist_id,
							'recommended_status'=>$recommended_status,
							'whatsapp_chemist'=>$chemist_id,
							'invoice_chemist'=>$chemist_id,
						);
						$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
					}
				}
			}
		}
	}
	/*****************************************************************************/
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

		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
		}*/

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

		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
		}*/

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

		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
		}*/

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

		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_chemist` WHERE `telephone` like '%$received_from%' ");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->altercode;
			$process_value = $tt->telephone;
			//$chemist_id = $tt->chemist_id;
		}

		if(empty($process_value)){
			$rr = $this->BankModel->select_query("SELECT * FROM `tbl_chemist` WHERE `telephone1` like '%$received_from%' ");
			$rr = $rr->result();
			foreach($rr as $tt){
				$jsonArray[] = $tt->altercode;
				$process_value = $tt->telephone1;
				//$chemist_id = $tt->chemist_id;
			}
		}

		if(empty($process_value)){
			$rr = $this->BankModel->select_query("SELECT * FROM `tbl_chemist` WHERE `mobile` like '%$received_from%' ");
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

	public function get_processing_recommended(){
		$date = date("Y-m-d");
		// yha sirf suggest karta ha user ko 
		$result = $this->BankModel->select_query("SELECT id,amount,date FROM `tbl_bank_processing` WHERE date='$date' and `from_text_find_chemist`='' and from_text_find_chemist_status=0 limit 50");
		$result = $result->result();
		foreach($result as $row) {

			$id 	= $row->id;
			$amount = $row->amount;
			$date	= $row->date;

			$chemist_id = $this->BankWhatsAppModel->whatsapp_recommended($id,$amount,$date);
			if(!empty($chemist_id)){
				$this->BankInvoiceModel->recommended_invoice_find($id,$chemist_id,$amount,$date);
			}
		}
	}
}	