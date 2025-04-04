<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_user_chemist extends CI_Controller {
	var $Page_title = "Manage chemist";
	var $Page_name  = "manage_user_chemist";
	var $Page_view  = "manage_user_chemist";
	var $Page_menu  = "manage_user_chemist";
	var $page_controllers = "manage_user_chemist";
	var $Page_tbl   = "tbl_chemist";
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->model("model-bank/BankModel");
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
		
		$result = $this->BankModel->select_query("SELECT tbl_chemist.code,tbl_chemist.altercode,tbl_chemist.name,tbl_chemist.mobile,tbl_chemist.email,tbl_chemist.address,tbl_chemist.address1,tbl_chemist.address2,tbl_chemist.address3,tbl_chemist_other.website_limit,tbl_chemist_other.android_limit,tbl_chemist_other.status,tbl_chemist.id as id,tbl_chemist_other.id as id2 from tbl_chemist left join tbl_chemist_other on tbl_chemist.code = tbl_chemist_other.code where tbl_chemist.slcd='CL' order by tbl_chemist.id desc");
		$result = $result->result();
		
  		$data["result"] = $result;
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
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