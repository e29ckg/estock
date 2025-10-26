<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../dbconfig.php";
require_once "../auth/verify_jwt.php";

$pro_id   = $_POST['pro_id'] ?? null;
$pro_name = $_POST['pro_name'] ?? null;
$pro_detail = $_POST['pro_detail'] ?? null;
$cat_id   = $_POST['cat_id'] ?? null;
$unit_id  = $_POST['unit_id'] ?? null;
$locat    = $_POST['locat'] ?? null;
$lower    = $_POST['lower'] ?? 0;
$min      = $_POST['min'] ?? 0;
$st       = $_POST['st'] ?? 1;
$action   = $_POST['action'] ?? 'insert';

// จัดการไฟล์รูป
$imgName = null;
if (!empty($_FILES['img']['name'])) {
    $targetDir = "../../uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    // ✅ ตรวจสอบขนาดไฟล์ไม่เกิน 2 MB
    if ($_FILES['img']['size'] > 2 * 1024 * 1024) {
        echo json_encode([
            "status" => false,
            "message" => "ไฟล์มีขนาดเกิน 2 MB"
        ]);
        exit;
    }

    $imgName = time() . "_" . basename($_FILES["img"]["name"]);
    $targetFile = $targetDir . $imgName;

    if (!move_uploaded_file($_FILES["img"]["tmp_name"], $targetFile)) {
        echo json_encode([
            "status" => false,
            "message" => "Upload failed"
        ]);
        exit;
    }
}

try {
    if ($action === 'insert') {
        $sql = "INSERT INTO products 
                (pro_name, pro_detail, cat_id, unit_id, locat, `lower`, `min`, st, img, created_at, updated_at)
                VALUES 
                (:pro_name, :pro_detail, :cat_id, :unit_id, :locat, :lower, :min, :st, :img, NOW(), NOW())";
        $stmt = $dbcon->prepare($sql);
        $stmt->execute([
            ":pro_name" => $pro_name,
            ":pro_detail" => $pro_detail,
            ":cat_id" => $cat_id,
            ":unit_id" => $unit_id,
            ":locat" => $locat,
            ":lower" => $lower,
            ":min" => $min,
            ":st" => $st,
            ":img" => $imgName
        ]);
        echo json_encode(["status" => true, "message" => "เพิ่มสินค้าเรียบร้อยแล้ว"]);

    } elseif ($action === 'update') {
        $sql = "UPDATE products SET 
                    pro_name=:pro_name,
                    pro_detail=:pro_detail,
                    cat_id=:cat_id,
                    unit_id=:unit_id,
                    locat=:locat,
                    `lower`=:lower,
                    `min`=:min,
                    st=:st,
                    updated_at=NOW()";
        if ($imgName) $sql .= ", img=:img";
        $sql .= " WHERE pro_id=:pro_id";

        $stmt = $dbcon->prepare($sql);
        $params = [
            ":pro_id" => $pro_id,
            ":pro_name" => $pro_name,
            ":pro_detail" => $pro_detail,
            ":cat_id" => $cat_id,
            ":unit_id" => $unit_id,
            ":locat" => $locat,
            ":lower" => $lower,
            ":min" => $min,
            ":st" => $st
        ];
        if ($imgName) $params[":img"] = $imgName;
        $stmt->execute($params);

        echo json_encode(["status" => true, "message" => "แก้ไขสินค้าเรียบร้อยแล้ว"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}