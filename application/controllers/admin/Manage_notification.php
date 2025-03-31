<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_notification extends CI_Controller {
	var $Page_title = "Manage Notification";	
	var $Page_name  = "manage_notification";
	var $Page_view  = "manage_notification";
	var $Page_menu  = "manage_notification";
	var $page_controllers = "manage_notification";
	var $Page_tbl   = "tbl_notification";
	public function __construct(){
		parent::__construct();
		$this->load->model("model-drdweb/NotificationModel");
	}
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
	}
	public function add()
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

		$data['title1'] = $Page_title." || Add";
		$data['title2'] = "Add";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;	

		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Add","admin/$page_controllers/add");		

		$tbl = $Page_tbl;
		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/resize/";
		$upload_path 		= "./uploads/$page_controllers/photo/main/";
		$upload_resize 		= "./uploads/$page_controllers/photo/resize/";

		$system_ip = $this->input->ip_address();
		$find_medicine_id = $find_medicine_company_id = $find_medicine_company_division = 0;
		extract($_POST);
		if(isset($Submit))
		{
			$message = nl2br($message);
			$message = str_replace("'","&#39;",$message);
			$message = str_replace("\r\n","<br>",$message);

			if (!empty($_FILES["image"]["name"]))
			{
				$this->Image_Model->uploadTo = $upload_path;
				$image = $this->Image_Model->upload($_FILES['image']);
				$image = str_replace($upload_path,"",$image);

				$this->Image_Model->newPath = $upload_resize;
				$this->Image_Model->newWidth = 550;
				$this->Image_Model->newHeight = 550;
				$this->Image_Model->resize();
			}		
			else
			{
				$image = "";
			}
			$message_db = "";
			$result = "";	
			if(empty($find_chemist_id))
			{ 
				$message_db = "Select Chemist";
				$message = "Select Chemist";
				$this->session->set_flashdata("message_type","error");
			} else {
				$result = "";
				if(!empty($find_chemist_id) && $find_chemist_id!="all" && $find_chemist_id!="all login")
				{
					$query1 = $this->db->query("select * from tbl_chemist where slcd='CL' and altercode='$find_chemist_id' order by name asc")->result();
				}
				if($find_chemist_id=="all")
				{
					$query1 = $this->db->query("select * from tbl_chemist where slcd='CL' order by name asc")->result();
				}
				if($find_chemist_id=="all login")
				{
					$query1 = $this->db->query("SELECT tbl_chemist.* FROM tbl_chemist_other INNER join `tbl_chemist` on tbl_chemist.code=tbl_chemist_other.code order by tbl_chemist.name asc")->result();
				}
				foreach($query1 as $row1)
				{
					$chemist_id = $row1->altercode;
					$user_type = "chemist";

					$result = $this->NotificationModel->insert_notification($funtype,$title,$message,$chemist_id,$user_type,$find_medicine_id,$find_medicine_company_id,$find_medicine_company_division,$image,'Admin');
				}
				if($result)
				{
					$message_db = "() - Add Successfully.";
					$message = "Add Successfully.";
					$this->session->set_flashdata("message_type","success");
				}
				else
				{
					$message_db = "() - Not Add.";
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
						redirect(base_url()."admin/$page_controllers/view");
					}
				}
			}
		}

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/manage_user_chemist/find_chemist",$data);
		$this->load->view("admin/manage_medicine/find_medicine",$data);
		$this->load->view("admin/manage_medicine/find_medicine_company",$data);
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

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	public function view_api() {
		
		$i = 1;
		$Page_tbl = $this->Page_tbl;
		if(!empty($_REQUEST)){
			$from_date 	= $_REQUEST["from_date"];
			$to_date	= $_REQUEST['to_date'];

			$jsonArray = array();

			$items = "";
			if(!empty($from_date) && !empty($to_date)){

				$result = $this->db->query("SELECT * FROM $Page_tbl WHERE `date` BETWEEN '$from_date' and '$to_date' order by id desc");
				$result = $result->result();

				foreach($result as $row){

					$sr_no = $i++;
					$id = $row->id;
					$myfun = "";
					if($row->funtype=="0")
					{ 
						$myfun = "Not Need"; 
					}
					if($row->funtype=="1")
					{ 
						$myfun = "Item ".$row->itemid; 
					} 
					if($row->funtype=="2")
					{ 
						$myfun = "Company ".$row->compid;
					} 
					if($row->funtype=="3")
					{ 
						$myfun = "Map Page"; 
					} 
					if($row->funtype=="4")
					{ 
						$myfun = "Orders Page"; 
					} 
					if($row->funtype=="4")
					{ 
						$myfun = "Invoice Page"; 
					}
					$user = $row->user_type."(".$row->chemist_id.")";
					$title = $row->title;
					$message = $row->message;
					$insert_type = $row->insert_type;
					$datetime = date("d-M-y @ H:i:s", $row->timestamp);

					$dt = array(
						'sr_no' => $sr_no,
						'id' => $id,
						'myfun' => $myfun,
						'user'=>$user,
						'title'=>$title,
						'message'=>$message,
						'insert_type'=>$insert_type,
						'datetime'=>$datetime,
					);
					$jsonArray[] = $dt;
				}
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

	public function delete_rec()
	{
		$id = $_POST["id"];
		$Page_title = $this->Page_title;
		$Page_tbl = $this->Page_tbl;
		$result = $this->db->query("delete from $Page_tbl where id='$id'");
		if($result)
		{
			$message = "Delete Successfully.";
		}
		else
		{
			$message = "Not Delete.";
		}
		$message = $Page_title." - ".$message;
		$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}
}