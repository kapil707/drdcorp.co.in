<?php

namespace App\Controllers;

use App\Models\BankModel;

class Home extends BaseController
{
    protected $bankModel;
    public function __construct(){
        $this->bankModel = new BankModel();
	}
    
    public function index()
    {
        //return view('welcome_message');

        echo "work";
		$start_date = $end_date = date('d/m/Y');

		$sender_name_place = "Online%20Details";

		//Created a GET API
		echo $url = "http://192.46.214.43:5000/get_messages_by_status?start_date=$start_date&end_date=$end_date&group=$sender_name_place&status=true";

		// HTTP headers (Authorization)
		$options = [
			"http" => [
				"method" => "GET",
				"header" => "Authorization: Bearer THIRTEENWOLVESWENTHUNTINGBUT10CAMEBACK\r\n".
							"Content-Type: application/json\r\n"
			]
		];

		$context = stream_context_create($options);
		$response = file_get_contents($url, false, $context);

		if ($response === FALSE) {
			die("API call failed.");
		}

		echo $response;
    }

    public function get_statment(){

		//echo " get_statment";
		$result = $this->BankModel->select_query("select * from tbl_statment where status='0' limit 100");
		$result = $result->result();
		foreach($result as $row){
		
			echo $row->id."----<br>";
			$amount1 = $row->amount;
			$date = $row->date;
			echo $text = $row->narrative;
			$text = str_replace(array("\r", "\n"), '', $text);
			$upi_no = $orderid = $row->customer_reference;
			$received_from = "";

			/**********************************************/
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
			$text = preg_replace("/N2632432758889\s*/", '', $text);

			$text = preg_replace("/AXOMB2639/", '', $text);
			$text = preg_replace("/A\s*XOMB2639/", '', $text);
			$text = preg_replace("/AX\s*OMB2639/", '', $text);
			$text = preg_replace("/AXO\s*MB2639/", '', $text);
			$text = preg_replace("/AXOM\s*B2639/", '', $text);
			$text = preg_replace("/AXOMB\s*2639/", '', $text);
			$text = preg_replace("/AXOMB2\s*639/", '', $text);
			$text = preg_replace("/AXOMB26\s*39/", '', $text);
			$text = preg_replace("/AXOMB263\s*9/", '', $text);
			$text = preg_replace("/AXOMB2639\s*/", '', $text);
			/**********************************************/
			$text = preg_replace('/\s+\d+TXN\s+REF NO/', ' REF NO', $text);
			$text = preg_replace('/\s+\d+\s+REF NO/', ' REF NO', $text);
			$text = preg_replace('/AX.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/N00.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/PUNBY.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/INDBN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/ICIN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/YES.*?REF NO/', ' REF NO', $text);

			$text = preg_replace('/SB2.*?-UPI/', ' UPI', $text);
			echo "<br>".$text;

			preg_match("/FROM\s+(.+?)\s+CITI/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 1;
				echo "<br>1</br>";
			}
			
			preg_match("/FROM\s+(.+?)\s*+PAYMENT/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 2;
				echo "<br>2</br>";
			}

			preg_match("/FROM\s+(.+?)\s+SENT/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 3;
				echo "<br>3</br>";
			}

			preg_match("/FROM\s+(.+?)\s+UPI/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 4;
				echo "<br>4</br>";
			}

			preg_match("/FROM\s+(.+?)\s+REF/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 5;
				echo "<br>5</br>";
			}

			preg_match("/FROM\s+(.+?)\s+PAY/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 6;
				echo "<br>6</br>";
			}
			
			preg_match("/FROM\s+(\d+)@\s+(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 7;
				echo "<br>7</br>";
			}
			
			preg_match("/FROM\s+(\d+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find2: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 8;
				echo "<br>8</br>";
			}

			preg_match("/FROM\s+(\w+)\d+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find3: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 9;
				echo "<br>9</br>";
			}

			preg_match("/FROM\s+([^\s@]+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find4: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 10;
				echo "<br>10</br>";
			}

			preg_match("/FROM\s+([^\@]+)@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find5: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 11;
				echo "<br>11</br>";
			}

			preg_match("/FROM\s+(.*?)\s+PUNBQ/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 12;
				echo "<br>12</br>";
			}

			preg_match("/FROM\s+([\w\s]+)\s+[A-Z0-9]+\s+REF NO/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 13;
				echo "<br>13</br>";
			}

			preg_match("/FROM\s+(.*?)\s+CITI0000/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 14;
				echo "<br>14</br>";
			}

			preg_match("/FROM\s+(.*)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 15;
				echo "<br>15</br>";
			}

			echo $received_from."<br>";
			//die();

			$statment_id = $row->id;
			if(!empty($received_from)){
				$row_new = $this->BankModel->select_query("select id,type,status,received_from from tbl_bank_processing where upi_no='$upi_no'");
				$row_new = $row_new->row();
				
				if(empty($row_new->id)){
					$type = "Statment";
					$dt = array(
						'type'=>$type,
						'status'=>1,
						'amount'=>$amount1,
						'date'=>$date,
						'received_from'=>$received_from,
						'upi_no'=>$upi_no,
						'orderid'=>$orderid,
						'statment_id'=>$statment_id,
						'from_statment'=>1,
						'statment_type'=>$statment_type,
					);
					$this->BankModel->insert_fun("tbl_bank_processing", $dt);
				}else{
					$where = array('upi_no'=>$upi_no);
					$status = 0;
					$type = $row_new->type;
					if($type=="SMS")
					{
						$type = "SMS or Update With Statment";
					}
					if(strtolower($row_new->received_from)==strtolower($received_from)){
						$status = $row_new->status;
					}
					$dt = array(
						'type'=>$type,
						'status'=>$status,
						'received_from'=>$received_from,
						'orderid'=>$orderid,
						'statment_id'=>$statment_id,
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
