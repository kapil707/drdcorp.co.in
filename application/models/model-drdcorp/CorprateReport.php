<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CorprateReport extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function FolderCreate()
	{
		$vdt   = date("Y-m-d");

		if (!file_exists('corporate_report/'.$vdt)) {
			mkdir('corporate_report/'.$vdt, 0777, true);
		}
	}

	function sendReport() {
		$this->db->select('tbl_corporate.memail, stock_and_sales_analysis_daily_email, item_wise_report_daily_email, chemist_wise_report_daily_email, tbl_corporate_other.status, tbl_corporate.compcode, tbl_corporate.company_full_name, tbl_corporate.division, tbl_corporate.id, tbl_corporate_other.id as id1, tbl_corporate.code');
        $this->db->from('tbl_corporate');
        $this->db->join('tbl_corporate_other', 'tbl_corporate.code = tbl_corporate_other.code');
        $this->db->where('tbl_corporate_other.daily_status', 0);
        $this->db->limit(10);

        $query = $this->db->get();
        $result = $query->result();
		foreach($result as $row)
		{
			/************************************************/
			$memail     = $row->memail;
            $code       = $row->code;
            $compcode   = $row->compcode;
            $division   = $row->division;
            $company_full_name = $row->company_full_name;
            
            $this->get_body($email,$code,$compcode,$division,$company_full_name);
		}
	}

    public function get_body($email,$code,$compcode,$division,$company_full_name){

		$today_date = date("d-M-y");		
		$folder_dt = "2025-02-11";//date('Y-m-d');

		$file_name1 = "ChemistWiseReport.xlsx";
		$file_name_1 = "ChemistWiseReport-$code-$compcode-$division.xlsx";
		$file_name2 = "ItemWiseReport.xlsx";
		$file_name_2 = "ItemWiseReport-$code-$compcode-$division.xlsx";
		$file_name3 = "SaleAndStockAnalysis.xlsx";
		$file_name_3 = "SaleAndStockAnalysis-$code-$compcode-$division.xlsx";
		if($file_name_1){
			$file = "corporate_report/".$folder_dt."/".$file_name_1;
			$url1 = "https://www.drdcorp.co.in/".$file;
			$url1 = "<a href='".$url1."'>".$file_name1."</a><br><br>";
		}
		if($file_name_2){
			$file = "corporate_report/".$folder_dt."/".$file_name_2;
			$url2 = "https://www.drdcorp.co.in/".$file;
			$url2 = "<a href='".$url2."'>".$file_name2."</a><br><br>";
		}
		if($file_name_3){
			$file = "corporate_report/".$folder_dt."/".$file_name_3;
			$url3 = "https://www.drdcorp.co.in/".$file;
			$url3 = "<a href='".$url3."'>".$file_name3."</a><br><br>";
		}

		$subject = "Daily Report (".$today_date.") ".ucwords(strtolower($company_full_name))." (".$division.")";
		$message = "Sir<br>It is the sales data as you have sought<br>";
		$message.= $url1;
		$message.= $url2;
		$message.= $url3;
		$message.= "Please download the file as in the links above for your reference.<br>Thanks and regards<br><br>D.R. Distributors Pvt Ltd<br>Notice & Disclaimer - This email and any files transmitted with it contain Proprietary, privileged and confidential information and/or information protected by intellectual property rights and is only for the use of the intended recipient of this message. If you are not the intended recipient, please delete or destroy this and all copies of this message along with the attachments immediately. You are hereby notified and directed that (1) if you are not the named and intended addressee you shall not disseminate, distribute or copy this e-mail, and (2) any offer for product/service shall be subject to a final evaluation of relevant patent status. Company cannot guarantee that e-mail communications are secure or error-free, as information could be intercepted, corrupted, amended, lost, destroyed, arrive late or incomplete, or may contain viruses. Company does not accept responsibility for any loss or damage arising from the use of this email or attachments.";

		$this->test_email($email,$subject,$message);
	}

    public function test_email($email,$subject,$message)
	{
		$email = $this->phpmailer_lib->load();
		
		$addreplyto 		= "application@drdistributor.com";
		$addreplyto_name 	= "Vipul DRD";
		$server_email 		= "application@drdistributor.com";
		//$server_email 	= "send@drdindia.com";
		$server_email_name 	= "DRD Corporate Report";
		$email1 			= "kapildrd@gmail.com";
		
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