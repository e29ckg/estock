<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

try{
    $total  = 0;
    $max    = 0;
    $min    = 0;
    $limit  = 100;

    $sql_test = "SELECT COUNT(ord_id) FROM `ords`";
    $query = $dbcon->prepare($sql_test);
    $query->execute();

    $total  = $query->fetchColumn();
    $max    = $total;
    if($max >= $limit){$min = $max - $limit;}

    /*ดึงข้อมูลทั้งหมด*/
    $sql = "SELECT * FROM ords ORDER BY ord_id DESC LIMIT $limit;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    // $data = array();

    // foreach($result as $res){
    //     array_push($data,array(
    //         "unit_id" => $res->unit_id,
    //         "unit_name" => $res->unit_name
    //     ));
    // }
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'message' =>  'Ok', 
        'respJSON' =>  $result, 
        // 'respJSON' => $data
    ));

}catch(PDOException $e){
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


