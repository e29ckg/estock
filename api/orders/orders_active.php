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

$order_own = $userData['fullname'] ?? 'system';

// ✅ ถ้าไม่ได้ส่ง ord_date มา → ใช้วันที่ปัจจุบัน
if (empty($Ord->order_date)) {
    $order_date = date("Y-m-d");
} elseif (is_numeric($Ord->order_date)) {
    // ถ้าเป็น timestamp
    $order_date = date("Y-m-d", (int)$Ord->order_date);
} else {
    // ถ้าเป็น string เช่น "2025-10-19"
    $order_date = $Ord->order_date ;
}

try{
        
    if ($Ord->action === 'active') {
         if (isOrderAlreadyActive($dbcon, $Ord->order_id)) {
            http_response_code(200);
            echo json_encode([
                'status' => false,
                'message' => 'ใบเบิกนี้ถูกอนุมัติไปแล้ว ไม่สามารถเบิกซ้ำได้'
            ]);
            exit;
        }

       try {
            $dbcon->beginTransaction();
            $ord_pay_date = date("Y-m-d H:i:s");
            // update order header
            $sql = "UPDATE orders SET st=1, order_app=:order_app, order_pay_date=:ord_pay_date, order_pay_own=:ord_pay_own WHERE order_id = :order_id";
            $stmt = $dbcon->prepare($sql);
            $stmt->execute([
                ':order_app' => $order_own,
                ':ord_pay_date' => $ord_pay_date,
                ':ord_pay_own' => $order_own,
                ':order_id' => $Ord->order_id
            ]);

            foreach ($data->Ord_lists as $ord_l) {
                $qua = (int)$ord_l->qua;
                if ($qua <= 0) continue;

                $pro_id = $ord_l->pro_id;
                $to_do_date = $order_date;
                $unit_name = $ord_l->unit_name;
                $rec_order_list_id = $ord_l->order_list_id;
                $comment = $Ord->comment;
                $qua_pay = 0;

                $recLists = getRecLists($dbcon, $pro_id);
                $product_instock = array_sum(array_map(fn($r) => $r->qua_for_ord, $recLists));

                if ($product_instock < $qua) continue; // ไม่พอเบิก

                foreach ($recLists as $rrl) {
                    if ($qua <= 0) break;

                    $bf = getLastStock($dbcon, $pro_id);
                    $stck_out = min($rrl->qua_for_ord, $qua);
                    $qua -= $stck_out;

                    $bal = $bf - $stck_out;
                    $qua_for_ord = $rrl->qua_for_ord - $stck_out;
                    $qua_pay += $stck_out;

                    insertStockMovement($dbcon, [
                        ':pro_id' => $pro_id,
                        ':to_do_date' => $to_do_date,
                        ':unit_name' => $unit_name,
                        ':price_one' => $rrl->price_one,
                        ':bf' => $bf,
                        ':stck_in' => 0,
                        ':stck_out' => $stck_out,
                        ':bal' => $bal,
                        ':ref_type' => "order",
                        ':ref_id' => $ord_l->order_id,
                        ':comment' => $comment
                    ]);

                    updateRecList($dbcon, $rrl->rec_list_id, $qua_for_ord);
                    updateProductInstock($dbcon, $pro_id, $bal);
                }

                updateOrderList($dbcon, $rec_order_list_id, $order_own, $qua_pay);
            }

            $dbcon->commit();
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'บันทึกข้อมูลเรียบร้อย ok']);

        } catch (Exception $e) {
            if ($dbcon->inTransaction()) $dbcon->rollBack();
            http_response_code(500);
            echo json_encode(['status' => false, 'message' => 'Active failed: ' . $e->getMessage()]);
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

function getRecLists($dbcon, $pro_id) {
    $sql = "SELECT * FROM rec_lists WHERE pro_id = :pro_id AND qua_for_ord > 0 ORDER BY rec_list_id ASC";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':pro_id' => $pro_id]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getLastStock($dbcon, $pro_id) {
    $sql = "SELECT bal FROM stock WHERE pro_id = :pro_id ORDER BY stck_id DESC LIMIT 1";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':pro_id' => $pro_id]);
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    return $row ? (int)$row->bal : 0;
}

function insertStockMovement($dbcon, $data) {
    $sql = "INSERT INTO stock(pro_id, to_do_date, unit_name, price_one, bf, stck_in, stck_out, bal, ref_type, ref_id, comment)
            VALUES(:pro_id, :to_do_date, :unit_name, :price_one, :bf, :stck_in, :stck_out, :bal, :ref_type, :ref_id, :comment)";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute($data);
}

function updateRecList($dbcon, $rec_list_id, $qua_for_ord) {
    $sql = "UPDATE rec_lists SET qua_for_ord = :qua_for_ord WHERE rec_list_id = :rec_list_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':qua_for_ord' => $qua_for_ord, ':rec_list_id' => $rec_list_id]);
}

function updateProductInstock($dbcon, $pro_id, $instock) {
    $sql = "UPDATE products SET instock = :instock WHERE pro_id = :pro_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':instock' => $instock, ':pro_id' => $pro_id]);
}

function updateOrderList($dbcon, $order_list_id, $order_app, $qua_pay) {
    $sql = "UPDATE order_lists SET st=1, qua_pay=:qua_pay WHERE order_list_id = :order_list_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':qua_pay' => $qua_pay, ':order_list_id' => $order_list_id]);
}

function isOrderAlreadyActive($dbcon, $order_id) {
    $sql = "SELECT st FROM orders WHERE order_id = :order_id LIMIT 1";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':order_id' => $order_id]);
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    return $row && $row->st == 1;
}
