<?php
require '../dbconfig.php';        // ใช้ dbconfig.php
require './vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$tz = getenv('APP_TIMEZONE') ?: 'Asia/Bangkok';
date_default_timezone_set($tz);

$data = json_decode(file_get_contents("php://input"));

$usernameOrEmail = $data->username ?? '';
$password        = $data->password ?? '';

try {
    $query = "SELECT user_id, fullname, password, role, email 
              FROM users 
              WHERE (email = ? OR username = ?) AND st = 10 
              LIMIT 1";

    $stmt = $dbcon->prepare($query);
    $stmt->bindParam(1, $usernameOrEmail, PDO::PARAM_STR);
    $stmt->bindParam(2, $usernameOrEmail, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $row['password'])) {
            // ✅ ใช้ secret key จาก .env
            $secret_key     = getenv('JWT_SECRET');
            $issuer_claim   = "localhost";
            $audience_claim = "E29CKG";
            $issuedat_claim = time();

            // Access Token (อายุสั้น)
            $expire_claim   = $issuedat_claim + (8 * 3600); // 8 ชั่วโมง
            $token_payload = [
                "iss"  => $issuer_claim,
                "aud"  => $audience_claim,
                "iat"  => $issuedat_claim,
                "exp"  => $expire_claim,
                "data" => [
                    "user_id"  => $row['user_id'],
                    "fullname" => $row['fullname'],
                    "role"     => $row['role'],
                    "email"    => $row['email']
                ]
            ];
            $jwt = JWT::encode($token_payload, $secret_key, 'HS256');

            // Refresh Token (อายุยาวกว่า)
            $refresh_expire = $issuedat_claim + (30 * 24 * 3600); // 30 วัน
            $refresh_payload = [
                "iss"  => $issuer_claim,
                "aud"  => $audience_claim,
                "iat"  => $issuedat_claim,
                "exp"  => $refresh_expire,
                "data" => [
                    "user_id" => $row['user_id']
                ]
            ];
            $refresh_jwt = JWT::encode($refresh_payload, $secret_key, 'HS256');

            // ✅ เก็บ refresh token ลง DB
            $stmt = $dbcon->prepare("UPDATE users SET refresh_token = :rtoken WHERE user_id = :uid");
            $stmt->execute([":rtoken" => $refresh_jwt, ":uid" => $row['user_id']]);

            http_response_code(200);
            echo json_encode([
                "status"       => "success",
                "message"      => "Successful login.",
                "jwt"          => $jwt,
                "refresh_jwt"  => $refresh_jwt,
                "user_data"    => [
                    "user_id"  => $row['user_id'],
                    "fullname" => $row['fullname'],
                    "role"     => $row['role'],
                    "email"    => $row['email']
                ],
                "expireAt"     => $expire_claim,
                "refreshExpAt" => $refresh_expire
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "status"  => "error",
                "message" => "Login failed. Incorrect username or password."
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            "status"  => "error",
            "message" => "Login failed. Incorrect username or password."
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Internal server error.",
        "error"   => $e->getMessage()
    ]);
}