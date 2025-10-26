<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../dbconfig.php";
require_once "../auth/verify_jwt.php";

$input = json_decode(file_get_contents("php://input"), true);
$pro_id = $input['pro_id'] ?? null;

if (!$pro_id) {
    echo json_encode(["status" => false, "message" => "Missing pro_id"]);
    exit;
}

try {
    $sql = "SELECT 
            p.pro_id,
            p.pro_name,
            p.pro_detail,
            p.cat_id,
            c.cat_name,
            p.unit_id,
            u.unit_name,
            p.instock,
            p.locat,
            p.lower,
            p.min,
            p.st,
            p.img,
            p.own,
            p.created_at,
            p.updated_at
        FROM products p
        INNER JOIN catalogs c ON p.cat_id = c.cat_id
        INNER JOIN units u ON p.unit_id = u.unit_id
        WHERE p.pro_id = :pro_id
        LIMIT 1";

    $stmt = $dbcon->prepare($sql);
    $stmt->bindParam(":pro_id", $pro_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode(["status" => true, "respJSON" => [$row]]);
    } else {
        echo json_encode(["status" => false, "message" => "Product not found"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database error",
        "error" => $e->getMessage()
    ]);
}