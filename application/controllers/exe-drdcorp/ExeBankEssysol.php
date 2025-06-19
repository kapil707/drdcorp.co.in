<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeBankEssysol extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		//Load model
		$this->load->model("model-bank/BankModel");
	}

	public function download(){

		$jsonArray_lines = array();
		$jsonArray = array();

		$total_line = 0;
		$date = date("Y-m-d");
		$download_time = date("YmdHi");

		$result = $this->BankModel->query("SELECT * from tbl_bank_essysol where status=0")->result();
		// Process each row from the result set
		foreach ($result as $row) {

			// These values will represent the last processed row's details
			$order_id = $row->id;
			$user_type = $row->user_type;
			$chemist_id = $row->chemist_id;
			$salesman_id = $row->salesman_id;
			$remarks = $row->remarks;
			$date = $row->date;
			$time = $row->time;
			$total_line = (int)$row->items_total;

			$acno = $row->code;
			$slcd = $row->slcd;
			
			/************************************************************ */
			$result1 = $this->db->query("SELECT * from tbl_cart where order_id='$order_id'")->result();	
			foreach ($result1 as $row1) {		
				$dt = array(
					'online_id' => $row1->id,
					'i_code' => $row1->i_code,
					'item_code' => $row1->item_code,
					'quantity' => $row1->quantity,
					'sale_rate' => $row1->sale_rate,
				);
				$jsonArray_lines[] = $dt;
			}
			/************************************************************ */
		}

		// If result is not empty, proceed to fetch chemist details
		if (!empty($result)) {
			// Final order details
			$dt = array(
				'order_id' => $order_id,
				'chemist_id' => $chemist_id,
				'salesman_id' => $salesman_id,
				'user_type' => $user_type,
				'acno' => $acno,
				'slcd' => $slcd,
				'remarks' => $remarks,
				'date' => $date,
				'time' => $time,
				'total_line' => $total_line,
			);
			$jsonArray[] = $dt;

			// Prepare the response structure
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $jsonArray,
				'items_other' => $jsonArray_lines,
			);
		} else {
			// If no result found, prepare an empty response
			$response = array(
				'success' => "0",
				'message' => 'No data found',
				'items' => "",
				'items_other' => "",
			);
		}
		// Send JSON response
		header('Content-Type: application/json');
		echo json_encode($response);
	}
}