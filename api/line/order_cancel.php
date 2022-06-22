<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	date_default_timezone_set("Asia/Bangkok");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "./config.php";
	$data = json_decode(file_get_contents("php://input"));
	$user_data = json_decode($data->user_data);
	$fullname = $user_data->fullname;
	$ord_id = $data->ord_id;

	
	$sMessage = "❌ ยกเลิกการเบิก 🛒\n";
	$sMessage .= "🧑ผู้เบิก : ". $fullname . "\n";
	$sMessage .= "(". $ord_id. ")" ;

		
	$chOne = curl_init(); 
	curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt( $chOne, CURLOPT_POST, 1); 
	curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
	$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
	curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
	$result = curl_exec( $chOne ); 

	//Result error 
	if(curl_error($chOne)) 
	{ 
		echo 'error:' . curl_error($chOne); 
	} 
	else { 
		$result_ = json_decode($result, true); 
		// echo "status : ".$result_['status']; echo "message : ". $result_['message'];
		http_response_code(200);
		echo json_encode(array(
			'status' => $result_['status'], 
			'message' =>  $result_['message'], 
		));

	} 
	curl_close( $chOne );   
?>