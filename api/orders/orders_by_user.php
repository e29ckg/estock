<?php
include_once "../dbconfig.php";
require "../auth/vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set("Asia/Bangkok");

// $key = "__test_secret__";
$jwt = null;
// $databaseService = new DatabaseService();
// $conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));
$Ord_lists = $data->carts;
$action = $data->action;

$ord_own = '';

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
// 
$arr = explode(" ", $authHeader);

// http_response_code(200);
// echo json_encode(array('status' => 'success', 'message' => 'เพิ่มข้อมูลเรียบร้อย', 'responseJSON' => $data));
// die; 
$ord_date = date("Y-m-d h:s:i");
try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data_auth = $decoded->data;
    
    $ord_own = $data_auth->fullname;    
    
    if($action == 'insert'){   
        $dbcon->beginTransaction();
        $ord_id = time();
        
        $sql = "INSERT INTO ords(ord_id, ord_date, ord_own) VALUE(:ord_id, :ord_date, :ord_own);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':ord_id', $ord_id,PDO::PARAM_INT);
        $query->bindParam(':ord_date', $ord_date);
        $query->bindParam(':ord_own',$ord_own, PDO::PARAM_STR);
        $query->execute();  
        
        foreach($Ord_lists as $ord_l){
            if($ord_l->pro_id != '' && $ord_l->qua != 0  && $ord_l->qua != ''){
                $sql = "INSERT INTO ord_lists(ord_id, pro_id, pro_name, unit_name, qua, ord_own) VALUE(:ord_id, :pro_id, :pro_name, :unit_name, :qua, :ord_own);";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':ord_id', $ord_id, PDO::PARAM_INT);
                $query->bindParam(':pro_id', $ord_l->pro_id, PDO::PARAM_INT);
                $query->bindParam(':pro_name', $ord_l->pro_name, PDO::PARAM_STR);
                $query->bindParam(':unit_name', $ord_l->unit_name, PDO::PARAM_STR);
                $query->bindParam(':qua', $ord_l->qua, PDO::PARAM_INT);
                $query->bindParam(':ord_own', $ord_own, PDO::PARAM_STR);
                $query->execute();  
            }
        }
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'message' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $Ord_lists));

        $dbcon->commit();
        exit;
    }

    if($action == 'delete'){    
        $dbcon->beginTransaction();
        $sql = "DELETE FROM ords WHERE ord_id = $Ord->ord_id";
        $dbcon->exec($sql);

        $sql = "DELETE FROM Ord_lists WHERE ord_id = $Ord->ord_id";
        $dbcon->exec($sql);

        $dbcon->commit();
        
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'message' => 'Record deleted successfully'));
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


