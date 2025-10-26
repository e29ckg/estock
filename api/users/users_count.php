<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";
include "../auth/verify_jwt.php"; // ✅ ตรวจสอบ JWT ก่อน

try {
    // ✅ ใช้ COUNT(*) เพื่อประสิทธิภาพ
    $sql = "SELECT COUNT(*) as cnt FROM users WHERE st = 10";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        'status'   => true,
        'message'  => 'Ok',
        'respJSON' => (int)$row['cnt'],
        'user'     => $userData // ✅ ส่งข้อมูล user ที่ decode จาก JWT กลับไปด้วย (optional)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => false,
        'message' => 'Database error',
        'error'   => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => false,
        'message' => 'Unexpected error',
        'error'   => $e->getMessage()
    ]);
}