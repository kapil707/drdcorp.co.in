<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HookTest extends CI_Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function upload(){

		// Step 1: Raw input from webhook
        $raw_input = file_get_contents("php://input");

        // Step 2: Try decoding JSON if expected
        $data = json_decode($raw_input, true);

        file_put_contents(APPPATH . 'logs/webhook_log_' . date('Ymd_His') . '.txt', print_r($data, true));
        if (isset($data['data'])) {
			foreach ($data['data'] as $message) {
                // Step 3: Log the input (for debugging)
                file_put_contents(APPPATH . 'logs/new_webhook_log_' . date('Ymd_His') . '.txt', print_r($message, true));
            }
        }

        // Step 4: Process the data (optional)
        // You can access values like: $data['order_id'], etc.

        // Step 5: Send response
        http_response_code(200);
        echo "Webhook received successfully.";
	}
}