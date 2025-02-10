<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class WhatsAppModel extends CI_Model
{
	public function __construct() {
        parent::__construct();
		// Load model
		$this->load->model("model-drdweb/TokenModel");
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

	public function tbl_whatsapp_email_fail($number,$message,$altercode)
	{
		$where = array('altercode'=>$altercode);
		$row = $this->Scheme_Model->select_row("tbl_whatsapp_email_fail",$where,'','');
		if($row->id=="")
		{
			$this->db->query("insert into tbl_whatsapp_email_fail set altercode='$altercode',mobile='$number',message='$message'");
		}
	}
	
	public function insert_whatsapp($mobile,$message,$chemist_id,$media='',$insert_type='') {

		$dt = array(
			'mobile'=>$mobile,
			'message'=>$message,
			'chemist_id'=>$chemist_id,
			'media'=>$media,
			'insert_type'=>$insert_type,
			'status'=>0,
			'respose'=>'',
			'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'timestamp' => time(),
		);
		return $this->insert_query("tbl_whatsapp_message",$dt);
	}
	
	public function send_whatsapp() {

		$whatsapp_token = $this->TokenModel->get_token("whatsapp_token");
		
		$this->db->limit(25);
		$this->db->where('status',0);
		$query = $this->db->get("tbl_whatsapp_message")->result();
		foreach($query as $row) {

			$id 			= $row->id;
			$mobile 		= $row->mobile;
			$media 			= $row->media;
			$message 		= ($row->message);
			$message 		= str_replace("<br>","\\n",$message);
			$message 		= str_replace("<b>","*",$message);
			$message 		= str_replace("</b>","*",$message);
			$chemist_id 	= $row->chemist_id;
		
			if($media!="")
			{
				$parmiter = '{"phone": "'.$mobile.'","message": "'.$message.'","media": { "file": "'.$media.'" }}';
			}
			if($media=="")
			{
				$parmiter = "{\"phone\":\"$mobile\",\"message\":\"$message\"}";
			}

			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.wassi.chat/v1/messages",
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_ENCODING =>"",
			CURLOPT_MAXREDIRS =>10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION =>CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS =>$parmiter,
			CURLOPT_HTTPHEADER =>array("content-type: application/json","token:$whatsapp_token"),));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			$response = htmlspecialchars($response);
			$response = str_replace("'","&#39;",$response);

			$this->db->query("update `tbl_whatsapp_message` set status=1 WHERE id='$id'");
			$this->db->query("update `tbl_whatsapp_message` set respose='$response' WHERE id='$id'");
		}
	}
	
	public function insert_whatsapp_group($mobile,$message,$media='') {

		$dt = array(
			'mobile'=>$mobile,
			'message'=>$message,
			'media'=>$media,
			'status'=>0,
			'respose'=>'',
			'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'timestamp' => time(),
		);
		$this->insert_query("tbl_whatsapp_group_message",$dt);
	}
	
	public function send_whatsapp_group() {

		$whatsapp_token = $this->TokenModel->get_token("whatsapp_token");
		
		$this->db->limit(25);
		$this->db->where('status',0);
		$query = $this->db->get("tbl_whatsapp_group_message")->result();
		foreach($query as $row) {
			
			$id 			= $row->id;
			$mobile 		= $row->mobile;
			$message 		= ($row->message);
			$message 		= str_replace("<br>","\\n",$message);
			$message 		= str_replace("<b>","*",$message);
			$message 		= str_replace("</b>","*",$message);
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.wassi.chat/v1/messages",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"group\":\"$mobile\",\"priority\":\"high\",\"message\":\"$message\"}",
			CURLOPT_HTTPHEADER => array(
			"content-type: application/json","token:$whatsapp_token"),));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			$this->db->query("update `tbl_whatsapp_group_message` set status=1 WHERE id='$id'");

			$this->db->query("update `tbl_whatsapp_group_message` set respose='$response' WHERE id='$id'");
		}
	}
}