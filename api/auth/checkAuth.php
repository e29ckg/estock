<?php
// checkAuth.php
header("Content-Type: application/json; charset=UTF-8");

// ✅ โหลด verify_jwt.php เพื่อถอดรหัส token
require_once __DIR__ . "/verify_jwt.php";

/**
 * ฟังก์ชันตรวจสอบสิทธิ์
 * @param array|null $userData ข้อมูล user จาก JWT
 * @param array $roles รายชื่อ role ที่อนุญาต เช่น ['admin','manager']
 */
function checkAuth($userData, $roles = []) {
    if ($userData === null) {
        http_response_code(401);
        echo json_encode([
            "status" => false,
            "message" => "Unauthorized"
        ]);
        exit;
    }

    if (!empty($roles) && !in_array($userData['role'], $roles)) {
        http_response_code(403);
        echo json_encode([
            "status" => false,
            "message" => "Forbidden"
        ]);
        exit;
    }
}