<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobCorporateReport extends CI_Controller 
{
	public $email;
	public $phpmailer_lib;

	public function __construct(){
		parent::__construct();
		
		$this->load->library('phpmailer_lib');
		//Load model
		$this->load->model("model-drdcorp/CorporateReport");
	}

	public function folder_create()
	{
		$this->CorporateReport->FolderCreate();
		echo "Folder created";
	}
	
	public function send_email()
	{
		$this->CorporateReport->sendReport();
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
		$message = $this->get_body();
		
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

	public function get_body(){

		$today_date = date("d-M-y");
		
		$file_name_1 = "ChemistWiseReport-2251-ZU.xlsx";
		$folder_dt = date('Y-m-d');
		if($file_name_1){
			$file_name1 = "corporate_report/".$folder_dt."/".$file_name_1;
			$url1 = "https://www.drdcorp.co.in/".$file_name1;
			$url1 = "<a href='".$url1."'>".$file_name_1."</a><br><br>";
		}

		$company_full_name = "test";
		$user_division = "xx";

		$subject = "Daily Report (".$today_date.") ".ucwords(strtolower($company_full_name))." (".$user_division.")";
		$message = "Sir<br>It is the sales data as you have sought<br>";
		$message.= $url1;
		//$message.= $url2;
		//$message.= $url3;
		$message.= "Please download the file as in the links above for your reference.<br>Thanks and regards<br><br>D.R. Distributors Pvt Ltd<br>Notice & Disclaimer - This email and any files transmitted with it contain Proprietary, privileged and confidential information and/or information protected by intellectual property rights and is only for the use of the intended recipient of this message. If you are not the intended recipient, please delete or destroy this and all copies of this message along with the attachments immediately. You are hereby notified and directed that (1) if you are not the named and intended addressee you shall not disseminate, distribute or copy this e-mail, and (2) any offer for product/service shall be subject to a final evaluation of relevant patent status. Company cannot guarantee that e-mail communications are secure or error-free, as information could be intercepted, corrupted, amended, lost, destroyed, arrive late or incomplete, or may contain viruses. Company does not accept responsibility for any loss or damage arising from the use of this email or attachments.";

		return $message;
	}
}