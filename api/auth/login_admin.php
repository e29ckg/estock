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

$email = $data->username;
$username = $data->username;
$password = $data->password;
$table_name = 'users';
$role = 'admin';
try {
    $query = "SELECT user_id, fullname, password, role FROM users WHERE email = ? OR username = ? AND st = 10 AND role= ? LIMIT 0,1";

    $stmt = $conn->prepare( $query );
    $stmt->bindParam(1, $email);
    $stmt->bindParam(2, $username);
    $stmt->bindParam(3, $role);
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
            // $key = '__test_secret__';
            // $t = 100 ; // 
            $t = 8 * 3600 ; // 
            $token_gen = bin2hex(random_bytes(16));

            $query = "UPDATE users SET token=:token WHERE user_id=:user_id";

            $stmt = $conn->prepare( $query );
            $stmt->bindParam(":token", $token_gen, PDO::PARAM_STR);
            $stmt->bindParam(":user_id", $user_id,PDO::PARAM_INT);
            $stmt->execute();

            // $secret_key = "99299929";
            $issuer_claim = "localhost"; // this can be the servername
            $audience_claim = "E29CKG";
            $issuedat_claim = time(); // issued at
            $notbefore_claim = $issuedat_claim + 1; //not before in seconds
            $expire_claim = $issuedat_claim + $t; // expire time in seconds
            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "token" => $token_gen,
                "user_id" => $user_id,
                // "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(                
                    "fullname" => $fullname,
                    "role" => $role,
                    "email" => $email
            ));
            
            $jwt = JWT::encode($token, base64_decode(strtr($key, '-_', '+/')), 'HS256');
            http_response_code(200);
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "user_data" => json_encode(array(                
                        "fullname" => $fullname,
                        "role" => $role,
                        "email" => $email
                    )),
                    "fname" => $fullname,
                    "email" => $email,
                    "expireAt" => $expire_claim
                ));
        }else{

            http_response_code(200);
            echo json_encode(array("status"=>"error","message" => "Login failed. Password Incorrect", "password" => $password));
        }
    }else{
            http_response_code(200);
            echo json_encode(array("status"=>"error","message" => "Login failed. Username Not Found", "username" => $username));
        
    }
}catch (Exception $e){

    http_response_code(401);

    echo json_encode(array(
        "status"=>"error",
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
}

?>