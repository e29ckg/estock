<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['items']) || !is_array($data['items'])) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'ไม่มีข้อมูล items']);
        exit;
    }

    // สมมติว่ามี user_id จาก token หรือ session
    $user_id = $data['user_id'] ?? 1;

    // ✅ เริ่ม transaction
    $dbcon->beginTransaction();

    // Insert requisition header
    $sql = "INSERT INTO requisitions (user_id, req_date, status) 
            VALUES (:user_id, NOW(), 'pending')";
    $stmt = $dbcon->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $req_id = $dbcon->lastInsertId();

    // Insert requisition items
    $sqlItem = "INSERT INTO requisition_items (req_id, pro_id, qty, unit_name) 
                VALUES (:req_id, :pro_id, :qty, :unit_name)";
    $stmtItem = $dbcon->prepare($sqlItem);

    foreach ($data['items'] as $item) {
        if (empty($item['pro_id']) || empty($item['qty'])) {
            throw new Exception("ข้อมูล item ไม่ครบ");
        }
        $stmtItem->execute([
            ':req_id'    => $req_id,
            ':pro_id'    => $item['pro_id'],
            ':qty'       => $item['qty'],
            ':unit_name' => $item['unit_name'] ?? null
        ]);
    }

    // ✅ commit ถ้าสำเร็จ
    $dbcon->commit();

    http_response_code(201);
    echo json_encode([
        'status' => true,
        'message' => 'บันทึกการเบิกเรียบร้อยแล้ว',
        'req_id' => $req_id
    ]);

} catch (Exception $e) {
    $dbcon->rollBack();
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}