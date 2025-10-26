<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";
require_once "../auth/verify_jwt.php";

$data = json_decode(file_get_contents("php://input"));
$pro_id = $data->pro_id ?? null;

if (!$pro_id) {
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => 'Missing pro_id'
    ]);
    exit;
}

try {
    $sql = "SELECT s.*, p.pro_name, u.unit_name
            FROM stock s
            LEFT JOIN products p ON p.pro_id = s.pro_id
            LEFT JOIN units u ON p.unit_id = u.unit_id
            WHERE s.pro_id = :pro_id
            ORDER BY s.stck_id ASC
            LIMIT 0,100";

    $query = $dbcon->prepare($sql);
    $query->bindParam(":pro_id", $pro_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        'status' => true,
        'message' => 'Ok',
        'respJSON' => $result
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}