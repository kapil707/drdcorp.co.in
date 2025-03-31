<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_bank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();

		$this->load->model("model-local/BankModel");
		//$this->load->model("model-drdweb/InvoiceModel");
	}

	public function kkss(){
		$json_data = '{
            "messages": [
                {
                    "body": "S-874 Se A/M=2000. Cash=1786.",
                    "date": "2024-05-21T03:01:25.000Z",
                    "extracted_text": "oO 1815 OBA @ Secure Environment...",
                    "from_number": "+919910402492",
                    "id": "D413C0F5EBA913620DBE321AAD0A2F36",
                    "ist_timestamp": "2024-05-21 08:31:25 IST",
                    "screenshot_image": "/v1/chat/646f5b15ab5ee824ea9bdd5f/files/664c0e9116fd7e000b220f35/download",
                    "sender_name_place": "Online Details",
                    "timestamp": "1716260485",
                    "vision_text": "18:15 <Transaction Summary..."
                }
            ]
        }';

        // Decode JSON data
        $messages = json_decode($json_data, true)['messages'];
		print_r($messages);
	}
	
	public function bank_processing(){

		$this->get_invoice();
		$this->get_sms();
		$this->get_statment();
		$this->bank_check_in_whatsapp();
		$this->get_whatsapp_message();
	
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='0' limit 100");
		$result = $result->result();
		foreach($result as $row){

			$received_from 	= $row->received_from;

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
				'find_by'=>$find_by,
				'status'=>1,
				'process_name'=>$process_name,
				'process_value'=>$process_value,
				'find_chemist_id'=>$find_chemist_id,
				
				'from_value'=>$process_value,
				'from_value_find'=>$process_name,
				'chemist'=>$find_chemist_id,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}
	
	public function get_invoice(){
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' limit 100");
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
				'find_invoice_chemist_id'=>$invoice,
				
				'invoice'=>$invoice,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}
	
	public function get_sms(){

		echo " get_sms ";
		$date = date('Y-m-d');

		$result = $this->BankModel->select_query("select * from tbl_sms where status='0' limit 100");
		$result = $result->result();
		foreach($result as $row){
			$message_body = $row->message_body;
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
				$status = 0;
			} else {
				$received_from = "Received from information not found";
				$status = 1;
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
			
			$_id = $row->id;
			
			$row_new = $this->BankModel->select_query("select id from tbl_bank_processing where upi_no='$upi_no'");
			$row_new = $row_new->row();
			
			if(empty($row_new->id)){
				$type = "SMS";
				$dt = array(
					'status'=>$status,
					'amount'=>$amount,
					'date'=>$getdate,
					'time'=>$gettime,
					'received_from'=>$received_from,
					'from_text'=>$received_from,
					'upi_no'=>$upi_no,
					'orderid'=>$orderid,
					'type'=>$type,
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
		echo " get_statment ";
		$result = $this->BankModel->select_query("select * from tbl_statment where status='0' limit 100");
		$result = $result->result();
		foreach($result as $row){
		
			$amount1 = $row->amount;
			$date = $row->date;
			$text = $row->narrative;
			//$text = trim($text);
			//$text = str_replace("'", "", $text);
			//$text = "+91-9899067942 411801191476 FROM GUPTAMEDICALSTORE 9300966180 CITI0000 9026 NA UBIN0579203";

			$transaction_description1 = $row->transaction_description;
			
			//$mydate = date('Y-m-d', strtotime($statment_date1));
			//echo $statment_date1 = date('Y-m-d', strtotime($statment_date1));
			//echo "<br>";

			// echo $i.". ";
			// $i++;
			// echo $text;
			//$text = str_replace("@ ", "@", $text);
			//echo $text = preg_replace('/@\s/', "@", $text, 1);

			$received_from = "";
			// Use regular expression to extract text after "FROM"

			$from_value = "";
			preg_match("/FROM\s+(\d+)@\s+(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_value)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$from_value = $received_from;
			}

			
			preg_match("/FROM\s+(\d+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_value)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find2: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$from_value = $received_from;
			}

			preg_match("/FROM\s+(\w+)\d+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_value)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find3: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$from_value = $received_from;
			}

			preg_match("/FROM\s+([^\s@]+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_value)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find4: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$from_value = $received_from;
			}

			preg_match("/FROM\s+([^\@]+)@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_value)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find5: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$from_value = $received_from;
			}

			preg_match("/FROM\s+(.*)/", $text, $matches);
			if (!empty($matches) && empty($from_value)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$from_value = $received_from;
			}

			$upi_no = $orderid = $row->customer_reference;
			
			$_id = $row->id;
			$received_from = $from_value;
			if(!empty($received_from)){
				$row_new = $this->BankModel->select_query("select id,received_from,type,status from tbl_bank_processing where upi_no='$upi_no'");
				$row_new = $row_new->row();
				
				if(empty($row_new->id)){
					$status = 0;
					$type = "Statment";
					$dt = array(
						'status'=>$status,
						'amount'=>$amount1,
						'date'=>$date,
						'received_from'=>$received_from,
						'from_text'=>$received_from,
						'upi_no'=>$upi_no,
						'orderid'=>$orderid,
						'type'=>$type,
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
						'status'=>$status,
						'type'=>$type,
						'received_from'=>$received_from,
						'orderid'=>$orderid,
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

	public function get_whatsapp_message()
	{
		echo " get_whatsapp_message ";
		$start_date = date('d/m/Y');
		$end_date 	= date('d/m/Y');

		$sender_name_place = "Online%20Details";

		$parmiter = '';
		$curl = curl_init();
		
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL =>"http://172.105.50.148:5000/messages?from=$start_date&to=$end_date&sender_name_place=$sender_name_place",
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

		$messages = json_decode($response, true); 
		// Convert JSON string to associative 
		// print_r($messages);
		// die();
		foreach ($messages['messages'] as $message) {
            $data = array(
                'body' => $message['body'],
                'date' => date('Y-m-d H:i:s', strtotime($message['date'])),
                'extracted_text' => $message['extracted_text'],
                'from_number' => $message['from_number'],
                'message_id' => $message['id'],
                'ist_timestamp' => date('Y-m-d H:i:s', strtotime($message['ist_timestamp'])),
                'screenshot_image' => $message['screenshot_image'],
                'sender_name_place' => $message['sender_name_place'],
                'timestamp' => $message['timestamp'],
                'vision_text' => $message['vision_text']
            );

            // Call the model function to insert the message
            if ($this->BankModel->add_whatsapp_messages($data)) {
                echo "Message added successfully.";
            } else {
                //echo "Duplicate message, not added.";
            }
        }


		/*array

		if (isset($data['messages'])) {
			foreach ($data['messages'] as $message) {
				$body = isset($message['body']) ? $message['body'] : "Body not found";

				$date = isset($message['date']) ? $message['date'] : "Date not found";

				$extracted_text = isset($message['extracted_text']) ? $message['extracted_text'] : "extracted_text not found";

				$vision_text = isset($message['vision_text']) ? $message['vision_text'] : "vision_text not found";

				$from_number = isset($message['from_number']) ? $message['from_number'] : "Date not found";
				
				$id = isset($message['id']) ? $message['from_number'] : "id not found";

				$screenshot_image = isset($message['screenshot_image']) ? $message['screenshot_image'] : "screenshot_image not found";

				$timestamp = isset($message['timestamp']) ? $message['timestamp'] : "timestamp not found";

				$body = utf8_encode($body);
				$extracted_text = utf8_encode($extracted_text);
				$vision_text = utf8_encode($vision_text);

				$dt = array(
					'body' => $body,
					'date' => $date,
					'extracted_text' => $extracted_text,
					'from_number' => $from_number,
					'message_id' => $id,
					'screenshot_image' => $screenshot_image,
					'timestamp' => $timestamp,
					'vision_text' => $vision_text,
				);
				$this->BankModel->insert_fun("tbl_whatsapp_message", $dt);
			}*/
	}

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
	
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' and done_status=0 limit 100");
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

	public function bank_check_in_whatsapp(){
		echo " bank_check_in_whatsapp ";
		//$result = $this->BankModel->select_query("SELECT tbl_whatsapp_message.id,tbl_whatsapp_message.vision_text,tbl_bank_processing.upi_no,tbl_bank_processing.id as myid FROM tbl_bank_processing, tbl_whatsapp_message WHERE tbl_whatsapp_message.vision_text LIKE CONCAT('%', tbl_bank_processing.upi_no, '%') and tbl_bank_processing.status=1");

		$result = $this->BankModel->select_query("SELECT id,upi_no,amount from tbl_bank_processing where status=1 limit 50");
		$result = $result->result();
		foreach($result as $row){

			$upi_no = trim($row->upi_no);
			$amount = $row->amount;

			$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE REPLACE(`vision_text`, ' ', '') LIKE '%$upi_no%'");
			$row1 = $row1->row();

			if(empty($row1)){
				$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE `vision_text` LIKE '%$upi_no%'");
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
				'status'=>2,
				'whatsapp_id'=>$whatsapp_id,
				'whatsapp_body'=>$whatsapp_body,
				'whatsapp_image'=>$whatsapp_image,
				'whatsapp_body2'=>$whatsapp_body2,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}

	public function delete_duplicate_rec(){
		
		$this->BankModel->select_query("DELETE tbl_bank_processing FROM tbl_bank_processing INNER JOIN ( SELECT MIN(id) as min_id, upi_no FROM tbl_bank_processing GROUP BY upi_no HAVING COUNT(upi_no) > 1 ) AS duplicates ON tbl_bank_processing.id != duplicates.min_id AND tbl_bank_processing.upi_no = duplicates.upi_no where type='sms'");
	}
}