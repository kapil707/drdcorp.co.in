<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BroadcastModel extends CI_Model
{
	public function __construct() {
        parent::__construct();
    }

	public function insert_broadcast($title,$message,$chemist_id,$insert_type='')
	{
		$dt = array(
			'title'=>$title,
			'message'=>$message,
			'chemist_id'=>$chemist_id,
			'status'=>0,
			'insert_type'=>$insert_type,
			'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'timestamp' => time(),
		);
		return $this->Scheme_Model->insert_fun("tbl_broadcast",$dt);
	}
}