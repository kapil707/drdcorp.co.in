<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CorporateReport extends CI_Model
{
	public function __construct(){
		parent::__construct();
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
        $this->db->where('tbl_corporate_other.report_status', 0);
        $this->db->limit(10);

        $query = $this->db->get();
        $result = $query->result();
		foreach($result as $row)
		{
			/************************************************/
			$email     		= $row->email;
            $code       	= $row->code;
            $compcode   	= $row->compcode;
            $division   	= $row->division;
            $company_name 	= $row->company_name;
			$name 			= $row->name;

			$file1 = $file2 = $file3 = "1";
			$date = date('Y-m-d');
            
            $this->get_body($date,$email,$code,$compcode,$division,$name,$company_name,$file1,$file2,$file3);

			$dt = array('report_status'=>1);
			$where = array('code'=>$code);
			$this->UpdateRecoreds("tbl_corporate_report", $dt, $where);
		}
	}

    public function get_body($date,$email,$code,$compcode,$division,$name,$company_name,$file1,$file2,$file3) {

		$today_date = date("d-M-y");		
		$folder_dt = date('Y-m-d');

		$file_name1 = "ChemistWiseReport.xlsx";
		$file_name_path1 = "ChemistWiseReport-$code-$compcode-$division.xlsx";
		$file_name2 = "ItemWiseReport.xlsx";
		$file_name_path2 = "ItemWiseReport-$code-$compcode-$division.xlsx";
		$file_name3 = "SaleAndStockAnalysis.xlsx";
		$file_name_path3 = "SaleAndStockAnalysis-$code-$compcode-$division.xlsx";
		if($file1==1){
			$url = "https://www.drdcorp.co.in/corporate_report/".$folder_dt."/".$file_name_path1;
			$url1 = "<a href='".$url."'>".$file_name1."</a><br><br>";
		}
		if($file2==1){
			$url = "https://www.drdcorp.co.in/corporate_report/".$folder_dt."/".$file_name_path2;
			$url2 = "<a href='".$url."'>".$file_name2."</a><br><br>";
		}
		if($file3==1){
			$url = "https://www.drdcorp.co.in/corporate_report/".$folder_dt."/".$file_name_path13;
			$url3 = "<a href='".$url."'>".$file_name3."</a><br><br>";
		}

		$subject = "Daily Report (".$today_date.") ".ucwords(strtolower($company_name))." (".$division.")";
		$message = "Sir $name,<br>It is the sales data as you have sought<br>";
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
			echo "<br>";
		}else{
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		}
		//echo "<pre>";
		//print_r($email);
	}
}