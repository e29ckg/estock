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
$store = $data->store[0];
$password = "password";

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

// http_response_code(200);
// echo json_encode(array('status' => true, 'message' => 'เพิ่มข้อมูลเรียบร้อย', 'responseJSON' => $store_id ));
// exit;           
try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data = $decoded->data;

    if($store->action == 'insert'){
        $sql = "SELECT str_name FROM `store` WHERE str_name = :str_name";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':str_name',$store->str_name, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'message' => 'storename นี้มีในระบบแล้ว', 'responseJSON' => $query->fetchAll(PDO::FETCH_OBJ)));
            exit;
        }

        $sql = "INSERT INTO store(str_name,str_detail,str_phone) VALUE(:str_name, :str_detail,:str_phone);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':str_name',$store->str_name, PDO::PARAM_STR);
        $query->bindParam(':str_detail',$store->str_detail, PDO::PARAM_STR);
        $query->bindParam(':str_phone',$store->str_phone, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'message' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $data));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'message' => 'มีบางอย่างผิดพลาด', 'responseJSON' => $data));
        }
        exit;
    }
    if($store->action == 'update'){
        $sql = "UPDATE store SET str_name=:str_name, str_detail=:str_detail, str_phone=:str_phone WHERE str_id = :str_id ";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':str_name',$store->str_name, PDO::PARAM_STR);
        $query->bindParam(':str_detail',$store->str_detail, PDO::PARAM_STR);
        $query->bindParam(':str_phone',$store->str_phone, PDO::PARAM_STR);
        $query->bindParam(':str_id',$store->str_id, PDO::PARAM_INT);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'message' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $store));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'message' => 'ไม่มีการปรับปรุง', 'responseJSON' => $store));
        }
        exit;
    }
    if($store->action == 'delete'){    
        $sql = "DELETE FROM store WHERE str_id = $store->str_id";
        $dbcon->exec($sql);
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'message' => 'Record deleted successfully'));  
        exit;
    }    

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


