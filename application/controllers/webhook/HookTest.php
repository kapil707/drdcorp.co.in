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
            $where = array('id'=>$row->id);
            $dt = array('status'=>'1','gemini_text'=>$text);
            $this->BankWebhookModel->update_message($dt,$where);
        }
    }
}