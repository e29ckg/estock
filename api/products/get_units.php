<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../dbconfig.php";
require_once "../auth/verify_jwt.php";

try {
    $stmt = $dbcon->prepare("SELECT unit_id, unit_name FROM units ORDER BY unit_name ASC");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "respJSON" => $rows
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database error",
        "error" => $e->getMessage()
    ]);
}