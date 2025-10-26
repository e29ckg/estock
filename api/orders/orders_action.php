<?php
require_once "../dbconfig.php";
require_once "../auth/verify_jwt.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set("Asia/Bangkok");


$data = json_decode(file_get_contents("php://input"));
$Ord = $data->Ord ?? null;
$Ord_lists = $data->Ord_lists ?? [];

if (!$Ord) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Invalid request: missing Ord']);
    exit;
}

$order_own = $Ord->order_own ?? $userData['fullname'];

// ✅ ถ้าไม่ได้ส่ง ord_date มา → ใช้วันที่ปัจจุบัน
if (empty($Ord->order_date)) {
    $order_date = date("Y-m-d H:i:s");
} elseif (is_numeric($Ord->order_date)) {
    // ถ้าเป็น timestamp
    $order_date = date("Y-m-d H:i:s", (int)$Ord->order_date);
} else {
    // ถ้าเป็น string เช่น "2025-10-19"
    $order_date = $Ord->order_date;
}

try{
        
    if ($Ord->action === 'insert') {
        try {
            $dbcon->beginTransaction();

            // ✅ insert orders (หัวเอกสาร)
            $sql = "INSERT INTO orders(user_id, order_date, comment, created_at, updated_at) 
                    VALUES(:user_id, :order_date, :comment, NOW(), NOW())";
            $query = $dbcon->prepare($sql);
            $query->execute([
                ':user_id'  => $Ord->user_id,
                ':order_date' => $order_date,
                ':comment'    => $Ord->comment
            ]);

            $order_id = $dbcon->lastInsertId();

            // ✅ insert order_lists (รายละเอียดสินค้า)
            $sql = "INSERT INTO order_lists(order_id, order_date, pro_id, qua, qua_pay, st, created_at, updated_at) 
                    VALUES(:order_id, :order_date, :pro_id, :qua, :qua_pay, 0, NOW(), NOW())";
            $stmtOrderList = $dbcon->prepare($sql);

            // ✅ เตรียม statement สำหรับดึง stock
            $stmtStock = $dbcon->prepare("SELECT instock FROM products WHERE pro_id = :pro_id LIMIT 1");

            // ✅ เตรียม statement สำหรับอัปเดต stock
            $stmtUpdateStock = $dbcon->prepare("UPDATE products SET instock = :new_instock WHERE pro_id = :pro_id");

            foreach ($Ord_lists as $ols) {
                if (!empty($ols->pro_id)) {
                    // insert order_list
                    $stmtOrderList->execute([
                        ':order_id' => $order_id,
                        ':order_date' => $order_date,
                        ':pro_id'   => $ols->pro_id,
                        ':qua'      => $ols->qua,
                        ':qua_pay'  => 0
                    ]);

                    // ดึง stock ล่าสุด
                    $stmtStock->bindParam(':pro_id', $ols->pro_id, PDO::PARAM_INT);
                    $stmtStock->execute();
                    $product_instock = $stmtStock->fetch(PDO::FETCH_OBJ);

                    $current_instock = $product_instock ? (int)$product_instock->instock : 0;
                    $new_instock = $current_instock - (int)$ols->qua;

                    // อัปเดต stock
                    $stmtUpdateStock->execute([
                        ':new_instock' => $new_instock,
                        ':pro_id'      => $ols->pro_id
                    ]);
                }
            }

            $dbcon->commit();

            http_response_code(200);
            echo json_encode([
                'status'   => true,
                'message'  => 'เพิ่มคำสั่งซื้อเรียบร้อย',
                'order_id' => $order_id
            ]);

        } catch (Exception $e) {
            if ($dbcon->inTransaction()) {
                $dbcon->rollBack();
            }
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Insert failed: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    if ($Ord->action === 'update') {
        try {
            $dbcon->beginTransaction();

            // ✅ update orders
            $sql = "UPDATE orders 
                    SET order_date = :order_date, comment = :comment
                    WHERE order_id = :order_id";
            $query = $dbcon->prepare($sql);
            $query->execute([
                ':order_date' => $order_date,
                ':comment'    => $Ord->comment,
                ':order_id'   => $Ord->order_id
            ]);

            // ✅ คืน stock เดิมก่อนลบ order_lists
            $sql = "SELECT pro_id, qua FROM order_lists WHERE order_id = :order_id";
            $stmtOldLists = $dbcon->prepare($sql);
            $stmtOldLists->execute([':order_id' => $Ord->order_id]);
            $oldLists = $stmtOldLists->fetchAll(PDO::FETCH_OBJ);

            $stmtUpdateStock = $dbcon->prepare("UPDATE products SET instock = instock + :qua WHERE pro_id = :pro_id");

            foreach ($oldLists as $old) {
                $stmtUpdateStock->execute([
                    ':qua'    => $old->qua,
                    ':pro_id' => $old->pro_id
                ]);
            }

            // ✅ ลบ order_lists เดิม
            $sql = "DELETE FROM order_lists WHERE order_id = :order_id";
            $query = $dbcon->prepare($sql);
            $query->execute([':order_id' => $Ord->order_id]);

            // ✅ เตรียม statement insert ใหม่
            $stmtInsertList = $dbcon->prepare("
                INSERT INTO order_lists(order_id, order_date, pro_id, qua, qua_pay, st, created_at, updated_at) 
                VALUES(:order_id, :order_date, :pro_id, :qua, :qua_pay, 0, NOW(), NOW())
            ");

            $stmtUpdateStock = $dbcon->prepare("UPDATE products SET instock = instock - :qua WHERE pro_id = :pro_id");

            // ✅ loop รายการใหม่
            foreach ($data->Ord_lists as $ord_l) {
                if (!empty($ord_l->pro_id) && !empty($ord_l->qua) && $ord_l->qua > 0) {
                    // insert order_list
                    $stmtInsertList->execute([
                        ':order_id' => $Ord->order_id,
                        ':order_date' => $order_date,
                        ':pro_id'   => $ord_l->pro_id,
                        ':qua'      => $ord_l->qua,
                        ':qua_pay'  => $ord_l->qua
                    ]);

                    // หัก stock ตามจำนวนใหม่
                    $stmtUpdateStock->execute([
                        ':qua'    => $ord_l->qua,
                        ':pro_id' => $ord_l->pro_id
                    ]);
                }
            }

            $dbcon->commit();

            http_response_code(200);
            echo json_encode([
                'status'  => 'success',
                'message' => 'อัปเดตคำสั่งซื้อและคืน stock เรียบร้อย'
            ]);
            exit;

        } catch (Exception $e) {
            if ($dbcon->inTransaction()) {
                $dbcon->rollBack();
            }
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Update failed: ' . $e->getMessage()
            ]);
            exit;
        }
    }
    

    if ($Ord->action === 'delete') {
        try {
            $dbcon->beginTransaction();

            // ✅ คืน stock เดิมก่อนลบ order_lists
            $sql = "SELECT pro_id, qua FROM order_lists WHERE order_id = :order_id";
            $stmtOldLists = $dbcon->prepare($sql);
            $stmtOldLists->execute([':order_id' => $Ord->order_id]);
            $oldLists = $stmtOldLists->fetchAll(PDO::FETCH_OBJ);

            $stmtUpdateStock = $dbcon->prepare("UPDATE products SET instock = instock + :qua WHERE pro_id = :pro_id");

            foreach ($oldLists as $old) {
                $stmtUpdateStock->execute([
                    ':qua'    => $old->qua,
                    ':pro_id' => $old->pro_id
                ]);
            }

            // ✅ ลบ order (order_lists จะถูกลบอัตโนมัติด้วยเพราะมี FK CASCADE)
            $sql = "DELETE FROM orders WHERE order_id = :order_id";
            $query = $dbcon->prepare($sql);
            $query->bindParam(':order_id', $Ord->order_id, PDO::PARAM_INT);
            $query->execute();

            $dbcon->commit();

            http_response_code(200);
            echo json_encode([
                'status'  => true,
                'message' => 'Record deleted successfully',
                'order_id' => $Ord->order_id
            ]);
        } catch (Exception $e) {
            if ($dbcon->inTransaction()) {
                $dbcon->rollBack();
            }
            http_response_code(500);
            echo json_encode([
                'status'  => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ]);
        }
    }  

}catch(PDOException $e){
    if ($dbcon->inTransaction()) {
        $dbcon->rollback();
        // If we got here our two data updates are not in the database
    }

    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


function getLatestStock($dbcon, $pro_id) {
    $sql = "SELECT SUM(qua_for_ord) AS total_instock 
            FROM recs 
            WHERE pro_id = :pro_id AND qua_for_ord > 0";
    $query = $dbcon->prepare($sql);
    $query->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    return $result && $result->total_instock ? (int)$result->total_instock : 0;
}

