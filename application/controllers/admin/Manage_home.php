<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_home extends CI_Controller {
	var $Page_title = "Manage Home";	
	var $Page_name  = "manage_home";
	var $Page_view  = "manage_home";
	var $Page_menu  = "manage_home";
	var $page_controllers = "manage_home";
	var $Page_tbl   = "tbl_home";
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
			$category 		= explode ("_", $category_id);
			$type 			= $category["0"];
			$category_id 	= $category["1"];
			
			$dt = array(
				'seq_id'=>$seq_id,
				'type'=>$type,
				'category_id'=>$category_id,
				'status'=>$status,
				'date' => date('Y-m-d'),
				'time' => date('H:i:s'),
				'timestamp' => time(),
			);				
			$result = $this->Scheme_Model->insert_fun($tbl,$dt);
			if($result)
			{
				$message_db = "($property_title) - Add Successfully.";
				$message = "Add Successfully.";
				$this->session->set_flashdata("message_type","success");
			}
			else
			{
				$message_db = "($property_title) - Not Add.";
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

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
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
			$category 		= explode ("_", $category_id);
			$type 			= $category["0"];
			$category_id 	= $category["1"];
			
			$dt = array(
				'seq_id'=>$seq_id,
				'type'=>$type,
				'category_id'=>$category_id,
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
	
	public function delete_photo()
	{
		$path = $_POST["path"];
		$type_me = $_POST["type_me"];
		
		$Page_title = $this->Page_title;
		$Page_name 	= $this->Page_name;
		$Page_view 	= $this->Page_view;
		$Page_menu 	= $this->Page_menu;
		$Page_tbl 	= $this->Page_tbl;
		$page_controllers 	= $this->page_controllers;
				
		$upload_path = "./uploads/$page_controllers/photo/";
		$result = $this->db->query("update $Page_tbl set $type_me='' where $type_me='$path'");
		if($result!="")
		{
			$filename = $upload_path.$path;
			$message = "Delete Photo Successfully.";
			if (file_exists($filename)) 
			{
    			unlink($filename);
			}
		}
		else
		{
			$message = "Photo Not Update.";
		}
		$message = $Page_title." - ".$message;
		$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}
	
	public function sort_order_check($str)
	{		
		$sort_order = $this->input->post('sort_order');
		
		$Page_tbl 	= $this->Page_tbl;
		
		$query =  $this->db->query ("select id from $Page_tbl where sort_order='$sort_order'");
		$count	=  $query->num_rows();
		if ($count > 0) 
		{
			 $this->form_validation->set_message('sort_order_check','This field already used in database Please try different!');
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}

	public function view_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;
		$Page_tbl = $this->Page_tbl;

		$result = $this->db->query("SELECT * FROM $Page_tbl order by id desc");
		$result = $result->result();
		foreach($result as $row) {

			$sr_no = $i++;
			$id = $row->id;

			$seq_id = $row->seq_id;
			$type = $row->type;
			$category_id = $row->category_id;
			$isdefault = 0;
			if($type!="divisioncategory" && $type!="itemcategory"){
				$category_type = ucfirst($type);
				$category_name = ucfirst($type)." ($category_id)";
				if($type=="notification" || $type=="invoice" || $type=="menu"){
					$category_name = ucfirst($type)." (Main)";
					$isdefault = 1;
				}
			}else{
				$category_type = ucfirst($type);

				if($type=="divisioncategory"){
					$row1 = $this->db->query("SELECT * FROM tbl_company_division_category where id='$category_id'")->row();
					if(!empty($row1)){
						$category_name = $row1->title;
					}
				}

				if($type=="itemcategory"){
					$row1 = $this->db->query("SELECT * FROM tbl_item_category where id='$category_id'")->row();
					if(!empty($row1)){
						$new_title = str_replace(" ","-",strtolower($row1->title));
						$category_name = "<a href='https://www.drdistributor.com/c/ic/".$new_title."' target='_blank'>".$row1->title."</a>";
					}
				}
			}
			$datetime = date("d-M-y @ H:i:s", $row->timestamp);

			$dt = array(
				'sr_no' => $sr_no,
				'id' => $id,
				'seq_id' => $seq_id,
				'category_type' => $category_type,
				'category_name' => $category_name,
				'isdefault' => $isdefault,
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