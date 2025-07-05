<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobWebHook extends CI_Controller 
{
	public function __construct(){
		parent::__construct();
		//Load model
        $this->load->model("model-bank/BankModel");
        $this->load->model('model-bank/BankWebhookModel');
	}

	public function update_webhook_reply_message(){
		echo "update_webhook_reply_message";
		$this->BankWebhookModel->update_webhook_reply_message();
	}

	public function test(){

        $apiKey = $this->Scheme_Model->get_website_data("gemini_apikey");

        $image_url = 'https://api.wassi.chat/v1/chat/66faf180345d460e9984e4ac/files/68683285ddc86bce8a1c9000/download?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e';

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
						//"text" => "Extract all readable text from this image."
						"text" => "From this document text, extract the following in JSON: 'transaction_id, amount, date, account_number, ifsc_code,utr,upi ref. no' Return only valid JSON: and Extract all readable text from this image."
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

		$responseArray = json_decode($response, true);

		print_r($responseArray);

		// Step 1: Extract Gemini's wrapped JSON text
		$json_block = $responseArray['candidates'][0]['content']['parts'][0]['text'] ?? '';

		if ($json_block) {
			// Step 2: Strip ```json and ``` from markdown
			$clean_json = preg_replace('/^```json|```$/', '', trim($json_block));

			// Step 3: Decode into array
			$data = json_decode(trim($clean_json), true);

			// Step 4: Output each field
			echo "Transaction ID: " . ($data['transaction_id'] ?? 'N/A') . "\n";
			echo "Amount: " . ($data['amount'] ?? 'N/A') . "\n";
			echo "Date: " . ($data['date'] ?? 'N/A') . "\n";
			echo "Account Number: " . ($data['account_number'] ?? 'N/A') . "\n";
			echo "IFSC Code: " . ($data['ifsc_code'] ?? 'N/A') . "\n";
			echo "UTR: " . ($data['utr'] ?? 'N/A') . "\n";
			echo "UPI Ref No: " . ($data['upi ref. no'] ?? 'N/A') . "\n";
		} else {
			echo "Gemini returned no usable response.\n";
		}
	}

	public function get_gemini_response(){

        $apiKey = $this->Scheme_Model->get_website_data("gemini_apikey");

        $query = $this->BankModel->select_query("SELECT * FROM `webhook_messages` where media_id!='' and status=0 limit 1");
	    $result = $query->result();
        foreach($result as $row) {

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
                            //"text" => "Extract all readable text from this image."
							"text" => "From this document text, extract the following in JSON: 'transaction_id, amount, date, account_number, ifsc_code,utr,upi ref. no' Return only valid JSON:"
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

            /*$data = json_decode($response, true);

			$status = "0";
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $data['candidates'][0]['content']['parts'][0]['text'];
				$status = "1";
            } else {
                $text = "Error or no text found";
				$status = "2";
            }*/

			$responseArray = json_decode($response, true);

			// Step 1: Extract Gemini's wrapped JSON text
			$json_block = $responseArray['candidates'][0]['content']['parts'][0]['text'] ?? '';

			if ($json_block) {
				// Step 2: Strip ```json and ``` from markdown
				$clean_json = preg_replace('/^```json|```$/', '', trim($json_block));

				// Step 3: Decode into array
				$data = json_decode(trim($clean_json), true);

				// Step 4: Output each field
				$transaction_id = ($data['transaction_id'] ?? 'N/A');
				$amount = ($data['amount'] ?? 'N/A');
				$date = ($data['date'] ?? 'N/A');
				$account_number = ($data['account_number'] ?? 'N/A');
				$ifsc_code = ($data['ifsc_code'] ?? 'N/A');
				$utr = ($data['utr'] ?? 'N/A');
				$upi_no =($data['upi ref. no'] ?? 'N/A');
				$status = 1;
			} else {
				echo "Gemini returned no usable response.\n";
				$status = 0;
			}

			$amount = str_replace([",", ".00"], "", $amount);

            $transaction_id = trim($transaction_id);
			$amount = trim($amount);
			$account_number = trim($account_number);
			$ifsc_code = trim($ifsc_code);
			$utr = trim($utr);
			$upi_no = trim($upi_no);

            $where = array('id'=>$row->id);
            $dt = array('status'=>$status,'gemini_text'=>$text,'transaction_id'=>$transaction_id,'amount'=>$amount,'account_number'=>$account_number,'ifsc_code'=>$ifsc_code,'upi_no'=>$upi_no,);

            print_r($dt);
            $this->BankWebhookModel->update_message($dt,$where);
        }
    }
}