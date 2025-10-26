<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../dbconfig.php";
require_once "../auth/checkAuth.php";
checkAuth($userData,['admin']);

try {
    $sql = "SELECT str_id, str_name, str_detail, str_phone, created_at, updated_at
            FROM store
            WHERE deleted_at IS NULL
            ORDER BY str_id ASC";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "respJSON" => $result
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}