<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HookTest extends CI_Controller
{
	public function __construct(){
		parent::__construct();
        //Load model
        $this->load->model("model-bank/BankModel");
        $this->load->model('model-bank/BankWebhookModel');
	}

	public function upload(){

		// Step 1: Raw input
        $raw_input = file_get_contents("php://input");

        // Step 2: Decode JSON
        $data_array = json_decode($raw_input, true);

        // Step 3: Extract 'data' section
        $data = isset($data_array['data']) ? $data_array['data'] : [];

        // Step 4: Extract required fields
        $id   = isset($data['id']) ? $data['id'] : '';
        $to   = isset($data['to']) ? $data['to'] : '';
        $from = isset($data['from']) ? $data['from'] : '';
        $body = isset($data['body']) ? $data['body'] : null;

        $media_id = isset($data['media']['id']) ? $data['media']['id'] : null;
        $quoted_wid = isset($data['quoted']['wid']) ? $data['quoted']['wid'] : null;

        // Step 5: Prepare data for insert
        $save_data = [
            'message_id' => $id,
            'receiver_to' => $to,
            'receiver_from' => $from,
            'message_body' => $body,
            'media_id' => $media_id,
            'reply_id' => $quoted_wid,
        ];

        // Step 6: Save to database
        if ($id && $to) {
            $this->BankWebhookModel->insert_message($save_data);
        }
        
        file_put_contents(APPPATH . 'logs/'.$id.'.txt', print_r($data_array, true));

        // Response
        http_response_code(200);
        echo "Data received and stored.";
	}

    public function test(){

        $apiKey = $this->Scheme_Model->get_website_data("gemini_apikey");

        $query = $this->BankModel->select_query("SELECT * FROM `webhook_messages` where media_id!='' and status=0 limit 1");
	    $row = $query->row();
        if(!empty($row->id)){

            $image_url = 'https://api.wassi.chat/v1/chat/66faf180345d460e9984e4ac/files/'.$row->media_id.'/download?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e';

            $imageData = base64_encode(file_get_contents($image_url));

            $payload = [
                "contents" => [[
                    "role" => "user",
                    "parts" => [
                        [
                            "inline_data" => [
                                "mime_type" => "image/jpeg",
                                "data" => $imageData
                            ]
                        ],
                        [
                            "text" => "Extract all readable text from this image."
                        ]
                    ]
                ]]
            ];

            $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                echo $text = $data['candidates'][0]['content']['parts'][0]['text'];
            } else {
                echo $text = "Error or no text found:";
                print_r($data);
            }

            $text = str_replace("Here's a transcription of the visible text in the image:", '', $text);

            $upi_no = "";
			$amount = "0.0";
			//********amount********** */
			// Regular Expression to extract amount.
			preg_match('/[₹\?]([\d,]+)/', $text, $matches);
			// Check if match is found
			if (!empty($matches[1])) {
				$amount = $matches[1];
			}
			if($amount == "0.0"){
				preg_match('/[₹\?]([\d,]+(?:\.\d{1,2})?)/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/Amount:\s*([\d,]+)/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/\*\*Transfer Amount:\*\* ([\d,]+\.\d{2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/\*\*Transfer Amount:\*\* ([\d,]+\.\d{2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match_all('/\?[\s]*([\d,.]+)/', $text, $matches);

				if (!empty($matches[1])) {
					$amount = !empty($matches[1][0]) ? $matches[1][0] : $matches[1][1] ?? '0.0';
				}
			}

			if($amount == "0.0"){
				preg_match('/Rs ([\d,]+\.?\d{0,2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}

			if($amount == "0.0"){
				preg_match('/INR ([\d,]+\.?\d{0,2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}

			if($amount == "0.0"){
				preg_match('/\bRs\.\s?(\d{1,3}(?:,\d{3})*\.\d{2})\b/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}

			if($amount == "0.0"){
				preg_match('/\b(\d{1,3}(?:,\d{3})*\.\d{2})\b/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}

			$type = 0;
			/************************************************** */
			// Regular Expression to extract UTR No.
			preg_match('/Reference No\. \(UTR No\.\/RRN\): (\S+)/', $text, $matches);
			// Check if match is found
			if (!empty($matches[1])) {
				$upi_no = $matches[1];
				$type = 1;
				//echo "UTR Number: " . $matches[1]; // Output: KKBKH25070930804
			}

			if(empty($upi_no)){
				preg_match('/UTR:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 2;
					//echo "UTR Number: " . $matches[1];
				}
			}
            $transaction_id = "";
			if(empty($upi_no)){
				preg_match('/UPI transaction ID:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$transaction_id = $upi_no = $matches[1];
					$type = 3;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/Transaction ID:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$transaction_id = $upi_no = $matches[1];
					$type = 4;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref\. No:\s*([\d\s]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 5;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI txn id:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 6;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref ID:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 7;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI transaction ID\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 8;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref\. No:\s*([\d\s]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 9;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				// Regex se UPI Reference Number extract karna
				preg_match('/\*\*UPI Ref\. No:\*\*\s*([\d\s]+)/', $text, $matches);

				if (!empty($matches[1])) {
					$upi_no = preg_replace('/\s+/', '', $matches[1]); // Space remove karna
					$type = 10;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se UPI Reference Number extract karna
				preg_match('/\*\*\s*Reference No\. \(UTR No\.\/RRN\):\s*\*\*\s*(\w+\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 11;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\b\d{12}\b/', $text, $matches);
				if (!empty($matches[0])) {
					$upi_no = $matches[0];
					$type = 12;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\*\*Transaction ID:\*\*\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$transaction_id = $upi_no = $matches[1];
					$type = 13;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Transaction ID\s*\n*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$transaction_id = $upi_no = $matches[1];
					$type = 14;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\*\*Reference Number:\*\*\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 15;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Reference\s*Number\s*([A-Z0-9]+)/i', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 16;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/UTR:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 17;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Reference Number:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 18;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\*\*Transaction ID\*\*\s*(\S+)/', $text, $matches);
				if (!empty($matches[1])) {
					$transaction_id = $upi_no = $matches[1];
					$type = 19;
					//echo "UTR Number: " . $upi_no;
				} 
			}

            if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/UPI Reference Number[:\s]+([\d\s]+)/i', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 20;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			$amount = str_replace([",", ".00"], "", $amount);

            $transaction_id = trim($transaction_id);
			$upi_no = trim($upi_no);
			$amount = trim($amount);

            $where = array('id'=>$row->id);
            $dt = array('status'=>'1','gemini_text'=>$text,'transaction_id'=>$transaction_id,'upi_no'=>$upi_no,'amount'=>$amount);

            print_r($dt);
            $this->BankWebhookModel->update_message($dt,$where);
        }
    }
}