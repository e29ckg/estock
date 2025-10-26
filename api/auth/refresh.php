<?php
require '../dbconfig.php';
require './vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$secret_key = getenv('JWT_SECRET');
$tz = getenv('APP_TIMEZONE') ?: 'Asia/Bangkok';
date_default_timezone_set($tz);

$data = json_decode(file_get_contents("php://input"));
$refresh_token = $data->refresh_token ?? '';

try {
    if (!$refresh_token) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Missing refresh token"]);
        exit;
    }

    // ✅ ตรวจสอบ refresh token
    $decoded = JWT::decode($refresh_token, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->user_id;

    // ✅ ตรวจสอบ refresh token กับ DB
    $stmt = $dbcon->prepare("SELECT user_id, fullname, email, role, refresh_token 
                             FROM users 
                             WHERE user_id = :uid AND refresh_token = :rtoken AND st = 10 LIMIT 1");
    $stmt->execute([":uid" => $user_id, ":rtoken" => $refresh_token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Invalid refresh token"]);
        exit;
    }

    // ✅ ออก access token ใหม่
    $issuedat_claim = time();
    $expire_claim   = $issuedat_claim + (8 * 3600); // 8 ชั่วโมง

    $token_payload = [
        "iss"  => "localhost",
        "aud"  => "E29CKG",
        "iat"  => $issuedat_claim,
        "exp"  => $expire_claim,
        "data" => [
            "user_id"  => $user['user_id'],
            "fullname" => $user['fullname'],
            "role"     => $user['role'],
            "email"    => $user['email']
        ]
    ];

    $new_jwt = JWT::encode($token_payload, $secret_key, 'HS256');

    http_response_code(200);
    echo json_encode([
        "status"   => "success",
        "jwt"      => $new_jwt,
        "expireAt" => $expire_claim
    ]);

} catch (\Firebase\JWT\ExpiredException $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Refresh token expired"]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid refresh token", "error" => $e->getMessage()]);
}