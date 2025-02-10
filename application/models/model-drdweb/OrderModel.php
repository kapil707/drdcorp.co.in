<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OrderModel extends CI_Model  
{	
    public function __construct(){
		parent::__construct();
        $this->load->model("model-drdweb/EmailModel");
        $this->load->model("model-drdweb/WhatsAppModel");
        $this->load->model("model-drdweb/NotificationModel");
    }
    public function run_job(){

        $where = array('tbl_cart_order.status'=>0);
        $this->db->select('tbl_cart_order.*, tbl_chemist.name, tbl_chemist.email, tbl_chemist.mobile');
        $this->db->from('tbl_cart_order');
        $this->db->join('tbl_chemist', 'tbl_cart_order.chemist_id = tbl_chemist.altercode', 'left');
        $this->db->where($where);
        $this->db->limit(25);
        $query = $this->db->get()->result();
        foreach($query as $row)
        {
            $order_id   = $row->id;
            $remarks 	= $row->remarks;
            $user_type 	= $row->user_type;
            $chemist_id = $row->chemist_id;
            $salesman_id= $row->salesman_id;
            $total_rs 	= $row->total;
            $total_rs = round($total_rs);

            $this->db->query("update tbl_cart_order set status=1 where id='$order_id'");

            /**************************************************** */
            $acm_name		= ucwords(strtolower($row->name));
            $acm_email 		= $row->email;
            $acm_mobile 	= $row->mobile;	
            
            /*****************Excel file ke liya*****************************
            // yha code band kiya ha ku ki url diya ha file nahi bj rhay no need file ko download ka url bj rahy ha osi say sara kam ho raha ha	
            $file_name_1 = $this->Order_Model->excel_save_order_to_server($query,$chemist_excle,"cronjob_download");
            /****************************************************************/	
        
            /*************************************************************/
            $how_to_use              = $this->how_to_use($order_id);
            $how_to_use_for_whatsapp = $how_to_use["for_whatsapp"];
            $how_to_use_for_table 	 = $how_to_use["for_table"];
            $how_to_use_for_html 	 = $how_to_use["for_html"];
            /*************************************************************/

            if($remarks){
                $remarks = "<br>Remarks : ".$remarks;
            }

            /*************************************************************/
            $default_place_order_text = $this->Scheme_Model->get_website_data("default_place_order_text");

            $whatsapp_footer_text = $this->Scheme_Model->get_website_data("whatsapp_footer_text");

            /*****************notification_whatsapp_message**************************/
            $notification_whatsapp_message = "Hello $acm_name ($chemist_id)<br><br>".$default_place_order_text."<br><br>Order No. : $order_id<br>Total Rs. : $total_rs/- $remarks <br> $how_to_use_for_whatsapp <br><b>You can check your order by clicking on </b><br>View : https://www.drdistributor.com/ov/".$chemist_id."/".$order_id." <br>Download : https://www.drdistributor.com/od/".$chemist_id."/".$order_id." ".$whatsapp_footer_text;

            /****************email_message******************************************/
            $email_footer_text = $this->Scheme_Model->get_website_data("email_footer_text");
            $email_message = "Hello $acm_name ($chemist_id)<br><br>".$default_place_order_text."<br><br>Order No. : $order_id<br>Total Rs. : $total_rs/- $remarks <br> $how_to_use_for_table <br><b>You can check your order by clicking on </b><br>View : https://www.drdistributor.com/ov/".$chemist_id."/".$order_id." <br>Download : https://www.drdistributor.com/od/".$chemist_id."/".$order_id." ".$email_footer_text."<br><br>".$how_to_use_for_html;
            
            /****************notification***********************/
            $q_title 		= "New Order - $order_id";
            $q_message		= $notification_whatsapp_message;
            $this->NotificationModel->insert_notification("4",$q_title,$q_message,$chemist_id,"chemist");
            /*****************whatsapp*************************/
            if(!empty($acm_mobile))
            {
                $w_number 		= "+91".$acm_mobile;
                $w_message = $notification_whatsapp_message;
                $this->WhatsAppModel->insert_whatsapp($w_number,$w_message,$chemist_id);
            }
            /*****************whatsapp or sales man*************************/
            if($user_type=="sales")
            {
                $where = array('customer_code'=>$salesman_id);
                $users = $this->Scheme_Model->select_row("tbl_users",$where);
                $salesman_name 		= $users->firstname." ".$users->lastname;
                $salesman_mobile	= $users->cust_mobile;
                $salesman_altercode	= $users->customer_code;
                if($salesman_mobile!="")
                {
                    $w_number 		= "+91".$salesman_mobile;//$c_cust_mobile;
                    $w_message 		= "New Order Placed - $order_id for $acm_name for amount $total_rs";
                    $this->WhatsAppModel->insert_whatsapp($w_number,$w_message,$chemist_id);
                }
            }

            /***************only for group message***********************/
            $notification_whatsapp_message  = str_replace("Hello","",$notification_whatsapp_message);
            $group2_message 	= "New order recieved from ".$notification_whatsapp_message;
            $whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
            $this->WhatsAppModel->insert_whatsapp_group($whatsapp_group2,$group2_message);
            /*************************************************************/
            
            /******************group message******************************/
            $group1_message 	= "New Order Recieved from ".$notification_whatsapp_message."Please check in Easy Sol";
            $whatsapp_group1 = $this->Scheme_Model->get_website_data("whatsapp_group1");
            $this->WhatsAppModel->insert_whatsapp_group($whatsapp_group1,$group1_message);
            /**********************************************************/
            
            /**********************email************************/
            $subject = "DRD Order || ($order_id) || $acm_name ($chemist_id)";            
            $message = "";
            if($user_type == "sales"){
                $message = "Salesman : ".$salesman_name." (".$salesman_altercode.")<br>";
            }		
            $message.=$email_message;
            
            $user_email_id = $acm_email;
            if (filter_var($user_email_id, FILTER_VALIDATE_EMAIL)) {
                //$user_email_id = "drdwebmail1@gmail.com";	
            }
            if(!empty($user_email_id))
            {
                //$file_name1 = $order_id;
                $file_name_1 = $file_name1 = "";
                
                $subject = ($subject);
                $message = ($message);
                $email_function = "new_order";
                /************************************************/
                $row1 = $this->db->query("select * from tbl_email where email_function='$email_function'")->row();
                /***********************************************/
                $email_other_bcc = $row1->email;
                $mail_server = "";
				$file_name1 = $file_name2 = $file_name3 = "";
				$file_name_1 = $file_name_2 = $file_name_3 = "";
				$this->EmailModel->insert_email($user_email_id,$subject,$message,$file_name1,$file_name2,$file_name3,$file_name_1,$file_name_2,$file_name_3,$mail_server,$email_function,$email_other_bcc);
            }
        }
    }

    public function how_to_use($order_id){
        
        $for_html = "<br><br><h2><center>How to use this medicine</center></h2>";
        $for_whatsapp = "<br><b>How to use this medicine</b><br>";
        $for_table = "<br><table width='100%' border='1'><tr><th>SrNo.</th><th>Item Code</th><th>Item Name</th><th>Item quantity</th><th>Price</th><th>Total</th></tr> ";
        
        $i = 0;
        $result = $this->db->query("SELECT tbl_cart.i_code,tbl_cart.item_name,tbl_cart.quantity,tbl_cart.sale_rate FROM tbl_medicine_use left join tbl_cart on tbl_cart.i_code = tbl_medicine_use.item_code where order_id='$order_id'")->result();
        if(empty($result)){
            $for_html = $for_whatsapp = $for_table = "";
        }
        foreach($result as $row)
        {
            $i++;
            $item_code  = $row->i_code;
            $item_name  = $row->item_name;
            $quantity   = $row->quantity;
            $sale_rate  = $row->sale_rate;
            $url = "https://www.drdistributor.com/medicine_use/".$item_code;

            $for_whatsapp.= $i.". ".$item_name."<br>".$url."<br>";

            $for_html.= "<hr><h2><center><b>".$item_name."</b></center></h2><br>";
            $php_files = glob('./medicine_use/'.$item_code.'/*');
            foreach($php_files as $file) {
                $file = str_replace("./","",$file);
                //$file = base_url().$file;
                
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if($ext=="jpg"){
                    $for_html.= "<img src='".base_url().$file."' width='250px' style='object-fit: contain;height: 200px;margin-right:15px;margin-bottom:15px;border-radius:10px;'>";
                }
                if($ext=="mp4"){
                    $for_html.= "<a href='".base_url().$file."'><img src='https://www.drdistributor.com/img_v51/default-video-thumbnail.jpg' width='250px' style='object-fit: contain;height: 200px;margin-right:15px;margin-bottom:15px;border-radius:10px;'></a>";
                }
            }
            $for_table.= "<tr><td>".$i."</td><td>".$item_code."</td><td>".$item_name." <a href='".$url."'>View</a></td><td>".$quantity."</td><td>".$sale_rate."</td><td>".$sale_rate * $row->quantity."</td></tr>";
        }
        if(!empty($for_table)){		
            $for_table .= "</table>";
        }
        
        $return["for_whatsapp"]  = $for_whatsapp;
        $return["for_table"]     = $for_table;
        $return["for_html"]      = $for_html;
        
        return $return;
    }
}