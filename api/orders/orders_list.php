<?php
header("Content-Type: application/json; charset=UTF-8");
include "../dbconfig.php";
include "../auth/verify_jwt.php"; // ✅ ตรวจสอบ JWT ก่อน

try {
    $stmt = $dbcon->prepare("SELECT * FROM orders ORDER BY created_at DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "orders" => $orders
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database error",
        "error" => $e->getMessage()
    ]);
}