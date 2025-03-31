<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeCronjobBank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_invoice_or_insert_drdcorp(){

		//Created a GET API
		$url = "https://drdweb.co.in/api-bank/Api01/get_invoice_api";

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

		//print_r($data1);
		if (isset($data1['items'])) {
			foreach ($data1['items'] as $message) {
				$mtime = ($message['mtime']);
				$dispatchtime = ($message['dispatchtime']);
				$date = ($message['date']);
				$vno = ($message['vno']);
				$tagno = ($message['tagno']);
				$gstvno = ($message['gstvno']);
				$pickedby = ($message['pickedby']);
				$checkedby = ($message['checkedby']);
				$deliverby = ($message['deliverby']);
				$amt = ($message['amt']);
				$taxamt = ($message['taxamt']);
				$acno = ($message['acno']);
				$chemist_id = ($message['chemist_id']);
				$status = ($message['status']);
				$insert_time = ($message['insert_time']);

				$dt = array(
					'mtime' => $mtime,
					'dispatchtime' => $dispatchtime,
					'date' => $date,
					'vno' => $vno,
					'tagno' => $tagno,
					'gstvno' => $gstvno,
					'pickedby' => $pickedby,
					'checkedby' => $checkedby,
					'deliverby' => $deliverby,
					'amt' => $amt,
					'taxamt'=>$taxamt,
					'acno'=>$acno,
					'chemist_id'=>$chemist_id,
					'status'=>$status,
					'insert_time'=>$insert_time,
				);

				if (!empty($gstvno)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->BankModel->select_row("tbl_invoice", array('gstvno' => $gstvno));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('gstvno' => $gstvno);
						$this->BankModel->edit_fun("tbl_invoice", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->BankModel->insert_fun("tbl_invoice", $dt);
					}
				}
			}
		}
	}

	public function get_whatsapp_or_insert_drdcorp(){

		//Created a GET API
		$url = "https://drdweb.co.in/api-bank/Api01/get_whatsapp_api";

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

		//print_r($data1);
		
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