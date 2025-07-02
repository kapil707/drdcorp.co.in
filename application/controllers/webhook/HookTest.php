<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HookTest extends CI_Controller
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

    public function test($mykey){
        // Assume this is the PHP file you're running in Codingter 3.0
        // Let's say you're getting the image file via some input mechanism
        // In a real web application, this would come from $_FILES array.
        // For Codingter 3.0, you might have a specific function to get input.

        // Placeholder for getting the image file content or path in Codingter 3.0
        // You need to replace this with how Codingter 3.0 handles file uploads.
        // For example, it might provide the file content directly, or a temporary path.

        // --- REPLACE THIS SECTION WITH HOW CODINGTER 3.0 HANDLES FILE INPUT ---
        // Example 1: If Codingter 3.0 gives you the raw image data (binary string)
        $image_data_base64 = ''; // Base64 encoded image data from Codingter 3.0 input
        // Example 2: If Codingter 3.0 gives you a temporary file path
        $uploaded_image_path = 'https://api.wassi.chat/v1/chat/66faf180345d460e9984e4ac/files/6864ef25d62c68fa8bb725ed/download?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e'; // Example path, replace with actual.
        // To read the content from a path:
        if (file_exists($uploaded_image_path)) {
            $image_data_base64 = base64_encode(file_get_contents($uploaded_image_path));
        } else {
            // Handle error: image file not found
            echo json_encode(['error' => 'Image file not found at: ' . $uploaded_image_path]);
            exit;
        }
        // --- END OF REPLACE SECTION ---


        // --- OCR using Google Cloud Vision API (via cURL) ---
        // You will need a Google Cloud Project with the Vision API enabled
        // and an API Key. Replace 'YOUR_GOOGLE_CLOUD_API_KEY' with your actual key.
        // This key should be kept secure.

        $google_cloud_api_key = $mykey;
        $api_endpoint = 'https://vision.googleapis.com/v1/images:annotate?key=' . $google_cloud_api_key;

        $request_body = json_encode([
            'requests' => [
                [
                    'image' => [
                        'content' => $image_data_base64 // Base64 encoded image data
                    ],
                    'features' => [
                        [
                            'type' => 'TEXT_DETECTION'
                        ]
                    ]
                ]
            ]
        ]);

        $ch = curl_init($api_endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($request_body)
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $text_from_image = null;
        if ($http_code === 200) {
            $api_response = json_decode($response, true);
            if (isset($api_response['responses'][0]['fullTextAnnotation']['text'])) {
                $text_from_image = $api_response['responses'][0]['fullTextAnnotation']['text'];
            } elseif (isset($api_response['responses'][0]['textAnnotations'][0]['description'])) {
                // Sometimes fullTextAnnotation might not be present, but textAnnotations[0] holds the overall text
                $text_from_image = $api_response['responses'][0]['textAnnotations'][0]['description'];
            }
        } else {
            // Handle API error
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'OCR API request failed',
                'http_code' => $http_code,
                'api_response' => json_decode($response, true)
            ]);
            exit;
        }

        if ($text_from_image === null) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No text detected in the image or failed to parse OCR response.']);
            exit;
        }

        // --- Text Extraction Logic (Same as before) ---
        function extract_info_from_text($text_input) {
            $transaction_id = null;
            $amount = null;
            $date = null;
            $ifsc_code = null;
            $account_number = null;

            // Example for transaction_id
            if (preg_match('/Transaction ID\s+(T\d{17})/', $text_input, $matches)) {
                $transaction_id = $matches[1];
            }

            // Example for amount (looking for "₹" followed by numbers with commas/dots)
            if (preg_match('/₹(\d{1,3}(?:,\d{3})*(?:\.\d+)?)/', $text_input, $matches)) {
                $amount = $matches[1];
            } elseif (preg_match('/INR\s*([\d,.]+)/', $text_input, $matches)) { // Alternative for INR
                $amount = $matches[1];
            }

            // Example for date (looking for "dd Mon YYYY")
            if (preg_match('/\b(\d{2} (?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{4})\b/', $text_input, $matches)) {
                $date = $matches[1];
            } elseif (preg_match('/\b(\d{2}\/\d{2}\/\d{4})\b/', $text_input, $matches)) { // dd/mm/yyyy
                $date = $matches[1];
            } elseif (preg_match('/\b(\d{2}-\d{2}-\d{4})\b/', $text_input, $matches)) { // dd-mm-yyyy
                $date = $matches[1];
            }


            // Example for account_number (looking for "XXXXXXXXXX1710" or similar masked numbers)
            // Prioritize patterns like XXXXXXXX1710 or explicit "A/c No.:"
            if (preg_match('/(X{8,10}\d{4})/', $text_input, $matches)) {
                $account_number = $matches[1];
            } elseif (preg_match('/Account No\.\s*:\s*(\d+)/i', $text_input, $matches)) { // Case-insensitive "Account No.:"
                $account_number = $matches[1];
            } elseif (preg_match('/A\/c No\.\s*:\s*(\d+)/i', $text_input, $matches)) { // Case-insensitive "A/c No.:"
                $account_number = $matches[1];
            }


            // IFSC Code is often 11 characters, alphanumeric, first 4 are alphabetic.
            // Example regex for IFSC: [A-Z]{4}0[A-Z0-9]{6}
            // As it's not in your image, it will likely remain null.
            // If it were present, you might add:
            // if (preg_match('/[A-Z]{4}0[A-Z0-9]{6}/', $text_input, $matches)) {
            //     $ifsc_code = $matches[0];
            // }

            $result = [
                "transaction_id" => $transaction_id,
                "amount" => $amount,
                "date" => $date,
                "ifsc_code" => $ifsc_code,
                "account_number" => $account_number
            ];

            return json_encode($result, JSON_PRETTY_PRINT);
        }

        // Execute the extraction
        $json_output = extract_info_from_text($text_from_image);

        header('Content-Type: application/json');
        echo $json_output;
    }
}