<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeBankInvoice extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		//Load model
		$this->load->model("model-bank/BankModel");
	}

	public function upload(){

		echo "upload";
		//Created a GET API
		$url = "https://www.drdweb.co.in/api-bank/Api01/get_invoice_api";

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
}