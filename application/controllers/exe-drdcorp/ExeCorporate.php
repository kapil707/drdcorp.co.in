<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeCorporate extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		//Load model
		$this->load->model("model-drdcorp/CorporateReport");
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
			
			print_r($data);

		}
	}
}