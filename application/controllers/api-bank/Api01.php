<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Api01 extends CI_Controller {	

	public function __construct(){
		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_sms_api() {
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
				'success' => "0",
				'message' => '502 error',
			);
		}
		
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}

	public function get_last_message_id() {

		/*$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();*/

		$my_id = 0;
		$row = $this->BankModel->select_query("SELECT max(message_id) as myid FROM `tbl_sms` where message_type='ReadAll'");
		$row = $row->row();
		if(!empty($row)){
			$my_id = $row->myid;
		}
		if(empty($my_id)){
			$my_id = 0;
		}
		

		$response = array(
            'success' => "1",
            'message' => 'Data add successfully',
			'my_id' => $my_id,
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo "[".json_encode($response)."]";
	}
	
	public function upload_sms_background() {

		$message_id		= $_POST['messageId'];
		$sender			= $_POST['sender'];
		$message_body 	= $_POST["message_body"];
		$message_type   = "Background";

		$message_date 	= $_POST['message_date'];
		$message_time 	= $_POST['message_time'];

		$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();

		if(!empty($message_date)){
			$date = $message_date;
		}
		if(!empty($message_time)){
			$time = $message_time;
		}

		//yha code vasay sahi ha taki id me koi dikat na ho
		$row = $this->BankModel->select_query("SELECT max(message_id) as myid FROM `tbl_sms`");
		$row = $row->row();
		if(!empty($row)){
			$message_id = $row->myid + 1;
		}
		if(empty($message_id)){
			$message_id = 0;
		}

		$dt = array(
			'message_id'=>$message_id,
			'sender'=>$sender,
			'message_body'=>$message_body,
			'message_type'=>$message_type,
			'date'=>$date,
			'time'=>$time,
			'datetime'=>$datetime,
		);
		$this->BankModel->insert_fun("tbl_sms", $dt);

		$response = array(
            'success' => "1",
            'message' => 'Data add successfully',
			'sender' => $sender,
			'message_id' => $message_id,
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}

	
	public function upload_sms() {

		$message_id   	= $_POST["_id"];
		$sender			= $_POST['sender'];
		$message_body 	= $_POST["message_body"];
		$message_type   = "ReadAll";

		$message_date 	= $_POST['message_date'];
		$message_time 	= $_POST['message_time'];

		$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();

		if(!empty($message_date)){
			$date = $message_date;
		}
		if(!empty($message_time)){
			$time = $message_time;
		}

		$this->BankModel->select_query("delete from tbl_sms where message_type='Background'");
		$this->BankModel->select_query("delete from tbl_sms where message_id='$message_id'");

		$dt = array(
			'message_id'=>$message_id,
			'sender'=>$sender,
			'message_body'=>$message_body,
			'message_type'=>$message_type,
			'date'=>$date,
			'time'=>$time,
			'datetime'=>$datetime,
		);
		$this->BankModel->insert_fun("tbl_sms", $dt);
		$response = array(
            'success' => "1",
            'message' => 'Data add successfully',
			'sender' => $sender,
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo "[".json_encode($response)."]";
	}

	public function get_upload_sms() {
		
		if(!empty($_REQUEST)){
			$from_date 	= $_REQUEST["from_date"];
			$to_date	= $_REQUEST['to_date'];

			$jsonArray = array();

			$items = "";
			if(!empty($from_date) && !empty($to_date)){

				$result = $this->BankModel->select_query("select tbl_sms.*,tbl_bank_processing.final_chemist as chemist_id from tbl_sms left join tbl_bank_processing on tbl_sms.upi_no=tbl_bank_processing.upi_no where tbl_sms.date BETWEEN '$from_date' AND '$to_date'");
				$result = $result->result();

				foreach($result as $row){

					$id = $row->id;
					$sender = $row->sender;
					$message_body = $row->message_body;
					$amount = $row->amount;
					$upi_no = $row->upi_no;
					$chemist_id = $row->chemist_id;
					$date = $row->date;
					$time = $row->time;
					$datetime = $row->datetime;

					$dt = array(
						'id' => $id,
						'sender' => $sender,
						'message_body'=>$message_body,
						'amount'=>$amount,
						'upi_no'=>$upi_no,
						'chemist_id'=>$chemist_id,
						'date'=>$date,
						'time'=>$time,
						'datetime'=>$datetime,
					);
					$jsonArray[] = $dt;
				}
			}

			$items = $jsonArray;
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
			);
		}else{
			$response = array(
				'success' => "0",
				'message' => '502 error',
			);
		}

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}