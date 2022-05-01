<?php
include_once './database.php';
require './vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set("Asia/Bangkok");


$email = '';
$password = '';

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();


$data = json_decode(file_get_contents("php://input"));

// http_response_code(200);
//     echo json_encode(array("message" => $data->username."User was successfully registered."));
//     exit;

$email = $data->username;
$username = $data->username;
$password = $data->password;

$table_name = 'users';

$query = "SELECT user_id, fullname, password, role FROM " . $table_name . " WHERE email = ? OR username = ? LIMIT 0,1";

$stmt = $conn->prepare( $query );
$stmt->bindParam(1, $email);
$stmt->bindParam(2, $username);
$stmt->execute();
$num = $stmt->rowCount();

if($num > 0){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $user_id = $row['user_id'];
    $fullname = $row['fullname'];
    $password2 = $row['password'];
    $role = $row['role'];

    if(password_verify($password, $password2))
    {
        // This is your client secret
        $key = '__test_secret__';
        // $secret_key = "99299929";
        $issuer_claim = "localhost"; // this can be the servername
        $audience_claim = "E29CKG";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 1; //not before in seconds
        $expire_claim = $issuedat_claim + 60; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            // "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "user_id" => $user_id,
                "fullname" => $fullname,
                "role" => $role,
                "email" => $email
        ));

        http_response_code(200);

        // $jwt = JWT::encode($token, $secret_key, 'RS256');
        $jwt = JWT::encode($token, base64_decode(strtr($key, '-_', '+/')), 'HS256');
        echo json_encode(
            array(
                "status" => "ok",
                "message" => "Successful login.",
                // "token" => $jwt,
                "jwt" => $jwt,
                "email" => $email,
                "expireAt" => $expire_claim
            ));
    }
    else{

        http_response_code(401);
        echo json_encode(array("message" => "Login failed. Password Incorrect", "password" => $password));
    }
}else{
        http_response_code(200);
        echo json_encode(array("message" => "Login failed. Username Not Found", "username" => $username));
    
}

?>