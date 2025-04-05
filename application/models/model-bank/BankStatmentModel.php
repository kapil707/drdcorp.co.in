<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankStatmentModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_statment(){

		//echo " get_statment";
		$result = $this->BankModel->select_query("select * from tbl_statment where status='0' limit 100");
		$result = $result->result();
		foreach($result as $row){
		
			echo $row->id."----<br>";			
			$upi_no = $orderid = $row->customer_reference;
			$amount = $row->amount;
			$date = $row->date;
			$text = $statment_text = $row->narrative;
			//$text = str_replace(["\n", "\r",],"", $text);
			//$text = preg_replace('/\s*\n/', '', $text);
			//$text = str_replace($upi_no, ' ', $text);
			$text = str_replace(' TXN REF NO', 'TXN REF NO', $text);
			$text = str_replace('T XN REF NO', 'TXN REF NO', $text);
			$text = str_replace('TX N REF NO', 'TXN REF NO', $text);
			$from_text = "";

			/**********************************************
			$text = preg_replace("/KKBKH\d+/", "", $text);
			$text = preg_replace("/KK\s*BKH\d+/", "", $text);
			$text = preg_replace("/KKB\s*KH\d+/", "", $text);
			$text = preg_replace("/KKBK\s*H\d+/", "", $text);
			$text = preg_replace("/KKBKH\s*\d+/", "", $text); 

			$text = preg_replace("/9300966180/", '', $text);
			$text = preg_replace("/\s*9300966180/", '', $text);
			$text = preg_replace("/9\s*300966180/", '', $text);
			$text = preg_replace("/93\s*00966180/", '', $text);
			$text = preg_replace("/930\s*0966180/", '', $text);
			$text = preg_replace("/9300\s*966180/", '', $text);
			$text = preg_replace("/93009\s*66180/", '', $text);
			$text = preg_replace("/930096\s*6180/", '', $text);
			$text = preg_replace("/9300966\s*180/", '', $text);
			$text = preg_replace("/93009661\s*80/", '', $text);
			$text = preg_replace("/930096618\s*0/", '', $text);

			/*
			$text = preg_replace("/N\s*0/", '', $text);
			$text = preg_replace("/N2632432758889/", '', $text);
			$text = preg_replace("/N\s*2632432758889/", '', $text);
			$text = preg_replace("/N2\s*632432758889/", '', $text);
			$text = preg_replace("/N26\s*32432758889/", '', $text);
			$text = preg_replace("/N263\s*2432758889/", '', $text);
			$text = preg_replace("/N2632\s*432758889/", '', $text);
			$text = preg_replace("/N26324\s*32758889/", '', $text);
			$text = preg_replace("/N263243\s*2758889/", '', $text);
			$text = preg_replace("/N2632432\s*758889/", '', $text);
			$text = preg_replace("/N26324327\s*58889/", '', $text);
			$text = preg_replace("/N263243275\s*8889/", '', $text);
			$text = preg_replace("/N2632432758\s*889/", '', $text);
			$text = preg_replace("/N26324327588\s*89/", '', $text);
			$text = preg_replace("/N263243275888\s*9/", '', $text);

			$text = preg_replace("/AXOMB2639/", '', $text);
			$text = preg_replace("/A\s*XOMB2639/", '', $text);
			$text = preg_replace("/AX\s*OMB2639/", '', $text);
			$text = preg_replace("/AXO\s*MB2639/", '', $text);
			$text = preg_replace("/AXOM\s*B2639/", '', $text);
			$text = preg_replace("/AXOMB\s*2639/", '', $text);
			$text = preg_replace("/AXOMB2\s*639/", '', $text);
			$text = preg_replace("/AXOMB26\s*39/", '', $text);
			$text = preg_replace("/AXOMB263\s*9/", '', $text);
			
			$text = preg_replace('/\s+\d+TXN\s+REF NO/', ' REF NO', $text);
			$text = preg_replace('/\s+\d+\s+REF NO/', ' REF NO', $text);
			//$text = preg_replace('/AX.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/N00.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/PUNBY.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/PUNBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/INDBN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/INDBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/IDFBH.*?REF NO/', ' REF NO', $text); 
			$text = preg_replace('/ID\s*FBH.*?REF NO/', ' REF NO', $text); 
			$text = preg_replace('/ICIN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/YES.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/POD.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/TXN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/FOR.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/CNRBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/N\s*06.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/N06.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/SBIN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/BKIDN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/BINH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/C\s*BINH.*?REF NO/', ' REF NO', $text);


			$text = preg_replace('/AXOIC.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/AXOI\s*C.*?REF NO/', ' REF NO', $text);

			$text = preg_replace('/BKIDP.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/BKID\s*P.*?REF NO/', ' REF NO', $text);

			$text = preg_replace('/INDBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/I\sNDBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/IN\sDBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/IND\sBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/INDB\sH.*?REF NO/', ' REF NO', $text);

			$text = preg_replace('/HDFCH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/H\s*DFCH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/HD\s*FCH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/HDF\s*CH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/HDFC\s*H.*?REF NO/', ' REF NO', $text);

			$text = preg_replace('/SB2.*?-UPI/', ' UPI', $text);*/
			
			echo "<br>".$text;
			
			preg_match('/FROM\s+(.*?)\s+[A-Z]{5}\d{11}/', $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 0;
				echo "<br>0</br>";
			}

			preg_match('/FROM\s+(.*?)\s+[A-Z]{5}+\s*\d{11}/', $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 00;
				echo "<br>00</br>";
			}

			preg_match('/FROM\s+(.*?)\s+[A-Z0-9]+\s*TXN REF NO/i', $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 01;
				echo "<br>01</br>";
			}

			preg_match("/FROM\s+(.+?)\s+930/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 02;
				echo "<br>02</br>";
			}

			/*preg_match("/FROM\s+(.+?)\s+REF/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 1;
				echo "<br>1</br>";
			}

			preg_match("/FROM\s+(.+?)\s+CITI/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 2;
				echo "<br>2</br>";
			}
			
			preg_match("/FROM\s+(.+?)\s*+PAYMENT/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 3;
				echo "<br>3</br>";
			}

			preg_match("/FROM\s+(.+?)\s+SENT/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 4;
				echo "<br>4</br>";
			}

			preg_match("/FROM\s+(.+?)\s+UPI/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 5;
				echo "<br>5</br>";
			}

			preg_match("/FROM\s+(.+?)\s+PAY/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_value = "<b>find2: ".$from_text."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 6;
				echo "<br>6</br>";
			}
			
			preg_match("/FROM\s+(\d+)@\s+(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1])."@".trim($matches[2]);
				$from_text = str_replace("'", "", $from_text);
				$from_text = str_replace(" ", "", $from_text);
				$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 7;
				echo "<br>7</br>";
			}
			
			preg_match("/FROM\s+(\d+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1])."@".trim($matches[2]);
				$from_text = str_replace("'", "", $from_text);
				$from_text = str_replace(" ", "", $from_text);
				$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find2: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 8;
				echo "<br>8</br>";
			}

			//preg_match("/FROM\s+(\w+)\d+@\s*(\w+)/", $text, $matches);
			preg_match("/FROM\s+([\d]+)@\s*([\w]+)/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1])."@".trim($matches[2]);
				$from_text = str_replace("'", "", $from_text);
				$from_text = str_replace(" ", "", $from_text);
				$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find3: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 9;
				echo "<br>9</br>";
			}

			preg_match("/FROM\s+([^\s@]+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1])."@".trim($matches[2]);
				$from_text = str_replace("'", "", $from_text);
				$from_text = str_replace(" ", "", $from_text);
				$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find4: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 10;
				echo "<br>10</br>";
			}

			preg_match("/FROM\s+([^\@]+)@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1])."@".trim($matches[2]);
				$from_text = str_replace("'", "", $from_text);
				$from_text = str_replace(" ", "", $from_text);
				$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find5: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 11;
				echo "<br>11</br>";
			}

			preg_match("/FROM\s+(.*?)\s+PUNBQ/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_text = str_replace("'", "", $from_text);
				//$from_text = str_replace(" ", "", $from_text);
				//$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 12;
				echo "<br>12</br>";
			}

			preg_match("/FROM\s+([\w\s]+)\s+[A-Z0-9]+\s+REF NO/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_text = str_replace("'", "", $from_text);
				//$from_text = str_replace(" ", "", $from_text);
				//$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 13;
				echo "<br>13</br>";
			}

			preg_match("/FROM\s+(.*?)\s+CITI0000/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_text = str_replace("'", "", $from_text);
				//$from_text = str_replace(" ", "", $from_text);
				//$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 14;
				echo "<br>14</br>";
			}

			preg_match("/FROM\s+(.*)/", $text, $matches);
			if (!empty($matches) && empty($from_text)){
				$from_text = trim($matches[1]);
				//$from_text = str_replace("'", "", $from_text);
				//$from_text = str_replace(" ", "", $from_text);
				//$from_text = str_replace("\n", "", $from_text);
				//$from_value = "<b>find6: ".$from_text."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 15;
				echo "<br>15</br>";
			}*/

			echo $from_text."<br>";
			//die();

			$amount = str_replace([",", ".00"], "", $amount);
			$amount = trim($amount);

			$statment_id = $row->id;
			if(!empty($from_text)){
				$row_new = $this->BankModel->select_query("select id,status,from_text from tbl_bank_processing where upi_no='$upi_no'");
				$row_new = $row_new->row();				
				if(empty($row_new->id)){
					$dt = array(
						'amount'=>$amount,
						'date'=>$date,
						'from_text'=>$from_text,
						'upi_no'=>$upi_no,
						'orderid'=>$orderid,
						'from_statment'=>1,
						'statment_id'=>$statment_id,
						'statment_type'=>$statment_type,
						'statment_text'=>$statment_text,						
					);
					$this->BankModel->insert_fun("tbl_bank_processing", $dt);
				}else{
					$where = array('upi_no'=>$upi_no);
					$dt = array(
						'from_text'=>$from_text,
						'from_statment'=>1,
						'statment_id'=>$statment_id,
						'statment_type'=>$statment_type,
						'statment_text'=>$text,
					);
					$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
				}
			}
			/****************************************************** */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>'1',
			);
			$this->BankModel->edit_fun("tbl_statment", $dt,$where);
		}
	}
}	