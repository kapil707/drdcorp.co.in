<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_user_chemist extends CI_Controller {
	var $Page_title = "Manage chemist";
	var $Page_name  = "manage_user_chemist";
	var $Page_view  = "manage_user_chemist";
	var $Page_menu  = "manage_user_chemist";
	var $page_controllers = "manage_user_chemist";
	var $Page_tbl   = "tbl_chemist";
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
	}
	public function view()
	{
		/******************session***********************/
		$user_id = $this->session->userdata("user_id");
		$user_type = $this->session->userdata("user_type");
		/******************session***********************/		
		$_SESSION["latitude"] = 
		$_SESSION["longitude"] = "";
		$Page_title = $this->Page_title;
		$Page_name 	= $this->Page_name;
		$Page_view 	= $this->Page_view;
		$Page_menu 	= $this->Page_menu;
		$Page_tbl 	= $this->Page_tbl;
		$page_controllers 	= $this->page_controllers;	
		$this->Admin_Model->permissions_check_or_set($Page_title,$Page_name,$user_type);	
		$data['title1'] = $Page_title." || View";
		$data['title2'] = "View";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;
		$this->breadcrumbs->push("Admin","admin/");	
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("View","admin/$page_controllers/view");
		
		$tbl = $Page_tbl;	
		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";	
		
		$this->load->library('pagination');
		$result = $this->db->query("SELECT tbl_chemist.code,tbl_chemist.altercode,tbl_chemist.name,tbl_chemist.mobile,tbl_chemist.email,tbl_chemist.address,tbl_chemist.address1,tbl_chemist.address2,tbl_chemist.address3,tbl_chemist_other.website_limit,tbl_chemist_other.android_limit,tbl_chemist_other.status,tbl_chemist.id as id,tbl_chemist_other.id as id2 from tbl_chemist left join tbl_chemist_other on tbl_chemist.code = tbl_chemist_other.code where tbl_chemist.slcd='CL' order by tbl_chemist.id desc")->result();
		
  		$data["result"] = $result;
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}
	
	public function edit($id)
	{
		//error_reporting(0);
		/******************session***********************/
		$user_id = $this->session->userdata("user_id");
		$user_type = $this->session->userdata("user_type");
		/******************session***********************/	
		$Page_title = $this->Page_title;
		$Page_name 	= $this->Page_name;
		$Page_view 	= $this->Page_view;
		$Page_menu 	= $this->Page_menu;
		$Page_tbl 	= $this->Page_tbl;
		$page_controllers 	= $this->page_controllers;	
		$this->Admin_Model->permissions_check_or_set($Page_title,$Page_name,$user_type);	
		$data['title1'] = $Page_title." || Edit";
		$data['title2'] = "Edit";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;
		$this->breadcrumbs->push("Edit","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Edit","admin/$page_controllers/edit");
		$tbl = $Page_tbl;	
		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";
		$upload_thumbs_path = "./uploads/$page_controllers/photo/thumbs/";
		$system_ip = $this->input->ip_address();
		$status = "";
		extract($_POST);
		if(isset($Submit))
		{
			$message_db = "";
			$time = time();
			$date = date("Y-m-d",$time);				
			$query = $this->db->query("select * from tbl_chemist where id='$id'")->row();
			$code = $query->code;
			$altercode = $query->altercode;
			if($block=="1" || $status==0)
			{
				$this->user_logout_new($altercode);
			}
			if (!empty($_FILES["image"]["name"]))
			{
				$user_code 		= $code;
				$user_type 		= "chemist";
				$image_path   	= $_FILES['image']['tmp_name'];
				$img_name     	= time()."_".$user_code."_".$user_type.".png";
				$user_profile 	= "user_profile/$img_name";			
				move_uploaded_file($image_path,$user_profile);
				$image 			= $img_name;
			}
			else
			{
				$image 			= $old_image;
			}
			$result = "";
			$dt = array(
				'new_request'=>"0",
				'status'=>$status,
				'block'=>$block,
				'website_limit'=>$website_limit,
				'android_limit'=>$android_limit,
				'image'=>$image,
				'download_status'=>0,
				'delete_request'=>0,
				'delete_request_date'=>'',
			);
			if($new_password!="")
			{
				$password =	$new_password;
				$this->send_email_for_password_create($code,$password);
				$password = md5($password);
				$dt = array(
					'new_request'=>"0",
					'status'=>$status,
					'block'=>$block,
					'website_limit'=>$website_limit,
					'android_limit'=>$android_limit,
					'password'=>$password,
					'download_status'=>0,
					'delete_request'=>0,
					'delete_request_date'=>'',
				);
			}
			$where = array('code'=>$code);
			$result = $this->Scheme_Model->edit_fun("tbl_chemist_other",$dt,$where);
			if($result)
			{
				$message_db = "Edit Successfully.";
				$message = "Edit Successfully.";
				$this->session->set_flashdata("message_type","success");
			}
			else
			{
				$message_db = "Not Add.";
				$message = "Not Add.";
				$this->session->set_flashdata("message_type","error");
			}
			if($message_db!="")
			{
				$message = $Page_title." - ".$message;
				$message_db = $Page_title." - ".$message_db;
				$this->session->set_flashdata("message_footer","yes");
				$this->session->set_flashdata("full_message",$message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if($result)
				{
					redirect(current_url());
					//redirect(base_url()."admin/$page_controllers/view");
				}
			}
		}	
		$query = $this->db->query("select tbl_chemist.altercode,tbl_chemist.code,tbl_chemist.name,tbl_chemist_other.status,tbl_chemist_other.block,tbl_chemist_other.order_limit,tbl_chemist_other.website_limit,tbl_chemist_other.android_limit,tbl_chemist_other.image from tbl_chemist,tbl_chemist_other where tbl_chemist.code=tbl_chemist_other.code and tbl_chemist.id='$id' order by tbl_chemist.id desc");
  		$data["result"] = $query->result();	
		$x = $query->result();	
		if(empty($x))
		{
			$query = $this->db->query("select * from tbl_chemist where id='$id'")->row();
			$code = $query->code;
			if($code!="")
			{
				$this->db->query("insert into tbl_chemist_other set code='$code'");
				redirect(current_url());
			}
		}
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/edit",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
	
	public function send_email_for_password_create($code,$password)
	{
		$this->load->model("model-drdweb/WhatsAppModel");
		$this->load->model("model-drdweb/EmailModel");
		
		$q = $this->db->query("select * from tbl_chemist where code='$code' ")->row();
		if(!empty($q->altercode))
		{
			$chemist_name	= $q->name;
			$chemist_email 	= $q->email;
			$altercode 		= $q->altercode;
			$chemist_mobile = $q->mobile;
			if(!empty($chemist_mobile)){
				
				$whatsapp_footer_text = $this->Scheme_Model->get_website_data("whatsapp_footer_text");
				
				$login_details_text   = $this->Scheme_Model->get_website_data("login_details_text");
				
				$whatsapp_message = "Hello $chemist_name ($altercode) \\n\\n".$login_details_text."\\n\\nUsername : $altercode \\nPassword : $password ".$whatsapp_footer_text;
				
				
				$chemist_mobile = "+91".$chemist_mobile;				
				$this->WhatsAppModel->insert_whatsapp($chemist_mobile,$whatsapp_message,$altercode);
			}
			
			if(!empty($chemist_email)) {

				$email_footer_text  = $this->Scheme_Model->get_website_data("email_footer_text");

				$login_details_text = $this->Scheme_Model->get_website_data("login_details_text");

				$email_message = "Hello $chemist_name ($altercode),<br><br>".$login_details_text;
				$email_message .="<br><br>Username : $altercode <br>Password : $password";
				$email_message .=$email_footer_text;
				
				$subject = "Login Detail From D.R. Distributors Pvt. Ltd.";
				$message = $email_message;
				$user_email_id = $chemist_email;
				$email_function= "password";
				$email_other_bcc="kapildrd@gmail.com";
				$mail_server = "";
				$file_name1 = $file_name2 = $file_name3 = "";
				$file_name_1 = $file_name_2 = $file_name_3 = "";
				$this->EmailModel->insert_email($user_email_id,$subject,$message,$file_name1,$file_name2,$file_name3,$file_name_1,$file_name_2,$file_name_3,$mail_server,$email_function,$email_other_bcc);
			}
		}
	}
	public function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	public function user_logout_new($altercode)
	{
		$this->db->query("update tbl_android_device_id set logout='1' where chemist_id='$altercode'");
		//$this->db->query("delete from drd_login_time where user_altercode='$altercode'");
	}
	public function user_logout()
	{
		//error_reporting(0);
		header('Content-Type: application/json');
		$altercode = $_POST["altercode"];
		$items = "";
		$response = "";
		if($altercode!="")
		{
			$this->db->query("update tbl_android_device_id set logout='1' where chemist_id='$altercode'");
			//$this->db->query("delete from drd_login_time where user_altercode='$altercode'");
			$response = 1;
		}
$items.= <<<EOD
{"response":"{$response}"},
EOD;
if ($items != '') {
$items = substr($items, 0, -1);
}
?>
{"items":[<?= $items;?>]}
<?php
	}

	public function find_chemist()
	{	
		$i  = 0;
		$jsonArray = array();
		if(!empty($_REQUEST)){
			
			$chemist_name = $this->input->post('chemist_name');
			if(strtolower($chemist_name)=="all"){
				$sr_no = $i++;
				$id = "0";
				$chemist_id = "all";
				$chemist_name = "All Users";	

				$dt = array(
					'sr_no' => $sr_no,
					'id' => $id,
					'chemist_id' => $chemist_id,
					'chemist_name'=>$chemist_name,
				);
				$jsonArray[] = $dt;
				
				$sr_no = $i++;
				$id = "1";
				$chemist_id = "all login";
				$chemist_name = "All Login Users";	

				$dt = array(
					'sr_no' => $sr_no,
					'id' => $id,
					'chemist_id' => $chemist_id,
					'chemist_name'=>$chemist_name,
				);
				$jsonArray[] = $dt;
			}
			$result =  $this->db->query ("select * from tbl_chemist where name Like '$chemist_name%' or name Like '%$chemist_name' or altercode='$chemist_name' and slcd='CL' limit 50")->result();
			foreach($result as $row){

				$sr_no = $i++;
				$id = $row->id;
				$chemist_id = $row->altercode;
				$chemist_name = $row->name;	

				$dt = array(
					'sr_no' => $sr_no,
					'id' => $id,
					'chemist_id' => $chemist_id,
					'chemist_name'=>$chemist_name,
				);
				$jsonArray[] = $dt;
			}
			
			$items = $jsonArray;
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
			);
		}else{
			$response = array(
				'success' => "0",
				'message' => '502 error',
			);
		}

		// Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}