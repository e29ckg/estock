<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

$data = json_decode(file_get_contents("php://input"));

try {
    if (empty($data->unit_id)) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'unit_id is required'
        ]);
        exit;
    }

    // ✅ ใช้ prepare + bindParam ป้องกัน SQL Injection
    $sql = "SELECT * 
              FROM units 
             WHERE unit_id = :unit_id 
               AND deleted_at IS NULL
             LIMIT 1";
    $query = $dbcon->prepare($sql);
    $query->bindParam(':unit_id', $data->unit_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
        http_response_code(200);
        echo json_encode([
            'status'   => true,
            'message'  => 'Ok',
            'respJSON' => $result
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'status'  => false,
            'message' => 'ไม่พบข้อมูล unit_id ที่ระบุ'
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}