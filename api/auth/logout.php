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

// print_r($_SERVER);
// echo json_encode(array(
//     "message" => "sd " .$arr[1]
// ));
// exit;

$jwt = $arr[1];

if($jwt){

    try {
        // $t = 5 * 60 * 60 ; // 
        $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']);       
        
        $user_id = $decoded->user_id;
        $token_gen = bin2hex(random_bytes(16));

        $query = "UPDATE users SET token=:token WHERE user_id=:user_id";

        $stmt = $conn->prepare( $query );
        $stmt->bindParam(":token", $token_gen, PDO::PARAM_STR);
        $stmt->bindParam(":user_id", $user_id,PDO::PARAM_INT);
        $stmt->execute();
    
        http_response_code(200);
        echo json_encode(
            array(
                "status" => "success",                
                "message" => "You Logout Success",                
                "ts"=> time()
            ));        

    }catch (Exception $e){

        http_response_code(401);

        echo json_encode(array(
            "status" => "error",
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }

}
?>