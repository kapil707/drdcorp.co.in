<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeBankSMS extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		//Load model
		$this->load->model("model-bank/BankModel");
	}

	public function download() {
		$jsonArray = array();
		$items = "";

		$result = $this->BankModel->select_query("select * from tbl_sms where upload_status=0 limit 1000");
		$result = $result->result();
		foreach($result as $row) {
			
			$id = $row->id;
			$message_id = $row->message_id;
			$sender = $row->sender;
			$message_body = $row->message_body;
			$message_type = $row->message_type;
			$status = $row->status;
			$date = $row->date;
			$time = $row->time;
			$datetime = $row->datetime;

			$dt = array(
				'message_id' => $message_id,
				'sender' => $sender,
				'message_body' => $message_body,
				'message_type' => $message_type,
				'status'=>$status,
				'date'=>$date,
				'time'=>$time,
				'datetime'=>$datetime,
			);
			$jsonArray[] = $dt;

			$where = array('id'=>$id);
			$dt = array(				
				'upload_status'=>'1',
			);
			$this->BankModel->edit_fun("tbl_sms", $dt,$where);
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
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => [],
			);
		}
		
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}