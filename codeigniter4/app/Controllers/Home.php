<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        //return view('welcome_message');

        echo "work";
		$start_date = $end_date = date('d-m-Y');

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);

		$start_date = $start_date->format('d/m/Y');
		$end_date 	= $end_date->format('d/m/Y');

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
}
