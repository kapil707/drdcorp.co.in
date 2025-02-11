<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CorprateReport extends CI_Model
{
    protected $db; // इसे पहले ही डिफाइन कर दें

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database(); // या ऑटो-लोड से लोड करें
    }

	function sendReport() {
		$this->db->select('tbl_corporate.memail, stock_and_sales_analysis_daily_email, item_wise_report_daily_email, chemist_wise_report_daily_email, tbl_corporate_other.status, tbl_corporate.compcode, tbl_corporate.company_full_name, tbl_corporate.division, tbl_corporate.id, tbl_corporate_other.id as id1, tbl_corporate.code');
        $this->db->from('tbl_corporate');
        $this->db->join('tbl_corporate_other', 'tbl_corporate.code = tbl_corporate_other.code');
        $this->db->where('tbl_corporate_other.daily_status', 0);
        $this->db->limit(1);

        $query = $this->db->get();
        $result = $query->result();
		foreach($result as $row)
		{
			/************************************************/
			echo $memail         = $row->memail;
		}
	}
}