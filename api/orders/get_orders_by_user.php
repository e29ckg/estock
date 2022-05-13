<?php
include_once "../dbconfig.php";
require "../auth/vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

$data = json_decode(file_get_contents("php://input"));
// $product = $data;

// http_response_code(200);
//     echo json_encode(array(
//         'status' => true, 
//         'massege' =>  'Ok', 
//         'respJSON' => $data->pro_id
//     ));
//     exit;

$ord_own = '';

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
// 
$arr = explode(" ", $authHeader);

try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data_auth = $decoded->data;
    
    $ord_own = $data_auth->fullname;

    /*ดึงข้อมูลทั้งหมด*/
    $sql = "SELECT * FROM `ords` WHERE ord_own = :ord_own ORDER BY ord_id DESC LIMIT 0,10;";
    $query = $dbcon->prepare($sql);
    $query->bindParam(':ord_own',$ord_own, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $datas = array();

    http_response_code(200);
    echo json_encode(array(
        'status' => 'success', 
        'massege' =>  'Ok', 
        'respJSON' =>  $result, 
        // 'respJSON' => $datas
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}