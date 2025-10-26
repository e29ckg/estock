<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

try {
    // ✅ ดึงเฉพาะข้อมูลที่ยังไม่ถูกลบ
    $sql = "SELECT unit_id, unit_name 
          FROM units 
         WHERE deleted_at IS NULL 
         ORDER BY unit_name ASC";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        'status'   => true,
        'message'  => 'Ok',
        'respJSON' => $result
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}