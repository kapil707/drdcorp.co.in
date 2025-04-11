<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_Model extends CI_Model  
{  
	public function admin_login($username,$password)
	{
		$query = $this->db->query("select tbl_user.*,tbl_user_type.user_type_title from tbl_user left join tbl_user_type on tbl_user.user_type=tbl_user_type.id where (email='$username' or username='$username') ");
		$row = $query->row();
		if($row->email == $username or $row->username==$username)
		{
			$user_id = $row->id;
			$user_password = $row->password;
			$system_ip = $this->input->ip_address();		
			if($user_password ==$password)
			{
				if($row->status == "1")
				{
					$id = $row->id;
					
					$timestamp = time();
					$date = date("Y-m-d",$timestamp);
					$time = date("H:i",$timestamp);

					$dt = array(
						'user_id'=>$user_id,
						'date'=>$date,
						'time'=>$time,
						'timestamp'=>$timestamp,
						'system_ip'=>$system_ip,
					);
					$this->Scheme_Model->insert_fun("tbl_login_time",$dt);

					$user_type = $row->user_type;					
					$user_type_title = $row->user_type_title;		
					$this->load->library('session');

					$session_arr = array(
						'user_id'=>$row->id,
						'name'=>$row->name,
						'user_email'=>$row->email,
						'username'=>$row->username,
						'user_type'=>$user_type,
						'user_type_title'=>$user_type_title,
						'user_password'=>$user_password,
						'image'=>$row->image);
					$this->session->set_userdata($session_arr);
					if($session_arr)
					{
						$return = "1";
					}
				}
				else
				{
					$return = "Your Account Cannot be Approved";
				}
			}
			else
			{
				$return = "Wrong Password";
			}
		}
		else
		{
			$return = "Wrong UserName And Password";
		}
		return $return;
	}		function check_login()	{		$user_id = $this->session->userdata('user_id');		if(empty($user_id) && $user_id =='')		{			redirect(base_url().'admin');		}		if(!empty($user_id) && $user_id !='')		{			redirect(base_url().'admin/dashboard');		}	}
	function check_login1()
	{
		$user_id = $this->session->userdata('user_id');
		if(empty($user_id) && $user_id =='')
		{
			//redirect(base_url().'admin');
		}
	}
	function permissions_check_or_set($page_title,$page_type,$user_type)
	{
		// yha check karta ha ki kya permission ha kya nhi?
		$query = $this->db->query("select * from tbl_permission_page where page_type='$page_type'");
		$row = $query->row();
		if(empty($row->id))
		{
			$dt = array('page_type'=>$page_type,'page_title'=>$page_title,);
			$this->Scheme_Model->insert_fun("tbl_permission_page",$dt);
		}
		$query = $this->db->query("select * from tbl_permission_settings where page_type='$page_type' and user_type='$user_type'");
		$row = $query->row();
		if(empty($row->id))
		{
			$this->session->set_flashdata("message","<p class='font-bold  alert alert-warning m-b-sm'>$page_title you Not Permission to open the page !</p>");
			$this->session->set_flashdata("message_footer","yes");
			$this->session->set_flashdata("message_type","error");
			$this->session->set_flashdata("full_message","$page_title you Not Permission to open the page !");
			redirect('admin/dashboard');
		}
	}
	function permissions_check_menu($page_type)
	{
		/***********************************************/
		$user_type = $this->session->userdata("user_type");
		/***********************************************/
		$query = $this->db->query("select * from tbl_permission_settings where page_type='$page_type' and user_type='$user_type'");
		$row = $query->row();
		if(!empty($row->id))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function Add_Activity_log($message)
	{
		/***********************************************/
		$user_id = $this->session->userdata("user_id");
		/***********************************************/
		$timestamp = time();
		$date = date("Y-m-d",$timestamp);
		$time = date("H:i",$timestamp);
		
		$dt = array('user_id'=>$user_id,'message'=>$message,'date'=>$date,'time'=>$time,'timestamp'=>$timestamp,);
		$this->Scheme_Model->insert_fun("tbl_activity_log",$dt);
	}

	function Manage_orders_fun($vdt)
	{
		//$this->db->distinct();
		//$this->db->select('DISTINCT `order_id`'); //You may use $this->db->distinct('name');  
		$this->db->select('*');
		$this->db->select('sum(sale_rate*quantity) as stockvalue');
		$this->db->select('COUNT(id) AS count');
		$this->db->where('date', $vdt);		
		$this->db->order_by("id","asc");
		$this->db->group_by('order_id');
		return $this->db->get("tbl_order")->result();
	}
	
	function Manage_orders_count_amout_items($order_id)
	{
		//$this->db->distinct();
		//$this->db->select('DISTINCT `order_id`'); //You may use $this->db->distinct('name');  
		$this->db->select('sale_rate,quantity');
		$this->db->where('order_id', $order_id);
		//$this->db->group_by('tbl_order');
		return $this->db->get("tbl_order")->result();
	}
	
	function Manage_invoice_fun($vdt)
	{ 
		$this->db->select('*');
		$this->db>where('date', $vdt);	
		return $this->db->get("tbl_invoice")->result();
	}
	
	function select_result($tbl='',$where='',$orderby='',$asc_desc='',$limit='')
	{
		if($where!="")
		{
			$this->db->where($where);
		}
		if($orderby!="")
		{
			$this->db->order_by($orderby,$asc_desc);
		}
		if($limit!="")
		{
			$this->db->limit($limit);
		}
		return $this->db->get($tbl)->result();	
	}
}  