<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class StockLowModel extends CI_Model  
{
	public $db_medicine;
	public function __construct(){

		parent::__construct();

		$this->db_medicine = $this->load->database('default2', TRUE);
	}
	
	function get_stock_low_report_admin()
	{
		$result = $this->db->query("SELECT * from tbl_stock_low")->result();
		return $result;
	}
	
	function get_stock_low_report()
	{		
		$date = date("Y-m-d");
		
		$items = "";
		$result = $this->db->query("SELECT `i_code`, `item_name`, COUNT(*) AS total FROM tbl_stock_low where status=0 GROUP BY `i_code`, `item_name` HAVING COUNT(*) > 0;")->result();
		$i = 0;
		foreach($result as $row){
			$i++;
			$i_code 	= $row->i_code;
			$item_name 	= $row->item_name;
			$total 		= $row->total;
			
			$items.="$i. $item_name ($i_code) :- ($total)\\n";
			
			$this->db->query("update tbl_stock_low set status=1 where i_code='$i_code'");
		}
		return $items;
	}
	
	function get_stock_low_now_available_report_admin()
	{
		$result = $this->db->query("SELECT low.i_code,low.item_name,tbl_medicine.batchqty,COUNT(*) AS total FROM tbl_stock_low as low inner join tbl_medicine on tbl_medicine.i_code=low.i_code where tbl_medicine.batchqty!='0' GROUP BY low.i_code,low.item_name,tbl_medicine.batchqty HAVING COUNT(*) > 0")->result();
		return $result;
	}
	
	function get_stock_low_now_available_report() {
		
		$db_medicine = $this->db_medicine;
		
		$items = "";
		$result = $this->db->query("SELECT low.i_code,low.item_name,tbl_medicine.batchqty,COUNT(*) AS total FROM tbl_stock_low as low inner join tbl_medicine on tbl_medicine.i_code=low.i_code where tbl_medicine.batchqty!='0' and available_status=0 GROUP BY low.i_code,low.item_name,tbl_medicine.batchqty HAVING COUNT(*) > 0")->result();
		$i = 0;
		foreach($result as $row){
			$i++;
			$i_code 	= $row->i_code;
			$item_name 	= $row->item_name;
			$batchqty 	= $row->batchqty;
			$chemist_id = $this->get_chemist_id($i_code);
			
			$items.="$i. $item_name ($i_code) Chemist Code:- $chemist_id \\n";
			
			$this->db->query("update tbl_stock_low set available_status=1 where i_code='$i_code'");
		}
		return $items;
	}
	
	function get_chemist_id($i_code) {
		
		$chemist_id = "";
		$result = $this->db->query("select chemist_id from tbl_stock_low where i_code='$i_code'")->result();
		foreach($result as $row){
			$chemist_id.= $row->chemist_id.",";
		}
		if ($chemist_id != '') {
			$chemist_id = substr($chemist_id, 0, -1);
		}
		return $chemist_id;
	}
}