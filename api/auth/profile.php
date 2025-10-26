<?php
require '../dbconfig.php';        // ใช้ dbconfig.php ที่สร้าง $dbcon
require_once "../auth/verify_jwt.php"; // ตรวจสอบ JWT

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
   
    // ✅ ดึงข้อมูลผู้ใช้จาก payload
    $user_id  = $decoded->data->user_id;
    $fullname = $decoded->data->fullname;
    $email    = $decoded->data->email;
    $role     = $decoded->data->role;

    // (Option) ดึงข้อมูลล่าสุดจาก DB เพื่อความถูกต้อง
    $query = "SELECT user_id, fullname, email, username, role, created_at 
              FROM users 
              WHERE user_id = :user_id LIMIT 1";
    $stmt = $dbcon->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // ✅ ส่งข้อมูลกลับ
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "user"   => $user
    ]);

} catch (\Firebase\JWT\ExpiredException $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Token expired"]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid token", "error" => $e->getMessage()]);
}