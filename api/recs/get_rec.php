<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";
require_once "../auth/verify_jwt.php";

$data = json_decode(file_get_contents("php://input"));

try {
    if (!isset($data->rec_id)) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'rec_id is required'
        ]);
        exit;
    }

    // ✅ ใช้ prepared statement ปลอดภัยกว่า
    $sql = "SELECT 
                r.rec_id,
                r.rec_own,
                r.rec_app,
                r.rec_date,
                r.str_id,
                s.str_name,         -- ✅ ดึงชื่อร้าน
                r.price_total,
                r.comment,
                r.st,
                r.created_at,
                r.updated_at
            FROM recs r
            INNER JOIN store s ON r.str_id = s.str_id
            WHERE r.rec_id = :rec_id
              AND r.deleted_at IS NULL
            LIMIT 1";

    $query = $dbcon->prepare($sql);
    $query->bindParam(':rec_id', $data->rec_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
        http_response_code(200);
        echo json_encode([
            'status' => true,
            'message' => 'Ok',
            'respJSON' => $result
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => false,
            'message' => 'ไม่พบข้อมูล rec_id ที่ระบุ'
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}