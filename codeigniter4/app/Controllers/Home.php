<?php

namespace App\Controllers;

class Home extends BaseController
{
    
    public function index()
    {
        //return view('welcome_message');

        echo "work";
		$start_date = $end_date = date('d/m/Y');

		$sender_name_place = "Online%20Details";

		//Created a GET API
		echo $url = "https://www.drdistributor.com/my_api/Api46/get_top_menu_api";

		// HTTP headers (Authorization)
		$options = [
			"http" => [
				"method" => "GET",
				"header" => "Content-Type: application/json\r\n"
			]
		];

		$context = stream_context_create($options);
		$response = file_get_contents($url, false, $context);

		if ($response === FALSE) {
			die("API call failed.");
		}

		echo $response;
    }
}
