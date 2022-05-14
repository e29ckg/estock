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
//         'massege' =>  'Ok', 
//         'respJSON' => $data->pro_id
//     ));
//     exit;

$ord_id = $data->ord_id;
try{
    /*ดึงข้อมูลทั้งหมด*/
    $sql = "SELECT ord_lists.*, products.img FROM `ord_lists` INNER JOIN products ON products.pro_id = ord_lists.pro_id WHERE ord_lists.ord_id = :ord_id ORDER BY ord_lists.ord_list_id ASC;";
    $query = $dbcon->prepare($sql);
    $query->bindParam(':ord_id',$ord_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $datas = array();

    http_response_code(200);
    echo json_encode(array(
        'status' => 'success', 
        'massege' =>  'Ok', 
        'respJSON' =>  $result, 
        'respJSON2' => $data->ord_id
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}