<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../dbconfig.php";
require_once "../auth/verify_jwt.php"; // ตรวจสอบ JWT

try {
    $sql = "SELECT 
                p.pro_id,
                p.pro_name,
                p.pro_detail,
                c.cat_name,
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
            WHERE p.deleted_at IS NULL
            ORDER BY p.pro_name ASC";

    $stmt = $dbcon->prepare($sql);
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