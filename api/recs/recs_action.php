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
$Recs = $data->Recs[0];
$password = "password";

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

// http_response_code(200);
// echo json_encode(array('status' => true, 'massege' => 'เพิ่มข้อมูลเรียบร้อย', 'responseJSON' => $Recs_id ));
// exit;           
try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data = $decoded->data;

    if($Recs->action == 'insert'){
        // $sql = "SELECT str_id FROM `recs` WHERE str_name = :str_name";
        // $query = $dbcon->prepare($sql);
        // $query->bindParam(':str_name',$Recs->str_name, PDO::PARAM_STR);
        // $query->execute();
        // if($query->rowCount() > 0){
        //     // echo "เพิ่มข้อมูลเรียบร้อย ok";
        //     http_response_code(200);
        //     echo json_encode(array('status' => 'error', 'massege' => 'Recsname นี้มีในระบบแล้ว', 'responseJSON' => $query->fetchAll(PDO::FETCH_OBJ)));
        //     exit;
        // }

        $sql = "INSERT INTO recs(rec_own, rec_app, str_id) VALUE(:rec_own, :rec_app, :str_id);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':rec_own',$Recs->rec_own, PDO::PARAM_STR);
        $query->bindParam(':rec_app',$Recs->rec_app, PDO::PARAM_STR);
        $query->bindParam(':str_id',$Recs->str_id, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'massege' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $data));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'massege' => 'มีบางอย่างผิดพลาด', 'responseJSON' => $data));
        }
        exit;
    }
    if($Recs->action == 'update'){
        $sql = "UPDATE recs SET rec_own=:rec_own, rec_app=:rec_app, str_id=:str_id WHERE rec_id = :rec_id ";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':str_name',$Recs->str_name, PDO::PARAM_STR);
        $query->bindParam(':rec_app',$Recs->rec_app, PDO::PARAM_STR);
        $query->bindParam(':str_id',$Recs->str_id, PDO::PARAM_STR);
        $query->bindParam(':str_id',$Recs->rec_id, PDO::PARAM_INT);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'massege' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $Recs));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'massege' => 'ไม่มีการปรับปรุง', 'responseJSON' => $Recs));
        }
        exit;
    }
    if($Recs->action == 'delete'){    
        $sql = "DELETE FROM recs WHERE rec_id = $Recs->rec_id";
        $dbcon->exec($sql);
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'massege' => 'Record deleted successfully'));  
        exit;
    }    

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


