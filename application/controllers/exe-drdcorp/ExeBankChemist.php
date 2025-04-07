<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeBankChemist extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		//Load model
		$this->load->model("model-bank/BankModel");
	}

	public function upload(){

		echo "upload";
		//Created a GET API
		$url = "https://www.drdweb.co.in/api-bank/Api01/get_chemist_api";

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
				$code = ($message['code']);
				$altercode = ($message['altercode']);
				$groupcode = ($message['groupcode']);
				$name = ($message['name']);
				$type = ($message['type']);
				$trimname = ($message['trimname']);
				$address = ($message['address']);
				$address1 = ($message['address1']);
				$address2 = ($message['address2']);
				$address3 = ($message['address3']);
				$telephone = ($message['telephone']);
				$telephone1 = ($message['telephone1']);
				$mobile = ($message['mobile']);
				$email = ($message['email']);
				$gstno = ($message['gstno']);
				$statecode = ($message['statecode']);
				$status = ($message['status']);
				$invexport = ($message['invexport']);
				$slcd = ($message['slcd']);
				$narcolicence = ($message['narcolicence']);
				$insert_time = ($message['insert_time']);

				$dt = array(
					'code' => $code,
					'altercode' => $altercode,
					'groupcode' => $groupcode,
					'name' => $name,
					'type' => $type,
					'trimname' => $trimname,
					'address' => $address,
					'address1' => $address1,
					'address2' => $address2,
					'address3' => $address3,
					'telephone'=>$telephone,
					'telephone1'=>$telephone1,
					'mobile'=>$mobile,
					'email'=>$email,
					'gstno'=>$gstno,
					'statecode'=>$statecode,
					'status'=>$status,
					'invexport'=>$invexport,
					'slcd'=>$slcd,
					'narcolicence'=>$narcolicence,
					'insert_time'=>$insert_time,
				);

				if (!empty($code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->BankModel->select_row("tbl_chemist", array('code' => $code,'slcd' => $slcd));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('code' => $code,'slcd' => $slcd);
						$this->BankModel->edit_fun("tbl_chemist", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->BankModel->insert_fun("tbl_chemist", $dt);
					}
				}
			}
		}
	}
}