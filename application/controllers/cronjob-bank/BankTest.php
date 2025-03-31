<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankTest extends CI_Controller 
{
	public function __construct(){

		parent::__construct();

		$this->load->model("model-local/BankModel");
		//$this->load->model("model-drdweb/InvoiceModel");
	}

	public function test(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://www.drdistributor.com/my_api/Api46/get_top_menu_api',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => array('api_key' => 'xxx','user_type' => 'chemist','user_altercode' => 'v153','user_password' => '11223344','chemist_id' => ''),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}
}