<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_order_max extends CI_Controller {
	var $Page_title = "Manage Order Max";
	var $Page_name  = "manage_order_max";
	var $Page_view  = "manage_order_max";
	var $Page_menu  = "manage_order_max";
	var $page_controllers = "manage_order_max";
	var $Page_tbl   = "";
	public function __construct()
    {
        parent::__construct();
		//$this->load->model("model-drdweb/BankModel");
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

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	public function view_api() {
		
		if(!empty($_REQUEST)){
			$from_date 	= $_REQUEST["from_date"];
			$to_date	= $_REQUEST['to_date'];

			$jsonArray = array();

			$items = "";
			if(!empty($from_date) && !empty($to_date)){

				$result = $this->db->query("SELECT DISTINCT(`chemist_id`),SUM(total) as total FROM `tbl_cart_order` WHERE `date` BETWEEN '$from_date' and '$to_date' GROUP BY `chemist_id` order by total desc");
				$result = $result->result();

				foreach($result as $row){

					$chemist_id = $row->chemist_id;
					$total = $row->total;

					$dt = array(
						'chemist_id' => $chemist_id,
						'total'=>$total,
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
}