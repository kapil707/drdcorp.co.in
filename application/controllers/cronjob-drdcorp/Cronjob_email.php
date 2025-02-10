<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_email extends CI_Controller 
{
	public $email;
	public $phpmailer_lib;

	public function __construct(){
		parent::__construct();
		
		$this->load->library('phpmailer_lib');
		// Load model
		//$this->load->model("model-drdcorp/EmailModel");
	}
	
	public function send_email() {
		$this->EmailModel->send_email();
		echo "send email";
	}
	
	public function test()
	{
		echo "hello";
	}

	public function test_email()
	{
		$email = $this->phpmailer_lib->load();
		
		$subject = "drd local test ok";
		$message = "drd local test ok";
		
		$addreplyto 		= "application@drdistributor.com";
		$addreplyto_name 	= "Vipul DRD";
		$server_email 		= "application@drdistributor.com";
		//$server_email 	= "send@drdindia.com";
		$server_email_name 	= "DRD TEST";
		$email1 			= "kapil707sharma@gmail.com";
		
		$email->AddReplyTo($addreplyto,$addreplyto_name);
		$email->SetFrom($server_email,$server_email_name);
		$email->AddAddress($email1);
		
		$email->Subject   	= $subject;
		$email->Body 		= $message . time();

		$email->IsHTML(true);

		// SMTP configuration
		$email->isSMTP();
		$email->SMTPAuth   = 3; 
		$email->SMTPSecure = "tls";  //tls
		$email->Host     = "smtp.gmail.com";
		$email->Username   = "application2@drdindia.com";
		$email->Password   = "drd@oct23";
		$email->Port     = 587;

		if($email->send()){
			echo 'Message has been sent';
		}else{
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		}
		echo "<pre>";
		print_r($email);
	}

	public function test_email2()
	{
		$email = $this->phpmailer_lib->load();
		
		$subject = "drd local test ok";
		$message = "drd local test ok";
		
		$addreplyto 		= "application@drdistributor.com";
		$addreplyto_name 	= "Vipul DRD";
		$server_email 		= "application@drdistributor.com";
		//$server_email 	= "send@drdindia.com";
		$server_email_name 	= "DRD TEST";
		$email1 			= "kapil707sharma@gmail.com";
		
		$email->AddReplyTo($addreplyto,$addreplyto_name);
		$email->SetFrom($server_email,$server_email_name);
		$email->AddAddress($email1);
		
		$email->Subject   	= $subject;
		$email->Body 		= $message . time();

		$email->IsHTML(true);

		$email->isSMTP();
		$email->Host       = 'mail.drdcorp.co.in'; // SMTP Server
		$email->SMTPAuth   = true; // Enable SMTP Authentication
		$email->Username   = 'report@drdcorp.co.in'; // SMTP Username
		$email->Password   = 'Kapil1234!@#$'; // SMTP Password
		$email->SMTPSecure = 'ssl'; // Use SSL (as your SMTP port is 465)
		$email->Port       = 465; // SMTP Port

		if($email->send()){
			echo 'Message has been sent';
		}else{
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		}
		echo "<pre>";
		print_r($email);
	}
}