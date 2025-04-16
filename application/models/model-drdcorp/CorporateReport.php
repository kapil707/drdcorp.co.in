<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CorporateReport extends CI_Model
{
	public $myemail;
	public function __construct(){
		parent::__construct();

		$this->load->database(); 
		//$this->myemail = $this->phpmailer_lib->load();
	}

	function CheckRecoreds($tbl,$where)
	{
		if($where!="")
		{
			$this->db->where($where);
		}
		return $this->db->get($tbl)->row();	
	}

	function InsertRecoreds($tbl,$dt)
	{
		if($this->db->insert($tbl,$dt))
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function UpdateRecoreds($tbl,$dt,$where)
	{
		if($this->db->update($tbl,$dt,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function FolderCreate()
	{
		$vdt   = date("Y-m-d");

		if (!file_exists('corporate_report/'.$vdt)) {
			mkdir('corporate_report/'.$vdt, 0777, true);
		}
	}

	function sendReport() {

		$this->db->select('*');
        $this->db->from('tbl_corporate_report');
        $this->db->where('email_status', 0);
        $this->db->limit(5);

        $query = $this->db->get();
        $result = $query->result();
		foreach($result as $row)
		{
			/************************************************/
			$report_type 	= $row->report_type;
			$email     		= $row->email;
			if(empty($email)){
				$email = "kapildrd@gmail.com";
			}
            $code       	= $row->code;
            $compcode   	= $row->compcode;
            $division   	= $row->division;
            $company_name 	= $row->company_name;
			$name 			= $row->name;
			$file1 			= $row->file1;
			$file2 			= $row->file2;
			$file3 			= $row->file3;
			$from_date 		= $row->from_date;
			$to_date 		= $row->to_date;
			$folder_date 	= $row->folder_date;
			$file_name 		= $row->file_name;
            
            $this->get_body($report_type,$email,$code,$compcode,$division,$name,$company_name,$file1,$file2,$file3,$from_date,$to_date,$folder_date,$file_name);
		}
	}

    public function get_body($report_type,$email,$code,$compcode,$division,$name,$company_name,$file1,$file2,$file3,$from_date,$to_date,$folder_date,$file_name) {

		$from_date = date("d-M-y", strtotime($from_date));
		$to_date = date("d-M-y", strtotime($to_date));

		$file_name1 = "SaleAndStockAnalysis.xlsx";
		$file_name_path1 = "SaleAndStockAnalysis-$file_name.xlsx";
		$file_name2 = "ItemWiseReport.xlsx";
		$file_name_path2 = "ItemWiseReport-$file_name.xlsx";
		$file_name3 = "ChemistWiseReport.xlsx";
		$file_name_path3 = "ChemistWiseReport-$file_name.xlsx";
		$url1 = $url2 = $url3 = "";
		if($file1==1){
			$url = "https://www.drdcorp.co.in/corporate_report/".$folder_date."/".$file_name_path1;
			$url1 = "<a href='".$url."'>".$file_name1."</a><br><br>";
		}
		if($file2==1){
			$url = "https://www.drdcorp.co.in/corporate_report/".$folder_date."/".$file_name_path2;
			$url2 = "<a href='".$url."'>".$file_name2."</a><br><br>";
		}
		if($file3==1){
			$url = "https://www.drdcorp.co.in/corporate_report/".$folder_date."/".$file_name_path3;
			$url3 = "<a href='".$url."'>".$file_name3."</a><br><br>";
		}

		$subject = "Monthly Report (".$from_date." to ".$to_date.") ".ucwords(strtolower($company_name))." (".$division.")";
		if($from_date==$to_date){
			$subject = "Daily Report (".$from_date.") ".ucwords(strtolower($company_name))." (".$division.")";
		}
		$message = "Hello Sir/Madam ".ucwords(strtolower($name)).",<br><br>It is the sales data as you have sought<br>";
		$message.= $url1;
		$message.= $url2;
		$message.= $url3;
		$message.= "Please download the file as in the link above for your reference.<br>Thanks and regards<br><br>D.R. Distributors Pvt Ltd<br>Notice & Disclaimer - This email and any files transmitted with it contain Proprietary, privileged and confidential information and/or information protected by intellectual property rights and is only for the use of the intended recipient of this message. If you are not the intended recipient, please delete or destroy this and all copies of this message along with the attachments immediately. You are hereby notified and directed that (1) if you are not the named and intended addressee you shall not disseminate, distribute or copy this e-mail, and (2) any offer for product/service shall be subject to a final evaluation of relevant patent status. Company cannot guarantee that e-mail communications are secure or error-free, as information could be intercepted, corrupted, amended, lost, destroyed, arrive late or incomplete, or may contain viruses. Company does not accept responsibility for any loss or damage arising from the use of this email or attachments.";
		
		$this->SendEmail($email,$subject,$message,$code);
	}

	public function SendEmail($user_email,$subject,$message,$code)
	{
		$email = $this->phpmailer_lib->load();
		
		$addreplyto 		= "vipul@drdindia.com";
		$addreplyto_name 	= "Vipul Gupta";
		$server_email 		= "report@drdcorp.co.in";
		$server_email_name 	= "DRD Corporate Report";
		//$user_email 		= "kapil707sharma@gmail.com";
		$email_bcc 			= "application@drdindia.com";
		
		$email->AddReplyTo($addreplyto,$addreplyto_name);
		$email->SetFrom($server_email,$server_email_name);
		$email->AddAddress($user_email);
		$email->addBcc($email_bcc);
		
		$email->Subject   	= $subject;
		$email->Body 		= $message;

		$email->IsHTML(true);

		// $email->isSMTP();
		// $email->Host       = 'mail.drdcorp.co.in'; // SMTP Server
		// $email->SMTPAuth   = true; // Enable SMTP Authentication
		// $email->Username   = 'report@drdcorp.co.in'; // SMTP Username
		// $email->Password   = 'Kapil1234!@#$'; // SMTP Password
		// $email->SMTPSecure = 'ssl'; // Use SSL (as your SMTP port is 465)
		// $email->Port       = 465; // SMTP Port

		if($email->send()){
			$message_status = 'Message has been sent';
			echo "<br>";
		}else{
			$message_status = 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		}
		echo $message_status;

		$dt = array('email_status'=>1,'message_status'=>$message_status,'subject'=>$subject,'message'=>$message);
		$where = array('code'=>$code);
		$this->UpdateRecoreds("tbl_corporate_report", $dt, $where);
		//echo "<pre>";
		//print_r($email);
	}

    public function test_email($email,$subject,$message,$code)
	{
		$email = $this->myemail;
		
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
		$email->Body 		= $message;

		$email->IsHTML(true);

		$email->isSMTP();
		$email->Host       = 'mail.drdcorp.co.in'; // SMTP Server
		$email->SMTPAuth   = true; // Enable SMTP Authentication
		$email->Username   = 'report@drdcorp.co.in'; // SMTP Username
		$email->Password   = 'Kapil1234!@#$'; // SMTP Password
		$email->SMTPSecure = 'ssl'; // Use SSL (as your SMTP port is 465)
		$email->Port       = 465; // SMTP Port

		if($email->send()){
			$message_status = 'Message has been sent';
			echo "<br>";
		}else{
			$message_status = 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		}
		echo $message_status;


		$dt = array('email_status'=>1,'message_status'=>$message_status,'subject'=>$subject,'message'=>$message);
		$where = array('code'=>$code);
		$this->UpdateRecoreds("tbl_corporate_report", $dt, $where);
		//echo "<pre>";
		//print_r($email);
	}
}