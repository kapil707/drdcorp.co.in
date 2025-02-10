<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TokenModel extends CI_Model
{
	public function __construct() {
        parent::__construct();
    }

	function insert_query($tbl,$dt) {

		if($this->db->insert($tbl,$dt)) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	function update_query($tbl,$dt,$where) {

		if($this->db->update($tbl,$dt,$where)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function insert_token($token_name,$token_value) {

		$dt = array(
			'token_name'=>$token_name,
			'token_value'=>$token_value,
			'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'timestamp' => time(),
		);
		return $this->insert_query("tbl_token",$dt);
	}

	public function update_token($token_name,$token_value) {

		$where = array('token_name'=>$token_name);
		$dt = array(
			'token_value'=>$token_value,
			'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'timestamp' => time(),
		);
		return $this->update_query("tbl_token",$dt,$where);
	}
	
	public function get_token($token_name) {

		$this->db->select("token_value");
		$this->db->where('token_name',$token_name);
		$row = $this->db->get("tbl_token")->row();
		if(!empty($row)){
			return $row->token_value;
		}else{
			return "";
		}
	}
}