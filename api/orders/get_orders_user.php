<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";
require_once "../auth/verify_jwt.php";

// รับ user_id
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if ($user_id <= 0) {
    echo json_encode(["success" => false, "message" => "user_id ไม่ถูกต้อง"]);
    exit;
}

try {
    // ดึง orders ของ user
    $stmt = $dbcon->prepare("
        SELECT o.order_id, o.user_id, u.fullname, o.order_date, o.comment, o.st
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        WHERE o.user_id = ?
        ORDER BY o.order_date DESC
    ");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($orders as $order) {
        // ดึงรายการสินค้าในแต่ละ order
        $stmt2 = $dbcon->prepare("
            SELECT ol.order_list_id, ol.pro_id, ol.qua, ol.qua_pay, p.pro_name, p.unit_id
            FROM order_lists ol
            JOIN products p ON ol.pro_id = p.pro_id
            WHERE ol.order_id = ?
        ");
        $stmt2->execute([$order['order_id']]);
        $items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $result[] = [
            "order_id"   => $order['order_id'],
            "order_date" => $order['order_date'],
            "fullname"   => $order['fullname'],   // ✅ ส่งชื่อผู้ใช้กลับไปด้วย
            "comment"    => $order['comment'],
            "st"         => $order['st'],
            "items"      => $items
        ];
    }

    echo json_encode(["success" => true, "orders" => $result]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}