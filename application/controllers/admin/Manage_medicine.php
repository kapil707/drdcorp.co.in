<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_medicine extends CI_Controller {

	var $Page_title = "Manage Medicine";
	var $Page_name  = "manage_medicine";
	var $Page_view  = "manage_medicine";
	var $Page_menu  = "manage_medicine";
	var $page_controllers = "manage_medicine";
	var $Page_tbl   = "tbl_medicine";
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
	}
	public function view()
	{
		error_reporting(0);
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
		
		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/resize/";
		$upload_path 		= "./uploads/$page_controllers/photo/main/";
		$upload_resize 		= "./uploads/$page_controllers/photo/resize/";

		$result = $this->db->query("select * from $tbl order by id desc")->result();
		$data["result"] = $result;
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	public function find_medicine()
	{	
		$i  = 0;
		$jsonArray = array();
		if(!empty($_REQUEST)){
			
			$medicine_name = $this->input->post('medicine_name');

			$result =  $this->db->query ("select i_code,item_name from tbl_medicine where item_name Like '$medicine_name%' or item_name Like '%$medicine_name' or item_name='$medicine_name' limit 50")->result();
			foreach($result as $row){

				$sr_no = $i++;
				$item_code = $row->i_code;
				$item_name = $row->item_name;	

				$dt = array(
					'sr_no' => $sr_no,
					'item_code' => $item_code,
					'item_name'=>$item_name,
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

	public function find_medicine_company()
	{	
		$i  = 0;
		$jsonArray = array();
		if(!empty($_REQUEST)){
			
			$medicine_company_name = $this->input->post('medicine_company_name');

			$result =  $this->db->query ("select DISTINCT compcode,company_full_name from tbl_medicine where company_full_name Like '$medicine_company_name%' or company_full_name Like '%$medicine_company_name' or company_full_name='$medicine_company_name' or
			company_name Like '$medicine_company_name%' or company_name Like '%$medicine_company_name' or company_name='$medicine_company_name' limit 50")->result();
			foreach($result as $row){

				$sr_no = $i++;
				$item_code = $row->compcode;
				$item_name = $row->company_full_name;	

				$dt = array(
					'sr_no' => $sr_no,
					'item_code' => $item_code,
					'item_name'=>$item_name,
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

	public function find_medicine_company_division()
	{	
		$i  = 0;
		$jsonArray = array();
		if(!empty($_REQUEST)){
			
			$find_medicine_company_id = $this->input->post('find_medicine_company_id');

			$result =  $this->db->query ("select DISTINCT division from tbl_medicine where compcode='$find_medicine_company_id' order by division asc")->result();
			foreach($result as $row){

				$sr_no = $i++;
				$item_name = $row->division;	

				$dt = array(
					'sr_no' => $sr_no,
					'item_name'=>$item_name,
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
	
	public function view_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;
		$Page_tbl = $this->Page_tbl;

		$result = $this->db->query("SELECT * from $Page_tbl");
		$result = $result->result();
		foreach($result as $row) {

			$sr_no = $i++;
			$id = $row->id;

			$item_code = $row->i_code;
			$url = "https://www.drdistributor.com/md/$item_code";
			$item_name = "<a href='".$url."' target='_blank'>$row->item_name</a>";
			
			$i_code 		= $row->i_code;
			$item_code		= $row->item_code;
			$compney_code	= $row->compcode;
			$company_name 	= $row->company_name;
			$discount 		= $row->discount;
			$gst 			= $row->gstper;
			$mrp 			= $row->mrp;
			$ptr 			= $row->sale_rate;
			$costrate 		= round($row->costrate);
			
			//$datetime = date("d-M-y @ H:i:s", $row->time);

			$dt = array(
				'sr_no' => $sr_no,
				'id' => $id,				
				'i_code' => $i_code,
				'item_code' => $item_code,
				'item_name' => $item_name,
				'compney_code' => $compney_code,
				'company_name' => $company_name,
				'discount' => $discount,
				'gst' => $gst,
				'mrp' => $mrp,
				'ptr' => $ptr,
				'costrate' => $costrate,
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
		
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}