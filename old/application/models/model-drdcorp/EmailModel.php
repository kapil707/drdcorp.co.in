<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EmailModel extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	function insert_query($tbl,$dt) {

		if($this->db->insert($tbl,$dt)) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function insert_email($user_email_id='',$subject='',$message='',$file_name1='',$file_name2='',$file_name3='',$file_name_1='',$file_name_2='',$file_name_3='',$mail_server='',$email_function='',$email_other_bcc='') {

		$update_at = time();

		$dt = array(
			'user_email_id'=>$user_email_id,
			'subject'=>$subject,
			'message'=>$message,
			'file_name1'=>$file_name1,
			'file_name2'=>$file_name2,
			'file_name3'=>$file_name3,
			'file_name_1'=>$file_name_1,
			'file_name_2'=>$file_name_2,
			'file_name_3'=>$file_name_3,
			'mail_server'=>$mail_server,
			'email_function'=>$email_function,
			'email_other_bcc'=>$email_other_bcc,
			'update_at'=>$update_at,
			'status'=>0,
			'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'timestamp' => time(),
		);
		$this->insert_query("tbl_email_send",$dt);
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

	public function check_email_cc($email_function_id='',$email=''){
		$email_cc = "";
		$this->db->limit(1);
		$this->db->where('email_function_id',$email_function_id);
		$this->db->where('email',$email);
		$row = $this->db->get("tbl_email_cc")->row();
		if(!empty($row->id)){
			$email_cc = $row->email_cc;
		}
		return $email_cc;
	}
	
	public function send_email() {
	    $time  = time();
		$mytime_min 	= date("i",$time);
		$mytime_ganta 	= date("H",$time);
	    
		//error_reporting(0);
		$this->db->limit(1);
		$this->db->where('status','0');		
		$this->db->order_by('id','desc');
		$query = $this->db->get("tbl_email_send")->result();
		
		$this->load->library('phpmailer_lib');
		$email = $this->phpmailer_lib->load();
			
		foreach($query as $row)
		{
			/************************************************/
			$id 			= $row->id;
			$user_email_id 	= $row->user_email_id;
			$subject 		= ($row->subject);
			$message 		= ($row->message);
		    $email_function = $row->email_function;
			$file_name1 	= $row->file_name1;
			$file_name2 	= $row->file_name2;
			$file_name3 	= $row->file_name3;
			$file_name_1 	= $row->file_name_1;
			$file_name_2 	= $row->file_name_2;
			$file_name_3 	= $row->file_name_3;
			$mail_server 	= $row->mail_server;
			$email_other_bcc= $row->email_other_bcc;
			if($row->email_other_bcc=="")
			{
				$email_other_bcc="";
			}
			
			/************************************************/
			
			/************************************************/
			$this->db->where('email_function',$email_function);
			$row1 = $this->db->get("tbl_email")->row();
			
			$addreplyto 		= $row1->addreplyto;
			$addreplyto_name 	= $row1->addreplyto_name;
			$server_email 		= $row1->server_email;
			$server_email_name 	= $row1->server_email_name;
			$email1 			= $row1->email;
			$email_bcc 			= $row1->email_bcc;
			$live_or_demo 		= $row1->live_or_demo;
			
			$email->AddReplyTo($addreplyto,$addreplyto_name);
			$email->SetFrom($server_email,$server_email_name);
			/************************************************/
			
			if($mytime_min=="00" && $mytime_ganta%2==0){
			    $email_bcc = "kapildrd@gmail.com";
			}

			/*
			$email_cc = $this->check_email_cc($row1->id,$email1);
			if($email_cc!=""){
			    $email_bcc = $email_cc;
			}
			
			/************************************************/
			
			$email->Subject   	= $subject;
			$email->Body 		= $message;		
			if($live_or_demo=="Demo")
			{
				//$email->AddAddress($user_email_id);
				if(!empty($email1)) {
					$email->AddAddress($email1);
				}
				$email_bcc = explode (",",$email_bcc);
				foreach($email_bcc as $bcc)
				{
					$email->addBcc($bcc);
				}
				$email_other_bcc = explode (",",$email_other_bcc); 				
				foreach($email_other_bcc as $email_other_bcc_ok)
				{
					$email->addBcc($email_other_bcc_ok);
				}
			}
			else
			{
				$email->AddAddress($user_email_id);
				if(!empty($email1)) {
					$email->addBcc($email1);
				}
				$email_bcc = explode (",",$email_bcc);
				foreach($email_bcc as $bcc)
				{
					$email->addBcc($bcc);
				}
				$email_other_bcc = explode (",",$email_other_bcc); 				
				foreach($email_other_bcc as $email_other_bcc_ok)
				{
					$email->addBcc($email_other_bcc_ok);
				}
			}
			if($file_name1)
			{
				if($file_name_1)
				{
					$email->addAttachment($file_name1,$file_name_1);
				}
				else
				{
					$email->addAttachment($file_name1);
				}
			}
			if($file_name2)
			{
				if($file_name_2)
				{
					$email->addAttachment($file_name2,$file_name_2);
				}
				else
				{
					$email->addAttachment($file_name2);
				}
			}
			if($file_name3)
			{
				if($file_name_3)
				{
					$email->addAttachment($file_name3,$file_name_3);
				}
				else
				{
					$email->addAttachment($file_name3);
				}
			}
			
			/************************************************/
			//$this->db->query("delete from tbl_email_send where id='$id'");
			/************************************************/

			$this->db->query("update tbl_email_send set status='1' where id='$id'");
			
			$email->IsHTML(true);		
			if($email->Send()){
				echo "Mail Sent";
			}
			else{
				echo "Mail Failed";
			}
			if($file_name1)
			{
				unlink($file_name1);
			}
			if($file_name2)
			{
				unlink($file_name2);
			}
			if($file_name3)
			{
				unlink($file_name3);
			}
		}
	}
}