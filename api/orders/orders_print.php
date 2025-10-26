<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

// ✅ รองรับทั้ง GET และ POST
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $order_id = $data['order_id'] ?? null;
}

if (!$order_id) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing order_id'
    ]);
    exit;
}

try {
    // ✅ ดึงข้อมูล order header + ข้อมูลผู้ใช้
    $sql = "SELECT o.*,u.fullname, u.dep, u.phone, u.email 
            FROM orders o
            INNER JOIN users u ON o.user_id = u.user_id
            WHERE o.order_id = :order_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':order_id' => $order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Order not found'
        ]);
        exit;
    }

    // ✅ ดึงรายการสินค้า พร้อมหน่วยนับจาก units
    $sql = "SELECT 
                l.order_list_id, 
                l.order_id, 
                l.pro_id, 
                p.pro_name, 
                u.unit_name,
                l.qua, 
                l.qua_pay,
                COALESCE(p.img, 'none.png') AS img
            FROM order_lists l
            LEFT JOIN products p ON l.pro_id = p.pro_id
            LEFT JOIN units u ON p.unit_id = u.unit_id
            WHERE l.order_id = :order_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':order_id' => $order_id]);
    $order_lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ คำนวณ summary
    $count_items = count($order_lists);
    $sum_qua = 0;
    $sum_qua_pay = 0;
    $sum_price = 0;
    foreach ($order_lists as $item) {
        $sum_qua += (int)$item['qua'];
        $sum_qua_pay += (int)$item['qua_pay'];
        
    }

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Ok',
        'order' => $order,
        'order_lists' => $order_lists,
        'summary' => [
            'count_items' => $count_items,
            'sum_qua' => $sum_qua,
            'sum_qua_pay' => $sum_qua_pay,
            'sum_price' => $sum_price
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}