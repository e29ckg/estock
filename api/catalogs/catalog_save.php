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

$key = "__test_secret__";
$jwt = null;
// $databaseService = new DatabaseService();
// $conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));
$catalog = $data->catalog[0];

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

// http_response_code(200);
// echo json_encode(array('status' => true, 'message' => 'เพิ่มข้อมูลเรียบร้อย', 'responseJSON' => $cat_id ));
// exit;           
try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data = $decoded->data;

    if($catalog->action == 'insert'){
        $sql = "SELECT cat_name FROM `catalogs` WHERE cat_name = :cat_name";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':cat_name',$catalog->cat_name, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'ชื่อสินค้านี้มีในระบบแล้ว', 'responseJSON' => $query->fetchAll(PDO::FETCH_OBJ)));
            exit;
        }

        $sql = "INSERT INTO catalogs(cat_name) VALUE(:cat_name);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':cat_name',$catalog->cat_name, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $data));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'มีบางอย่างผิดพลาด', 'responseJSON' => $data));
        }
        exit;
    }
    if($catalog->action == 'update'){
        $sql = "UPDATE catalogs SET cat_name =:cat_name WHERE cat_id = :cat_id ";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':cat_name',$catalog->cat_name, PDO::PARAM_STR);
        $query->bindParam(':cat_id',$catalog->cat_id, PDO::PARAM_INT);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $catalog));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'ไม่มีการปรับปรุง', 'responseJSON' => $catalog));
        }
        exit;
    }
    if($catalog->action == 'delete'){
    
        $sql = "DELETE FROM catalogs WHERE cat_id = $catalog->cat_id";
        $dbcon->exec($sql);
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'Record deleted successfully'));  
        exit;
    }    

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


