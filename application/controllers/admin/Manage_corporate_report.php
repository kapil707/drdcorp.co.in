<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_corporate_report extends CI_Controller {
	public $Page_title = "Manage Corporate Report";
	public $Page_name  = "manage_corporate_report";
	public $Page_view  = "manage_corporate_report";
	public $Page_menu  = "manage_corporate_report";
	public $page_controllers = "manage_corporate_report";
	public $Page_tbl   = "tbl_corporate_report";
	public function __construct()
    {
        parent::__construct();
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
		
		//$result = $this->BankModel->select_query("select * from $tbl");
		//$data["result"] = $result->result();

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

		// $data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		// $upload_path = "./uploads/$page_controllers/photo/";
		// $upload_thumbs_path = "./uploads/$page_controllers/photo/thumbs/";
		// $system_ip = $this->input->ip_address();

		extract($_POST);
		if(isset($Submit))
		{
			$message_db = "";
			$result = "";
			$dt = array(
				'string_value'=>$string_value,
				'chemist_id'=>$chemist_id,
				'date' => date('Y-m-d'),
				'time' => date('H:i:s'),
				'timestamp' => time(),
				'user_id'=>$user_id
			);
			$where = array('id'=>$id);
			$result = $this->BankModel->edit_fun($tbl,$dt,$where);		
			if($result)
			{
				$message = "Edit Successfully.";
				$this->session->set_flashdata("message_type","success");
				redirect(current_url());
			}
			else
			{
				$message = "Not Add.";
				$this->session->set_flashdata("message_type","error");
			}
		}

		$query = $this->BankModel->select_query("select * from $tbl where id='$id'");
  		$data["result"] = $query->result();
		$data["id"] = $id;

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/edit",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
	public function delete_rec()
	{
		$id = $_POST["id"];
		$Page_title = $this->Page_title;
		$Page_tbl = $this->Page_tbl;
		
		$where = array('id'=>$id);
		$result = $this->BankModel->delete_fun("$Page_tbl",$where);
		if($result)
		{
			$message = "Delete Successfully.";
		}
		else
		{
			$message = "Not Delete.";
		}
		// $message = $Page_title." - ".$message;
		// $this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}
	
	public function view_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;
		$Page_tbl = $this->Page_tbl;

		if(!empty($_REQUEST)){
			$from_date 	= $_REQUEST["from_date"];
			$to_date	= $_REQUEST['to_date'];

			$query = $this->db->query("select * from `tbl_corporate_report` WHERE $Page_tbl.from_date BETWEEN '$from_date' AND '$to_date'");
			$result = $query->result();
			foreach($result as $row) {

				$sr_no = $i++;
				$id = $row->id;
				
				$name = $row->name;
				$email = $row->email;
				$from_date = $row->from_date;
				$to_date = $row->to_date;
				$message_status = $row->message_status;
				$subject = $row->subject;
				$message = $row->message;

				
				
				/*$time = $row->insert_time;
				if(empty($time)){
					$time = time();
				}
				$datetime = date("d-M-y @ H:i:s", $time);*/

				$dt = array(
					'sr_no' => $sr_no,
					'id' => $id,
					'name' => $name,
					'email' => $email,
					'from_date'=>$from_date,
					'to_date'=>$to_date,
					'message_status'=>$message_status,
					'subject'=>$subject,
					'message'=>$message,
				);
				$jsonArray[] = $dt;
			}
			if(!empty($jsonArray)){
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
		}
		
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}