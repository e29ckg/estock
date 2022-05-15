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

// $secret_key = "__test_secret__";
$jwt = null;
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

$jwt = $arr[1];

if($jwt){

    try {
        // $t = 5 * 60 * 60 ; // 
        $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']);     
         
        $token = $decoded->token;
        $user_id = $decoded->user_id;

        $query = "SELECT fullname, role FROM users WHERE user_id = :user_id AND token = :token AND st = 10 LIMIT 0,1";

        $stmt = $conn->prepare( $query );
        $stmt->bindParam(":user_id", $user_id,PDO::PARAM_INT);
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $stmt->execute();
        $num = $stmt->rowCount();
        
        if(empty($num)){
            http_response_code(200);
            echo json_encode(
                array(
                    "status" => "error",
                    "message" => "Access denied..",
                    "ts"=> time()
                ));
                
            die;

        }
        
        http_response_code(200);
        echo json_encode(array(
            "status" => "ok",                
            "message" => "Access granted.",
            // "data" => $decoded,
            "ts"=> time()
        ));
        

    }catch (Exception $e){

        http_response_code(401);

        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }

}
?>