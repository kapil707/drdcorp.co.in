<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_item extends CI_Controller {

	var $Page_title = "Manage Item";
	var $Page_name  = "manage_item";
	var $Page_view  = "manage_item";
	var $Page_menu  = "manage_item";
	var $page_controllers = "manage_item";
	var $Page_tbl   = "tbl_item";
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
		
		extract($_POST);
		if(isset($Submit))
		{
			$dt = array(
				'category_id'=>$category_id,
				'item_code'=>$find_medicine_id,
				'status'=>$status,
				'date' => date('Y-m-d'),
				'time' => date('H:i:s'),
				'timestamp' => time(),
			);
			$result = $this->Scheme_Model->insert_fun($tbl,$dt);
			if($result)
			{
				$message = "Add Successfully.";
				$this->session->set_flashdata("message_type","success");
				redirect(base_url()."admin/$page_controllers/view");
			}
			else
			{
				$message = "Not Add.";
				$this->session->set_flashdata("message_type","error");
			}
		}
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/manage_medicine/find_medicine",$data);
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
		
		extract($_POST);
		if(isset($Submit))
		{
			$dt = array(
				'category_id'=>$category_id,
				'item_code'=>$find_medicine_id,
				'status'=>$status,
				'date' => date('Y-m-d'),
				'time' => date('H:i:s'),
				'timestamp' => time(),
			);
			$where = array('id'=>$id);
			$result = $this->Scheme_Model->edit_fun($tbl,$dt,$where);		
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
		
		$query = $this->db->query("select * from $tbl where id='$id'");
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
		//$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}

	public function view_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;
		$Page_tbl = $this->Page_tbl;

		$result = $this->db->query("SELECT tbl_item.id,tbl_item.timestamp,tbl_item_category.title as category,tbl_medicine.i_code,tbl_medicine.item_name,tbl_medicine.image1 FROM `tbl_item` left join tbl_item_category on tbl_item.category_id=tbl_item_category.id left join tbl_medicine on tbl_item.item_code=tbl_medicine.i_code order by tbl_item.id desc");
		$result = $result->result();
		foreach($result as $row) {

			$sr_no = $i++;
			$id = $row->id;

			$item_code = $row->i_code;
			$url = "https://www.drdistributor.com/md/$item_code";
			$item_name = "<a href='".$url."' target='_blank'>$row->item_name</a>";

			$image = base_url().$row->image1;
			$item_category = $row->category;

			$new_title = str_replace(" ","-",strtolower($item_category));
			$url = "https://www.drdistributor.com/c/$new_title";
			$item_category = "<a href='".$url."' target='_blank'>$item_category</a>";
			
			$datetime = date("d-M-y @ H:i:s", $row->timestamp);

			$dt = array(
				'sr_no' => $sr_no,
				'id' => $id,
				'item_name' => $item_name,
				'item_code' => $item_code,
				'image' => $image,
				'item_category' => $item_category,
				'datetime'=>$datetime,
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