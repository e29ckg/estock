<?php
include_once "../dbconfig.php";
require_once "../auth/checkAuth.php";
checkAuth($userData, ['admin']);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set("Asia/Bangkok");

$data = json_decode(file_get_contents("php://input"));
if (!$data || empty($data->unit)) {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "Invalid request"]);
    exit;
}

$unit = $data->unit;

try {
    if ($unit->action === 'insert') {
        // ตรวจสอบชื่อซ้ำ (เฉพาะที่ยังไม่ถูกลบ)
        $sql = "SELECT unit_id FROM units WHERE unit_name = :unit_name AND deleted_at IS NULL";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':unit_name', $unit->unit_name, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            http_response_code(409);
            echo json_encode(['status' => false, 'message' => 'ชื่อหน่วยนี้มีในระบบแล้ว']);
            exit;
        }

        // Insert
        $sql = "INSERT INTO units(unit_name, created_at, updated_at) 
                VALUES(:unit_name, NOW(), NOW())";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':unit_name', $unit->unit_name, PDO::PARAM_STR);
        $query->execute();

        http_response_code(201);
        echo json_encode([
            'status' => true,
            'message' => 'เพิ่มข้อมูลเรียบร้อยแล้ว',
            'data' => ['unit_id' => $dbcon->lastInsertId(), 'unit_name' => $unit->unit_name]
        ]);
        exit;
    }

    if ($unit->action === 'update') {
        $sql = "UPDATE units 
                   SET unit_name = :unit_name, updated_at = NOW() 
                 WHERE unit_id = :unit_id AND deleted_at IS NULL";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':unit_name', $unit->unit_name, PDO::PARAM_STR);
        $query->bindParam(':unit_id', $unit->unit_id, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'ไม่พบข้อมูลหรือไม่มีการเปลี่ยนแปลง']);
        }
        exit;
    }

    if ($unit->action === 'delete') {
        // Soft delete
        $sql = "UPDATE units 
                   SET deleted_at = NOW(), updated_at = NOW() 
                 WHERE unit_id = :unit_id AND deleted_at IS NULL";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':unit_id', $unit->unit_id, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'ลบข้อมูล (soft delete) เรียบร้อยแล้ว']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'ไม่พบข้อมูลที่จะลบ']);
        }
        exit;
    }

    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Invalid action']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}