<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../auth/verify_jwt.php";
require_once "../dbconfig.php";

$input = json_decode(file_get_contents("php://input"), true);
$str_id = $input['str_id'] ?? null;

if (!$str_id) {
    echo json_encode(["status" => false, "message" => "Missing store id"]);
    exit;
}

try {
    $sql = "SELECT str_id, str_name, str_detail, str_phone, created_at, updated_at
            FROM store
            WHERE str_id = :str_id AND deleted_at IS NULL
            LIMIT 1";
    $stmt = $dbcon->prepare($sql);
    $stmt->bindParam(':str_id', $str_id, PDO::PARAM_INT);
    $stmt->execute();
    $store = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($store) {
        echo json_encode(["status" => true, "respJSON" => $store]);
    } else {
        echo json_encode(["status" => false, "message" => "ไม่พบข้อมูลร้านค้า"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Database error: " . $e->getMessage()]);
}