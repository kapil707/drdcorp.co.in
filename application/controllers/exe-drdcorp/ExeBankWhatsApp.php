<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeBankWhatsApp extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		//Load model
		$this->load->model("model-bank/BankModel");
	}
	public function upload()
	{
		// Data ko read karna (input stream se)
		$inputData = file_get_contents("php://input");

		// JSON data ko PHP array me convert karna
		$data = json_decode($inputData, true);

		
		// Data ko check karna
		if ($data && is_array($data)) {
			// Aap yaha data ko process kar sakte hain, jaise ki database me save karna, logging karna, etc.
			
			//print_r($data);

			// Example: Data ko print karna (ya log karna)
			//file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

			$code_array = array();
			foreach ($data as $record) {
				$code_array[] = $record['message_id'];
				
				$message_id = $record['message_id'];
				$body = $record['body'];
				$date = $record['date'];
				$extracted_text = $record['extracted_text'];
				$from_number = $record['from_number'];
				$ist_timestamp = $record['ist_timestamp'];
				$screenshot_image = $record['screenshot_image'];
				$sender_name_place = $record['sender_name_place'];
				$timestamp = $record['timestamp'];
				$vision_text = $record['vision_text'];
				$reply_id = $record['reply_id'];

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
					'reply_id' => $reply_id,
				);

				if (!empty($message_id)) {
					//Check karo agar record already exist karta hai
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
			$commaSeparatedString = implode(',', $code_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}