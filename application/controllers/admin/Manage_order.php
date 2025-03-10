<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_order extends CI_Controller {
	var $Page_title = "Manage Order";
	var $Page_name  = "manage_order";
	var $Page_view  = "manage_order";
	var $Page_menu  = "manage_order";
	var $page_controllers = "manage_order";
	var $Page_tbl   = "tbl_cart_order";
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
					$chemist_id = $row->chemist_id;
					$salesman_id = $row->salesman_id;
					$user_type = $row->user_type;
					$order_type = $row->order_type;
					$remarks = $row->remarks;
					$total = $row->total;
					$gstvno = $row->gstvno;
					$items_total = $row->items_total;
					$download_status = $row->download_status;
					$download_line = $row->download_line;
					$datetime = date("d-M-y",strtotime($row->date)) . " @ " .$row->time;

					$download_status1 = "No";
					if($download_status==1){
						$download_status1 = "Yes";
					}

					$download_error = "0";
					if($items_total!=$download_line){
						$download_error = "1";
					}

					$dt = array(
						'sr_no' => $sr_no,
						'id' => $id,
						'chemist_id' => $chemist_id,
						'salesman_id'=>$salesman_id,
						'user_type'=>$user_type,
						'order_type'=>$order_type,
						'remarks'=>$remarks,
						'total'=>$total,
						'gstvno'=>$gstvno,
						'items_total'=>$items_total,
						'download_status'=>$download_status1,
						'download_line'=>$download_line,
						'download_error'=>$download_error,
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
}