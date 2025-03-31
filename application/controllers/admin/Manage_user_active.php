<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_user_active extends CI_Controller {
	var $Page_title = "Manage Active User";
	var $Page_name  = "manage_user_active";
	var $Page_view  = "manage_user_active";
	var $Page_menu  = "manage_user_active";
	var $page_controllers = "manage_user_active";
	var $Page_tbl   = "tbl_activity_logs";
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
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	public function view_api() {
		
		$i = 1;
		$Page_tbl = $this->Page_tbl;
		$jsonArray = array();

		$items = "";

		$result = $this->db->query("SELECT chemist_id, salesman_id, date, MAX(time) AS time FROM $Page_tbl GROUP BY chemist_id, salesman_id, date ORDER BY MAX(timestamp) DESC");
		$result = $result->result();

		foreach($result as $row){

			$sr_no = $i++;
			$chemist_id = $row->chemist_id;
			$salesman_id = $row->salesman_id;
			if(empty($chemist_id)){
				$chemist_id = "Guest User";
			}
			if(empty($salesman_id)){
				$salesman_id = "N/a";
			}
			$datetime = date("d-M-y",strtotime($row->date)) . " @ " .$row->time;

			$dt = array(
				'sr_no' => $sr_no,
				'chemist_id' => $chemist_id,
				'salesman_id'=>$salesman_id,
				'datetime'=>$datetime,
			);
			$jsonArray[] = $dt;
		}

		$items = $jsonArray;
		$response = array(
			'success' => "1",
			'message' => 'Data load successfully',
			'items' => $items,
		);

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}