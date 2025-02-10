<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SliderModel extends CI_Model  
{ 	
	function slider_to_url($funtype="",$compid="",$division=""){
		$url = "#";
		if($funtype==2)
		{
			$url = base_url()."category/featured_brand/".$compid."/".$division;
		}
		return $url;
	}
	function slider_to_android($funtype=""){
		$return = "";
		if($funtype==2)
		{
			$return = "featured_brand";
		}
		return $return;
	}
	public function slider($slider_type=1)
	{
		$jsonArray = array();
		
		$where = array('status'=>1,'slider_type'=>$slider_type);
		$this->db->where($where);
		$this->db->order_by('RAND()');
		$query = $this->db->get("tbl_slider")->result();
		foreach ($query as $row)
		{
			$id			=	$row->id;
			$funtype	=	$row->funtype;
			$itemid	    =	$row->itemid;
			$division	=	$row->division;
			$compid		=	$row->compid;
			if($funtype==2){
				$itemid	    = $compid;
			}
			$image 		= 	constant('img_url_site')."uploads/manage_slider/photo/main/".$row->image;
			$web_action = $this->slider_to_url($funtype,$compid,$division);
			$android_action = $this->slider_to_android($funtype);
			
			$title = "";

			$dt = array(
				'item_id' => $id,
				'item_title' => $title,
				'item_type' => $funtype,
				'item_code' => $itemid,
				'item_division' => $division,
				'item_image' => $image,
				'item_web_action' => $web_action,
				'item_page_type' => $android_action,
			);
			$jsonArray[] = $dt;
		}
		//$jsonString  = json_encode($jsonArray);
		
		$return["items"] = $jsonArray;
		return $return;	
	}
}