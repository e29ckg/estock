<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";
require_once "../auth/verify_jwt.php";

$data = json_decode(file_get_contents("php://input"));
$order_id = $data->order_id ?? null;

try {
    if (!$order_id) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'order_id is required'
        ]);
        exit;
    }

    // ✅ ดึงรายการสินค้าใน order พร้อมชื่อสินค้า
    $sql = "SELECT ol.order_list_id, ol.order_id, ol.pro_id, p.pro_name, p.unit_name, ol.qua, ol.qua_pay
            FROM order_lists ol
            INNER JOIN products p ON ol.pro_id = p.pro_id
            WHERE ol.order_id = :order_id";
    $query = $dbcon->prepare($sql);
    $query->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    $datas = [];
    if ($result) {
        // ✅ ดึง stock ล่าสุดของสินค้าทั้งหมดในครั้งเดียว
        $pro_ids = array_map(fn($r) => $r->pro_id, $result);
        $placeholders = implode(',', array_fill(0, count($pro_ids), '?'));

        $sql = "SELECT s.pro_id, s.bal 
                FROM stock s
                INNER JOIN (
                    SELECT pro_id, MAX(stck_id) AS last_id
                    FROM stock
                    WHERE pro_id IN ($placeholders)
                    GROUP BY pro_id
                ) t ON s.pro_id = t.pro_id AND s.stck_id = t.last_id";
        $query = $dbcon->prepare($sql);
        $query->execute($pro_ids);
        $stocks = $query->fetchAll(PDO::FETCH_KEY_PAIR); // pro_id => bal

        foreach ($result as $rs) {
            $instock = $stocks[$rs->pro_id] ?? 0;
            $datas[] = [
                'ord_list_id' => $rs->order_list_id,
                'order_id'      => $rs->order_id,
                'pro_id'      => $rs->pro_id,
                'pro_name'    => $rs->pro_name,
                'unit_name'   => $rs->unit_name,
                'instock'     => $instock,
                'qua'         => $rs->qua,
                'qua_pay'     => $rs->qua_pay,
            ];
        }
    }

    http_response_code(200);
    echo json_encode([
        'status'   => true,
        'message'  => 'Ok',
        'respJSON' => $datas
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}