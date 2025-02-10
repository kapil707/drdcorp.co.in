<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Drd_Master_Model extends CI_Model  
{	
	public $db_master;
	public function __construct(){

		parent::__construct();

		$this->db_master = $this->load->database('db_master', TRUE);
	}

	function select_query($select,$tbl,$where="")
	{
		$db_master = $this->db_master;

		$db_master->select($select);
		if($where!="")
		{
			$db_master->where($where);
		}
		return $db_master->get($tbl)->result();		
	}

	function select_query_row($select,$tbl,$where="")
	{
		$db_master = $this->db_master;
		
		$db_master->select($select);
		if($where!="")
		{
			$db_master->where($where);
		}
		return $db_master->get($tbl)->row();		
	}
	function insert_query($tbl,$dt)
	{
		$db_master = $this->db_master;

		if($db_master->insert($tbl,$dt))
		{
			return $db_master->insert_id();
		}
		else
		{
			return false;
		}
	}
	function update_query($tbl,$dt,$where)
	{
		$db_master = $this->db_master;

		if($db_master->update($tbl,$dt,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function delete_query($tbl,$where)
	{
		$db_master = $this->db_master;

		if($db_master->delete($tbl,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function create_token_key() {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#%^&*';
		$randomString = '';
	 
		for ($i = 0; $i < 11; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}
	 
		return $randomString;
	}

	function full_query_row($query)
	{
		$db_master = $this->db_master;
		return $db_master->query($query)->row();		
	}

	function full_query_result($query)
	{
		$db_master = $this->db_master;
		return $db_master->query($query)->result();		
	}
}