<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class WebhookWhatsApp extends CI_Controller
{
	public function __construct(){
		parent::__construct();
        //Load model
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

        $date = date('Y-m-d');
        // Step 5: Prepare data for insert
        $save_data = [
            'message_id' => $id,
            'receiver_to' => $to,
            'receiver_from' => $from,
            'message_body' => $body,
            'media_id' => $media_id,
            'reply_id' => $quoted_wid,
            'date'=>$date,
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
}