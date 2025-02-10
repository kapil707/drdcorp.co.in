<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InvoiceModel extends CI_Model  
{	
	public function __construct() {
        parent::__construct();
		
		$this->load->model("model-drdweb/WhatsAppModel");
		$this->load->model("model-drdweb/EmailModel");
    }
	/********************************************************/
	public function invoice_send_email_whatsapp(){
		
		/*
		$subject = "Drd test message";
		$message = "Drd test message kapil707";
		$user_email_id = "kapil707sharma@gmail.com";
		$email_function= "invoice";
		$email_other_bcc="";
		$mail_server = "";
		*/
		
		$date = date("Y-m-d");
		/***************************************** */
		$where = array('date'=>$date,'deliverby!='=>'','tbl_invoice.status'=>0);

		$this->db->select('tbl_invoice.*, tbl_chemist.name as chemist_name, tbl_chemist.email as chemist_email, tbl_chemist.mobile as chemist_mobile');
        $this->db->from('tbl_invoice');
        $this->db->join('tbl_chemist', 'tbl_chemist.altercode = tbl_invoice.chemist_id', 'left');
        $this->db->where($where);
		$this->db->limit(25);
		$query = $this->db->get();
		/***************************************** */
		$result = $query->result();
		foreach($result as $row){
			
			$id				= $row->id;
			$date 			= $row->date;
			$vno 			= $row->vno;
			$gstvno 		= $row->gstvno;
			$pickedby 		= $row->pickedby;
			$checkedby 		= $row->checkedby;
			$deliverby 		= $row->deliverby;
			$amt 			= $row->amt;
			$taxamt 		= $row->taxamt;
			$acno 			= $row->acno;
			$chemist_id 	= $row->chemist_id;
			$chemist_name 	= $row->chemist_name;
			$chemist_email 	= $row->chemist_email;
			$chemist_mobile = "+91".$row->chemist_mobile;
			
			$newdate 		= strtotime($row->date);
			$newdate 		= date('d-M-Y',$newdate);
			
			/******************************************/
			$invoice_status = 0;
			
			$invoice_item = "<table border='1' width='100%'><tr><td>Sr.No.</td><td>ITEM NAME</td><td>QTY</td><td>BATCH</td><td>EXPIRY</td></tr>";
			
			/************************************************** */
			$where = array('date'=>$date,'vno'=>$vno);			
			
			$this->db->select('tbl_medicine.item_name, tbl_medicine.batchqty as qty, tbl_medicine.batch_no as batch, tbl_medicine.expiry, tbl_invoice_item.*');
			$this->db->from('tbl_invoice_item');
			$this->db->join('tbl_medicine', 'tbl_medicine.i_code = tbl_invoice_item.itemc', 'left');
			$this->db->where($where);
			$query = $this->db->get();
			/************************************************** */
			$get_invoice_item = $query->result();
			if(!empty($get_invoice_item)){
				$invoice_status = 1;
				$myi = 0;
				foreach($get_invoice_item as $row_1){
					$myi++;
					$item_name  = $row_1->item_name;
					$qty  		= $row_1->qty;
					$batch  	= $row_1->batch;
					$expiry  	= $row_1->expiry;
					$invoice_item.= "<tr><td>$myi</td><td>$item_name</td><td>$qty</td><td>$batch</td><td>$expiry</td></tr>";
				}
			}
			$invoice_item.= "</table>";
			/*******************************************************/
			
			$invoice_item_delete = "";
			$whatsapp_message_delete = "<br><br>All items in your order have been billed *without any shortage*";

			/***************delete items***************************** */
			// Path to your JSON file (ensure this file exists)
			$jsonFilePath = './invoice_files/'.$date.'/'.$vno.'.json';
			if (!file_exists($jsonFilePath)) {
				
			}else{

				$invoice_item_delete = "<br><br>Following items have been <b>Delete</b> from your order: <br><br><table border='1' width='100%'><tr><td>Sr.No.</td><td>ITEM NAME</td><td>QTY</td></tr>";
				$whatsapp_message_delete = "<br><br>Following items have been *Delete* from your order";

				$myi = 0;
				$jsonContent = file_get_contents($jsonFilePath);		
				$dataArray = json_decode($jsonContent, true);
				if (json_last_error() === JSON_ERROR_NONE) {
					foreach ($dataArray as $item) {
						//echo "itemc: " . $item['itemc'] . "<br>";
						$myi++;
						$item_name  = $item['item_name'];
						$amt1  		= $item['amt'];
						$invoice_item_delete.= "<tr><td>$myi</td><td>$item_name</td><td>$amt1</td></tr>";
						$whatsapp_message_delete.="<br>*$myi*. *$item_name*<br>*Quantity : $amt1*";
					}
					$invoice_item_delete.= "</table>";
				}
			}
			
			/*
			$where = array('date'=>$date,'vno'=>$vno);
			$query = $this->select_fun("tbl_invoice_item_delete",$where);
			$get_invoice_item_delete = $query->result();
			if(!empty($get_invoice_item_delete)){
				$invoice_item_delete = "<br><br>Following items have been <b>Delete</b> from your order: <br><br><table border='1' width='100%'><tr><td>Sr.No.</td><td>ITEM NAME</td><td>QTY</td></tr>";
				$whatsapp_message_delete = "<br><br>Following items have been *Delete* from your order";
				$myi = 0;
				foreach($get_invoice_item_delete as $row_1){
					$myi++;
					$item_name  = $row_1->item_name;
					$amt1  		= $row_1->amt;
					$invoice_item_delete.= "<tr><td>$myi</td><td>$item_name</td><td>$amt1</td></tr>";
					$whatsapp_message_delete.="<br>*$myi*. *$item_name*<br>*Quantity : $amt1*";
				}
				$invoice_item_delete.= "</table>";
			}
			/*******************************************************/
			
			$link = "https://www.drdistributor.com/iv/$chemist_id/$gstvno";
			$download_link = "https://www.drdistributor.com/id/$chemist_id/$gstvno";
			$android_link = "https://play.google.com/store/apps/details?id=com.drdistributor.dr";
			$website_link = "https://www.drdistributor.com";
			$android_img  = "https://www.drdistributor.com/img_v50/google_play.png";
			
			$email_message = "Hello<br>$chemist_name ($chemist_id),<br><br>Invoice No. <b>$gstvno</b> for Order dated $newdate of the value around <b>Rs.$amt/-</b> has been generated by <b>D.R. Distributors Pvt. Ltd.</b>.<br><br>Please find the list of Items processed.<br><br>$invoice_item $invoice_item_delete <br><br>You can check your invoice by clicking on <a href='$link'>$link</a><br><br>You can download your invoice by clicking on <a href='$download_link'>$download_link</a><br><br>On laptop or pc you can visit following link to start placing orders : $website_link <br><br>Please download our app from Google play store : <br><br><a href='$android_link'><img src='$android_img' width='150px' height='50px'/></a><br><br>Please find the attatchment with this email.";
			
			$whatsapp_message = "Hello <br>$chemist_name ($chemist_id), <br><br>Invoice No. *$gstvno* for order dated $newdate of the value around *Rs.$amt/-* has been generated by *D.R. Distributors Pvt. Ltd.*. <br>$whatsapp_message_delete <br><br>You can check your invoice by clicking on <br>$link<br><br>You can download your invoice by clicking on <br>$download_link <br><br>On laptop or pc you can visit following link to start placing orders : <br> $website_link <br><br>Please download our app from Google play store : ".$android_link;
			
			//invoice_status agar invoice h or item nahi ha to yha insert na ho
			if($invoice_status==1){
				
				//echo $message;
				$subject = "Invoice No. $gstvno From D.R. Distributors Pvt. Ltd.";
				//echo $email_message;
				$message = $email_message;
				$user_email_id = $chemist_email;
				$email_function= "invoice";
				$email_other_bcc="";
				$mail_server = "";
				$file_name1 = $file_name2 = $file_name3 = "";
				$file_name_1 = $file_name_2 = $file_name_3 = "";
				$this->EmailModel->insert_email($user_email_id,$subject,$message,$file_name1,$file_name2,$file_name3,$file_name_1,$file_name_2,$file_name_3,$mail_server,$email_function,$email_other_bcc);
				
				/************************************************/
				//$chemist_mobile = "+919530005050"; 
				if($chemist_mobile != "+91")
				{
					$altercode = $chemist_id;
					//echo $whatsapp_message;
					$this->WhatsAppModel->insert_whatsapp($chemist_mobile,$whatsapp_message,$altercode);
				}
							
				/************************************************/
				$dt = array('status'=>1);
				$where = array('id'=>$id);
				$this->Scheme_Model->edit_fun("tbl_invoice",$dt,$where);
				/************************************************/
			}
		}
	}
}	