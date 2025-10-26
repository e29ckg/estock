<?php
require_once "../dbconfig.php";
require_once "../auth/verify_jwt.php"; // ✅ ตรวจสอบ JWT และได้ $userData

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set("Asia/Bangkok");

$data = json_decode(file_get_contents("php://input"));
$Recs = $data->Recs ?? null;
$Rec_lists = $data->Rec_lists ?? [];

// ✅ ดึงข้อมูลผู้ใช้จาก verify_jwt.php
$rec_own = $userData['fullname'] ?? 'system';

// ✅ ถ้าไม่ได้ส่ง ord_date มา → ใช้วันที่ปัจจุบัน
if (empty($Recs->rec_date)) {
    $rec_date = date("Y-m-d H:i:s");
} elseif (is_numeric($Recs->rec_date)) {
    // ถ้าเป็น timestamp
    $rec_date = date("Y-m-d H:i:s", (int)$Recs->rec_date);
} else {
    // ถ้าเป็น string เช่น "2025-10-19"
    $rec_date = $Recs->rec_date . " 00:00:00";
}

try {
    // ---------------- INSERT ----------------
    if ($Recs->action === 'insert') {
        $dbcon->beginTransaction();

        // ✅ ไม่ต้องกำหนด rec_id เอง ให้ AUTO_INCREMENT ทำงาน
        $sql = "INSERT INTO recs(rec_date, str_id, price_total, rec_own, comment, created_at, updated_at) 
                VALUES(:rec_date, :str_id, :price_total, :rec_own, :comment, NOW(), NOW())";
        $query = $dbcon->prepare($sql);
        $query->execute([
            ':rec_date' => $rec_date,
            ':str_id' => $Recs->str_id,
            ':price_total' => $Recs->price_total,
            ':rec_own' => $rec_own,
            ':comment' => $Recs->comment
        ]);

        // ✅ ดึง rec_id ที่เพิ่ง insert
        $rec_id = $dbcon->lastInsertId();

        // ✅ Insert รายการสินค้า
        $sql = "INSERT INTO rec_lists(rec_id, rec_date, pro_id, pro_name, unit_name, qua, qua_for_ord, price_one, price, rec_own, created_at, updated_at) 
                VALUES(:rec_id, :rec_date, :pro_id, :pro_name, :unit_name, :qua, :qua_for_ord, :price_one, :price, :rec_own, NOW(), NOW())";
        $query = $dbcon->prepare($sql);

        foreach ($Rec_lists as $rls) {
            if (!empty($rls->pro_id)) {
                $query->execute([
                    ':rec_id' => $rec_id,
                    ':rec_date' => $rec_date,
                    ':pro_id' => $rls->pro_id,
                    ':pro_name' => $rls->pro_name,
                    ':unit_name' => $rls->unit_name,
                    ':qua' => $rls->qua,
                    ':qua_for_ord' => $rls->qua,
                    ':price_one' => $rls->price_one,
                    ':price' => $rls->price,
                    ':rec_own' => $rec_own
                ]);
            }
        }

        $dbcon->commit();
        echo json_encode([
            'status' => 'success',
            'message' => 'เพิ่มข้อมูลเรียบร้อย',
            'rec_id' => $rec_id
        ]);
        exit;
    }

    // ---------------- UPDATE ----------------
    if ($Recs->action === 'update') {
        $dbcon->beginTransaction();

        // ✅ อัปเดตหัวเอกสาร
        $sql = "UPDATE recs 
                SET rec_date=:rec_date, str_id=:str_id, price_total=:price_total, comment=:comment, updated_at=NOW()
                WHERE rec_id=:rec_id AND deleted_at IS NULL";
        $query = $dbcon->prepare($sql);
        $query->execute([
            ':rec_date' => $Recs->rec_date,
            ':str_id' => $Recs->str_id,
            ':price_total' => $Recs->price_total,
            ':comment' => $Recs->comment,
            ':rec_id' => $Recs->rec_id
        ]);

        // ✅ ลบ rec_lists เก่าออกก่อน (soft delete หรือ hard delete ตามต้องการ)
        $sql = "DELETE FROM rec_lists WHERE rec_id=:rec_id";
        $query = $dbcon->prepare($sql);
        $query->execute([':rec_id' => $Recs->rec_id]);

        // ✅ เพิ่ม rec_lists ใหม่
        $sql = "INSERT INTO rec_lists(rec_id, rec_date, pro_id, pro_name, unit_name, qua, qua_for_ord, price_one, price, rec_own, created_at, updated_at) 
                VALUES(:rec_id, :rec_date, :pro_id, :pro_name, :unit_name, :qua, :qua_for_ord, :price_one, :price, :rec_own, NOW(), NOW())";
        $query = $dbcon->prepare($sql);

        foreach ($Rec_lists as $rls) {
            if (!empty($rls->pro_id)) {
                $query->execute([
                    ':rec_id' => $Recs->rec_id,
                    ':rec_date' => $Recs->rec_date,
                    ':pro_id' => $rls->pro_id,
                    ':pro_name' => $rls->pro_name,
                    ':unit_name' => $rls->unit_name,
                    ':qua' => $rls->qua,
                    ':qua_for_ord' => $rls->qua,
                    ':price_one' => $rls->price_one,
                    ':price' => $rls->price,
                    ':rec_own' => $rec_own
                ]);
            }
        }

        $dbcon->commit();
        echo json_encode(['status' => 'success', 'message' => 'แก้ไขข้อมูลเรียบร้อย', 'rec_id' => $Recs->rec_id]);
        exit;
    }

    // ---------------- DELETE (Hard Delete) ----------------
    if ($Recs->action === 'delete') {
        $dbcon->beginTransaction();

        // ✅ ลบ rec_lists ก่อน (เพราะมี FK ไปที่ recs)
        $sql = "DELETE FROM rec_lists WHERE rec_id = :rec_id";
        $query = $dbcon->prepare($sql);
        $query->execute([':rec_id' => $Recs->rec_id]);

        // ✅ ลบ recs
        $sql = "DELETE FROM recs WHERE rec_id = :rec_id";
        $query = $dbcon->prepare($sql);
        $query->execute([':rec_id' => $Recs->rec_id]);

        $dbcon->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'ลบข้อมูลเรียบร้อย (hard delete)',
            'rec_id' => $Recs->rec_id
        ]);
        exit;
    }

    if ($Recs->action == 'active') {
        $dbcon->beginTransaction();

        // ✅ อัปเดตหัวเอกสาร
        $sql = "UPDATE recs SET st=1, rec_app=:rec_app WHERE rec_id = :rec_id";
        $query = $dbcon->prepare($sql);
        $query->execute([
            ':rec_app' => $rec_own,
            ':rec_id'  => $Recs->rec_id
        ]);

        $Rec_lists = $data->Rec_lists;

        foreach ($Rec_lists as $rls) {
            $pro_id = (int)$rls->pro_id;

            // ✅ อัปเดตรายการ (ครั้งเดียวพอ ไม่ต้องวนซ้ำ)
            $sql = "UPDATE rec_lists SET st=1, rec_app=:rec_app WHERE rec_id = :rec_id";
            $query = $dbcon->prepare($sql);
            $query->execute([
                ':rec_app' => $rec_own,
                ':rec_id'  => $Recs->rec_id
            ]);

            // ✅ ดึง stock ล่าสุด
            $sql = "SELECT bal FROM stock WHERE pro_id = :pro_id ORDER BY stck_id DESC LIMIT 1";
            $query = $dbcon->prepare($sql);
            $query->execute([':pro_id' => $pro_id]);
            $lastStock = $query->fetch(PDO::FETCH_OBJ);

            if ($lastStock) {
                $bf = $lastStock->bal;
                $stck_in = $rls->qua;
                $stck_out = 0;
                $bal = $lastStock->bal + $rls->qua;
            } else {
                $bf = 0;
                $stck_in = $rls->qua;
                $stck_out = 0;
                $bal = $rls->qua;
            }

            // ✅ บันทึก stock ใหม่
            $sql = "INSERT INTO stock(pro_id, to_do_date, unit_name, price_one, bf, stck_in, stck_out, bal, ref_type, ref_id, comment) 
                    VALUES(:pro_id, :to_do_date, :unit_name, :price_one, :bf, :stck_in, :stck_out, :bal, :ref_type, :ref_id, :comment)";
            $query = $dbcon->prepare($sql);
            $query->execute([
                ':pro_id'        => $pro_id,
                ':to_do_date'    => $Recs->rec_date,
                ':unit_name'     => $rls->unit_name,
                ':price_one'     => $rls->price_one,
                ':bf'            => $bf,
                ':stck_in'       => $stck_in,
                ':stck_out'      => $stck_out,
                ':bal'           => $bal,
                ':ref_type'      => "rec",
                ':ref_id'       => $Recs->rec_id,
                ':comment'       => $Recs->comment
            ]);

            // ✅ อัปเดต instock ของสินค้า
            $sql = "UPDATE products SET instock = :instock WHERE pro_id = :pro_id";
            $query = $dbcon->prepare($sql);
            $query->execute([
                ':instock' => $bal,
                ':pro_id'  => $pro_id
            ]);
        }

        $dbcon->commit();
        http_response_code(200);
        echo json_encode([
            'status'  => 'success',
            'message' => 'บันทึกข้อมูลเรียบร้อย ok'
        ]);
        exit;
}

    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);

} catch (Exception $e) {
    if ($dbcon->inTransaction()) {
        $dbcon->rollBack();
    }
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}