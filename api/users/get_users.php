<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

try{
    /*ดึงข้อมูลทั้งหมด*/
    // $sql = "SELECT * FROM catalog ORDER BY created_at DESC";
    $sql = "SELECT * FROM users ;";
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


