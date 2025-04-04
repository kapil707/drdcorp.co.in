<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_bank_invoice extends CI_Controller {
	public $Page_title = "Manage Bank Invoice";
	public $Page_name  = "manage_bank_invoice";
	public $Page_view  = "manage_bank_invoice";
	public $Page_menu  = "manage_bank_invoice";
	public $page_controllers = "manage_bank_invoice";
	public $Page_tbl   = "tbl_invoice";	
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

				$result = $this->BankModel->select_query("SELECT $Page_tbl.*, tbl_chemist.name FROM $Page_tbl LEFT JOIN tbl_chemist ON $Page_tbl.chemist_id = tbl_chemist.altercode WHERE $Page_tbl.date BETWEEN '$from_date' AND '$to_date' ORDER BY $Page_tbl.id DESC");
				$result = $result->result();

				foreach($result as $row){

					$sr_no = $i++;
					$id = $row->id;
					$dispatchtime = $row->dispatchtime;
					$vno = $row->vno;
					$tagno = $row->tagno;
					$gstvno = $row->gstvno;
					$pickedby = $row->pickedby;
					$checkedby = $row->checkedby;
					$deliverby = $row->deliverby;
					$amt = $row->amt;
					$taxamt = $row->taxamt;
					$chemist_id = $row->chemist_id;
					$user = $row->name."(".$row->chemist_id.")";
					//$datetime = date("d-M-y",strtotime($row->date)) . " @ " .$row->time;
					$datetime = date("d-M-y",strtotime($row->date)) . " @ ".$row->mtime;

					$dt = array(
						'sr_no' => $sr_no,
						'id' => $id,
						'dispatchtime' => $dispatchtime,
						'vno'=>$vno,
						'tagno'=>$tagno,
						'gstvno'=>$gstvno,
						'pickedby'=>$pickedby,
						'checkedby'=>$checkedby,
						'deliverby'=>$deliverby,
						'amt'=>$amt,
						'taxamt'=>$taxamt,
						'chemist_id'=>$chemist_id,
						'user'=>$user,
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