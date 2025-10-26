<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../dbconfig.php");
require_once "../auth/verify_jwt.php";

// รับข้อมูลจาก Vue
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['user_id']) || !isset($data['items'])) {
    echo json_encode(["success" => false, "message" => "ข้อมูลไม่ครบ"]);
    exit;
}

try {
    $dbcon->beginTransaction();

    // 1. บันทึก orders
    $stmt = $dbcon->prepare("INSERT INTO orders (user_id) VALUES (?)");
    $stmt->execute([$data['user_id']]);
    $order_id = $dbcon->lastInsertId();

    // 2. บันทึก order_lists
    $stmt = $dbcon->prepare("INSERT INTO order_lists (order_id, pro_id, qua) VALUES (?, ?, ?)");
    foreach ($data['items'] as $item) {
        $stmt->execute([$order_id, $item['pro_id'], $item['qty']]);
    }

    $dbcon->commit();
    echo json_encode(["success" => true, "order_id" => $order_id]);

} catch (Exception $e) {
    $dbcon->rollBack();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}