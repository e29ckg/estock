<?php
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
//         'message' =>  'Ok', 
//         'respJSON' => $data->pro_id
//     ));
//     exit;
try{
    /*ดึงข้อมูลทั้งหมด*/
    $sql = "SELECT * FROM `users` WHERE user_id = $data->user_id LIMIT 0,1;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $datas = array();
    foreach($result as $res){
        array_push($datas,array(
            "user_id" => $res->user_id,
            "email" => $res->email,
            "username" => $res->username,
            "dep" => $res->dep,
            "fullname" => $res->fullname,
            "role" => $res->role,
            "phone" => $res->phone,
            "st" => $res->st,
        ));
    }

    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'message' =>  'Ok', 
        // 'respJSON' =>  $result, 
        'respJSON' => $datas
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}