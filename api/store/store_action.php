<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../dbconfig.php";
require_once "../auth/checkAuth.php";
checkAuth($userData,['admin']);
date_default_timezone_set("Asia/Bangkok");

$input = json_decode(file_get_contents("php://input"), true);
$store = $input['store'] ?? null;

if (!$store) {
    echo json_encode(["status" => false, "message" => "Missing store data"]);
    exit;
}

try {
    // ✅ INSERT
    if ($store['action'] === 'insert') {
        $sql = "INSERT INTO store(str_name, str_detail, str_phone, created_at, updated_at)
                VALUES(:str_name, :str_detail, :str_phone, NOW(), NOW())";
        $stmt = $dbcon->prepare($sql);
        $stmt->execute([
            ":str_name"  => $store['str_name'],
            ":str_detail"=> $store['str_detail'],
            ":str_phone" => $store['str_phone']
        ]);

        echo json_encode(["status" => true, "message" => "เพิ่มร้านค้าเรียบร้อยแล้ว"]);
        exit;
    }

    // ✅ UPDATE
    if ($store['action'] === 'update') {
        $sql = "UPDATE store SET 
                    str_name   = :str_name,
                    str_detail = :str_detail,
                    str_phone  = :str_phone,
                    updated_at = NOW()
                WHERE str_id = :str_id AND deleted_at IS NULL";
        $stmt = $dbcon->prepare($sql);
        $stmt->execute([
            ":str_name"  => $store['str_name'],
            ":str_detail"=> $store['str_detail'],
            ":str_phone" => $store['str_phone'],
            ":str_id"    => $store['str_id']
        ]);

        echo json_encode(["status" => true, "message" => "แก้ไขร้านค้าเรียบร้อยแล้ว"]);
        exit;
    }

    // ✅ SOFT DELETE
    if ($store['action'] === 'delete') {
        $sql = "UPDATE store 
                SET deleted_at = NOW(), updated_at = NOW() 
                WHERE str_id = :str_id";
        $stmt = $dbcon->prepare($sql);
        $stmt->bindParam(':str_id', $store['str_id'], PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["status" => true, "message" => "ลบร้านค้า (soft delete) เรียบร้อยแล้ว"]);
        exit;
    }

    // ถ้า action ไม่ถูกต้อง
    echo json_encode(["status" => false, "message" => "Invalid action"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}