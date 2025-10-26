<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";
//include "../auth/verify_jwt.php"; // ✅ ตรวจสอบ JWT ก่อน

try {
    $input  = json_decode(file_get_contents("php://input"), true);
    $status = $input['data'] ?? null;

    if ($status === 'st0') {
        $sql = "SELECT COUNT(*) as cnt FROM orders WHERE st = 0";
    } elseif ($status === 'st1') {
        $sql = "SELECT COUNT(*) as cnt FROM orders WHERE st = 1";
    } else {
        $sql = "SELECT COUNT(*) as cnt FROM ords";
    }

    $stmt = $dbcon->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        'status'   => true,
        'message'  => 'Ok',
        'respJSON' => (int)$row['cnt'],
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => false,
        'message' => 'Database error',
        'error'   => $e->getMessage()
    ]);
}