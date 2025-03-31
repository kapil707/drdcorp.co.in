<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_slider extends CI_Controller {
	var $Page_title = "Manage Slider";
	var $Page_name  = "manage_slider";
	var $Page_view  = "manage_slider";
	var $Page_menu  = "manage_slider";
	var $page_controllers = "manage_slider";
	var $Page_tbl   = "tbl_slider";
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

		$find_medicine_id = $find_medicine_company_id = $find_medicine_company_division = "";
		extract($_POST);
		if(isset($Submit))
		{
			$item_code = $find_medicine_id;
			$comp_code = $find_medicine_company_id;
			$comp_division = $find_medicine_company_division;

			if (!empty($_FILES["image"]["name"]))
			{
				$this->Image_Model->uploadTo = $upload_path;
				$image = $this->Image_Model->upload($_FILES['image']);
				$image = str_replace($upload_path,"",$image);
				
				$this->Image_Model->newPath = $upload_resize;
				$this->Image_Model->newWidth = 1024;
				$this->Image_Model->newHeight = 250;
				$this->Image_Model->resize();
			}
			else
			{
				$image = "";
			}

			$dt = array(
				'slider_type'=>$slider_type,
				'short_order'=>$short_order,
				'funtype'=>$funtype,
				'item_code'=>$item_code,
				'comp_code'=>$comp_code,
				'comp_division'=>$comp_division,
				'image'=>$image,
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

		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/resize/";
		$upload_path 		= "./uploads/$page_controllers/photo/main/";
		$upload_resize 		= "./uploads/$page_controllers/photo/resize/";

		$find_medicine_id = $find_medicine_company_id = $find_medicine_company_division = "";
		
		extract($_POST);
		if(isset($Submit))
		{
			$item_code = $find_medicine_id;
			$comp_code = $find_medicine_company_id;
			$comp_division = $find_medicine_company_division;
	
			if (!empty($_FILES["image"]["name"]))
			{
				$this->Image_Model->uploadTo = $upload_path;
				$image = $this->Image_Model->upload($_FILES['image']);
				$image = str_replace($upload_path,"",$image);
				
				$this->Image_Model->newPath = $upload_resize;
				$this->Image_Model->newWidth = 1024;
				$this->Image_Model->newHeight = 250;
				$this->Image_Model->resize();
			}
			else
			{
				$image = $old_image;
			}			
			
			$dt = array(
				'slider_type'=>$slider_type,
				'short_order'=>$short_order,
				'funtype'=>$funtype,
				'item_code'=>$item_code,
				'comp_code'=>$comp_code,
				'comp_division'=>$comp_division,
				'image'=>$image,
				'status'=>$status,
				'date' => date('Y-m-d'),
				'time' => date('H:i:s'),
				'timestamp' => time(),
			);
			$where  = array('id'=>$id);
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

		$query = $this->db->query("select * from $tbl where id='$id' order by id desc");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/edit",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/manage_medicine/find_medicine",$data);
		$this->load->view("admin/manage_medicine/find_medicine_company",$data);
	}

	public function delete_rec()
	{
		$id = $_POST["id"];
		$Page_title = $this->Page_title;
		$Page_tbl = $this->Page_tbl;	

		$query = $this->db->query("select * from $Page_tbl where id='$id'");
		$row1 = $query->row();
		$name = ucfirst($row1->name);	

		$result = $this->db->query("delete from $Page_tbl where id='$id'");
		if($result)
		{
			$message = "$name Delete Successfully.";
		}
		else
		{
			$message = "$name Not Delete.";
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

		$result = $this->db->query("SELECT * FROM $Page_tbl order by id desc");
		$result = $result->result();
		foreach($result as $row) {

			$sr_no = $i++;
			$id = $row->id;

			$short_order = $row->short_order;
			$slider_type = "Slider ($row->slider_type)";
			if($row->function_type=="0"){
				$function_type = "Not Need";
				$title = "N/a";
			}
			if($row->function_type=="1"){ 
				$function_type = "Medicine ($row->item_code)";
				
				$row1 =  $this->db->query("select item_name,i_code from tbl_medicine where i_code='$row->item_code'")->row();

				$url = "https://www.drdistributor.com/md/$row->item_code";
				$title = "<a href='".$url."' target='_blank'>$row1->item_name</a>";
			}
			if($row->function_type=="2"){ 
				$company_division = $row->company_division;
				if(empty($company_division)){
					$company_division = "N/a";
				}
				$function_type = "Company ($row->company_code) / Division ($company_division)"; 

				$row1 = $this->db->query("select company_full_name from tbl_medicine where compcode='$row->company_code'")->row();

				$new_title = str_replace(" ","-",strtolower($row1->company_full_name));
				if(!empty($row->company_division)){
					$new_title.= "/".strtolower($row->company_division);
				}

				$url = "https://www.drdistributor.com/c/$new_title";
				$title = "<a href='".$url."' target='_blank'>$row1->company_full_name</a>";
			}
			$image = $row->image;
			if(!empty($image)) {
				$image = base_url()."uploads/$this->page_controllers/photo/resize/".$image;
			}
			$datetime = date("d-M-y @ H:i:s", $row->timestamp);

			$dt = array(
				'sr_no' => $sr_no,
				'id' => $id,
				'short_order' => $short_order,
				'slider_type' => $slider_type,
				'function_type' => $function_type,
				'title' => $title,
				'image' => $image,
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