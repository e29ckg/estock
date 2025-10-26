<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";
//require_once "../auth/verify_jwt.php";

// ✅ รองรับทั้ง GET และ POST
$data = json_decode(file_get_contents("php://input"), true);
$rec_id = $data['rec_id'] ?? ($_GET['rec_id'] ?? null);

try {
    if (!$rec_id) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'rec_id is required'
        ]);
        exit;
    }

    // ✅ ดึงข้อมูล rec + store
    $sql = "SELECT r.*, s.str_name, s.str_detail, s.str_phone
            FROM recs r
            INNER JOIN store s ON r.str_id = s.str_id
            WHERE r.rec_id = :rec_id
            LIMIT 1";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':rec_id' => $rec_id]);
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rec) {
        http_response_code(404);
        echo json_encode([
            'status' => false,
            'message' => 'ไม่พบข้อมูล rec_id ที่ระบุ'
        ]);
        exit;
    }

    // ✅ ดึงรายการ rec_lists + products + units
    $sql = "SELECT rl.rec_list_id, rl.rec_id, rl.pro_id, 
                   p.pro_name, u.unit_name,
                   rl.qua, rl.price_one,
                   (rl.qua * rl.price_one) AS price
            FROM rec_lists rl
            LEFT JOIN products p ON rl.pro_id = p.pro_id
            LEFT JOIN units u ON p.unit_id = u.unit_id
            WHERE rl.rec_id = :rec_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':rec_id' => $rec_id]);
    $rec_lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ รวม summary
    $price_all = 0;
    $sum_qua = 0;
    foreach ($rec_lists as $row) {
        $price_all += $row['price'];
        $sum_qua += $row['qua'];
    }
    $count_items = count($rec_lists);

    http_response_code(200);
    echo json_encode([
        'status'     => true,
        'message'    => 'Ok',
        'rec'        => $rec,
        'rec_lists'  => $rec_lists,
        'summary'    => [
            'count_items' => $count_items,
            'sum_qua'     => $sum_qua,
            'price_all'   => $price_all
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}