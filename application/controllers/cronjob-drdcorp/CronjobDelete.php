<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobDelete extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");	
	}
	
	public function delete_old_records(){

		$time  	= time();

		$day15  = date("Y-m-d", strtotime("-15 days", $time));

		echo $day15;
		die();
		$where = array(
			'date' => $day15,
		);
		$this->BankModel->delete_fun("tbl_bank_processing", $where);
	}
}