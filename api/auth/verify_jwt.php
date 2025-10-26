<?php
require __DIR__ . "/../dbconfig.php";   // ✅ โหลด $dbcon และ $key
require __DIR__ . "/vendor/autoload.php";

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header("Content-Type: application/json; charset=UTF-8");

// ✅ ดึง Authorization header
$headers = null;
if (function_exists('apache_request_headers')) {
    $headers = apache_request_headers();
} else {
    // fallback สำหรับ Nginx/FPM
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
}

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["status" => false, "message" => "Authorization header missing"]);
    exit;
}

list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');

if (!$jwt) {
    http_response_code(401);
    echo json_encode(["status" => false, "message" => "Token not provided"]);
    exit;
}

try {
    // ✅ ใช้ $key จาก dbconfig.php
    $decoded = JWT::decode($jwt, $key, ['HS256']);

    // เก็บข้อมูล user ไว้ใช้ต่อใน API
    $userData = (array)$decoded->data;

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        "status"  => false,
        "message" => "Invalid or expired token",
        "error"   => $e->getMessage()
    ]);
    exit;
}