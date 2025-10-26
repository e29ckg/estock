<?php
require '../dbconfig.php';        // ใช้ dbconfig.php ที่สร้าง $dbcon
require_once "../auth/verify_jwt.php"; // ตรวจสอบ JWT

header("Content-Type: application/json; charset=UTF-8");

try {
    $user_id = $decoded->data->user_id;

    $data = json_decode(file_get_contents("php://input"), true);
    $fullname = $data['fullname'] ?? '';
    $email    = $data['email'] ?? '';
    $phone    = $data['phone'] ?? '';
    $dep      = $data['dep'] ?? '';
    $password = $data['password'] ?? '';

    $sql = "UPDATE users SET fullname=:fullname, email=:email, phone=:phone, dep=:dep";
    $params = [
        ":fullname" => $fullname,
        ":email"    => $email,
        ":phone"    => $phone,
        ":dep"      => $dep,
        ":uid"      => $user_id
    ];

    if (!empty($password)) {
        $sql .= ", password=:password";
        $params[":password"] = password_hash($password, PASSWORD_BCRYPT);
    }
    $sql .= " WHERE user_id=:uid";

    $stmt = $dbcon->prepare($sql);
    $stmt->execute($params);

    echo json_encode(["status" => "success", "message" => "Profile updated"]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid token"]);
}