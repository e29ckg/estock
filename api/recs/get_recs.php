<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../dbconfig.php";
require_once "../auth/verify_jwt.php";

try {
    $sql = "SELECT 
                r.rec_id,
                r.rec_own,
                r.rec_app,
                r.rec_date,
                r.str_id,
                s.str_name,              -- ✅ เพิ่มชื่อร้าน
                r.price_total,
                r.comment,
                r.st,
                r.created_at,
                r.updated_at
            FROM recs r
            INNER JOIN store s ON r.str_id = s.str_id   -- ✅ join กับตาราง store
            WHERE r.deleted_at IS NULL                  -- ✅ ถ้ามี soft delete
            ORDER BY r.rec_id DESC;";
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