<?php
include_once './database.php';
require "./vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set("Asia/Bangkok");
// This is your client secret

$secret_key = "__test_secret__";
$jwt = null;
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

// print_r($_SERVER);
// echo json_encode(array(
//     "message" => "sd " .$arr[1]
// ));
// exit;

$jwt = $arr[1];

if($jwt){

    try {
        $key = '__test_secret__';
        // $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']);       
        
        $issuer_claim = "localhost"; // this can be the servername
        $audience_claim = "E29CKG";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 1; //not before in seconds
        $expire_claim = $issuedat_claim + 600; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            // "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => $decoded->data
        );

        http_response_code(200);

        // $jwt = JWT::encode($token, $secret_key, 'RS256');
        $jwt = JWT::encode($token, base64_decode(strtr($key, '-_', '+/')), 'HS256');
        echo json_encode(
            array(
                "status" => "ok",
                "message" => "Access granted.",
                // "token" => $jwt,
                "jwt" => $jwt,
                "data" => json_encode($decoded->data),
                // "email" => $decoded->data->email,
                // "fullname" => $decoded->data->fullname,
                "expireAt" => $expire_claim,
                "ts"=> time()
            ));

        // Access is granted. Add code of the operation here 
        // http_response_code(200);
        // echo json_encode(array(
        //     "status" => "ok",
        //     "message" => "Access granted.",
        //     "decoded" => $decoded->data->user_id,
        //     "ts"=> time()
        // ));

    }catch (Exception $e){

        http_response_code(401);

        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }

}
?>