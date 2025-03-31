<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_master_delivery extends CI_Controller {
	var $Page_title = "Manage Delivery";
	var $Page_name  = "manage_master_delivery";
	var $Page_view  = "manage_master_delivery";
	var $Page_menu  = "manage_master_delivery";
	var $page_controllers = "manage_master_delivery";
	var $Page_tbl   = "drd_master_tbl_delivery";
	public function __construct()
    {
        parent::__construct();
		$this->load->model('Query_Model');
    }
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
		
		$db_master = $this->load->database('db_master', TRUE);
		
		$mydate = date("Y-m-d");
		if(isset($_GET["mydate"])){
			$mydate = $_GET["mydate"];
		}
		$data["mydate"] = $mydate;
		
		$query = $db_master->query("select * from $tbl where vdt='$mydate'");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}
	public function edit($id)
	{
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


		$status = $new_password = "";
		extract($_POST);
		if (isset($Submit)) {
			$message_db = "";
			$time = time();
			$date = date("Y-m-d", $time);
			
			$where = array('code' => $id);

			if (!empty($_FILES["image"]["name"])) {
				$img = "image";
				$url_path = "./uploads/$page_controllers/photo/";

				$query = $this->db->query("select * from $tbl where id='$id'");
				$row11 = $query->row();
				$filename = $url_path . $row11->$img;
				unlink($filename);
				$name1 = "photo";

				$imagename = $_FILES["image"]['name'];
				$uploadedfile = $_FILES["image"]['tmp_name'];
				$image = "";

				$valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "PNG", "JPG", "JPEG", "GIF", "BMP");
				$ext = strtolower($this->Scheme_Model->getExtension($imagename));
				if (in_array($ext, $valid_formats)) {
					//$ext = "jpeg";
					$actual_image_name = $name1 . "_" . time() . "." . $ext;
					$widthArray = array(300);
					foreach ($widthArray as $newwidth) {
						$image = $this->Scheme_Model->compressImage($ext, $uploadedfile, $upload_path, $actual_image_name, $newwidth);
						$image = $newwidth . "_" . $image;
					}
				}
			} else {
				$image = $old_image;
			}

			//$exp_date = date("Y-m-d", strtotime($exp_date));
			$result = "";
			$dt = array(
				'status' => $status,
			);
			if ($new_password != "") {
				$password = $new_password;
				$password = md5($password);

				$dt = array(
					'status' => $status,
					'password' => $password,
				);
			}

			$result = $this->Scheme_Model->edit_fun("tbl_master_other", $dt, $where);
			if ($result) {
				$message_db = "$change_text - Edit Successfully.";
				$message = "Edit Successfully.";
				$this->session->set_flashdata("message_type", "success");
			} else {
				$message_db = "$change_text - Not Add.";
				$message = "Not Add.";
				$this->session->set_flashdata("message_type", "error");
			}
			if ($message_db != "") {
				$message = $Page_title . " - " . $message;
				$message_db = $Page_title . " - " . $message_db;
				$this->session->set_flashdata("message_footer", "yes");
				$this->session->set_flashdata("full_message", $message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if ($result) {
					redirect(current_url());
					//redirect(base_url()."admin/$page_controllers/view");
				}
			}
		}

		$query = $this->db->query("select tbl_master.id,tbl_master.code,tbl_master.altercode,tbl_master.name,tbl_master.email,tbl_master.mobile,tbl_master.status,tbl_master_other.exp_date,tbl_master_other.status as status1 from tbl_master left join tbl_master_other on tbl_master.code=tbl_master_other.code where tbl_master.code='$id' order by tbl_master.id desc");
  		$data["result"] = $query->result();		
		
		$row = $this->db->query("select id from tbl_master_other where code=$id")->row();
  		if(empty($row->id)){
			$dt = array(
			'code'=>$id,
			'status'=>0,
			'exp_date'=>0,
			'updated_at'=>0,
			'password_change'=>0,
			'password'=>'',
			'latitude'=>'',
			'longitude'=>'',
			'date'=>'',
			'time'=>'',
			'datetime'=>'',
			'firebase_token'=>'',
			'image'=>'',
			);
			$this->Scheme_Model->insert_fun("tbl_master_other",$dt);
		}

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/edit",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
	

	public function send_email_for_password_create($code,$password)
	{
		$q = $this->db->query("select code,altercode,email,mobile,name from tbl_master where code='$code' ")->row();
		if($q->code!="")
		{
			$name		= $q->name;
			$email_id 	= $q->email;
			$altercode 	= $q->altercode;
			$number 	= $q->mobile;
			if($q->mobile!="")
			{
				/*$msg = "Hello $q->name Your New Login Details is $q->altercode Password is $randompassword";
				$q->mobile = "9530005050";
				//$q->mobile = "7303229909";
				$this->auth_model->send_sms_fun($q->mobile,$msg);*/
			}
			else
			{
				$err = "$name this user can not have any mobile number";
				$this->Email_Model->tbl_whatsapp_email_fail($number,$err,$altercode);
			}
			if($q->email!="")
			{
				$this->Email_Model->send_email_for_password_create($name,$email_id,$altercode,$password);
			}
			else
			{
				$err = "$name this user can not have any email address";
				$this->Email_Model->tbl_whatsapp_email_fail($email_id,$err,$altercode);
			}			
		}
	}

	public function password_create1() {
		$id = $_POST["id"];
		$password = strtolower($_POST["password"]);	

		$row = $this->db->query("select tbl_master.code from tbl_master,tbl_master_other where tbl_master.code=tbl_master_other.code and tbl_master.id='$id' order by tbl_master.id desc")->row();
		$code = $row->code;
		$this->send_email_for_password_create($code,$password);
		$password = md5($password);
		$this->db->query("update tbl_master_other set password='$password' where code='$code'");
		echo "ok";
	}

	public function password_create2() {
		$id = $_POST["id"];
		$password = strtolower($this->randomPassword());	

		$row = $this->db->query("select tbl_master.code from tbl_master,tbl_master_other where tbl_master.code=tbl_master_other.code and tbl_master.id='$id' order by tbl_master.id desc")->row();
		$code = $row->code;
		$this->send_email_for_password_create($code,$password);
		$password = md5($password);
		$this->db->query("update tbl_master_other set password='$password' where code='$code'");
		echo "ok";
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
}