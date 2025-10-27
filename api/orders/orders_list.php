<?php
header("Content-Type: application/json; charset=UTF-8");
include "../dbconfig.php";
include "../auth/verify_jwt.php"; // ✅ ตรวจสอบ JWT ก่อน

try {
    $sql = "SELECT od.*, u.fullname order_own
            FROM orders od
            INNER JOIN users u ON u.user_id = od.user_id
            ORDER BY od.order_date DESC;";

    $stmt = $dbcon->prepare($sql);
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