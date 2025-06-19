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

		$result = $this->BankModel->select_query("SELECT * from tbl_bank_essysol where status=0");
		$result = $result->result();
		foreach ($result as $row) {

			// These values will represent the last processed row's details
			$id = $row->id;
			$vdt = $row->vdt;
			$amount = $row->amount;
			$chemist_id = $row->chemist_id;
			$bacno = $row->bacno;
			$mode = $row->mode;
			$chqno = $row->chqno;
			$rcptno = $row->rcptno;
			$upi_no = $row->upi_no;
			$bank_reference = $row->bank_reference;
			$bname = $row->bname;
			$invoice = $row->invoice;
			//$total_line = (int)$row->items_total;
			

			$dt = array(
				'id' => $id,
				'vdt' => $vdt,
				'amount' => $amount,
				'chemist_id' => $chemist_id,
				'bacno' => $bacno,
				'mode' => $mode,
				'chqno' => $chqno,
				'rcptno' => $rcptno,
				'upi_no' => $upi_no,
				'bank_reference' => $bank_reference,
				'bname' => $bname,
				'invoice' => $invoice,
			);
			$jsonArray[] = $dt;
		}
		// Prepare the response structure
		$response = array(
			'success' => "1",
			'message' => 'Data load successfully',
			'items' => $jsonArray,
		);
		// Send JSON response
		header('Content-Type: application/json');
		echo json_encode($response);
	}
}