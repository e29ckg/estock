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
    $sql = "SELECT recs.*, store.str_name  FROM recs LEFT JOIN store ON recs.str_id = store.str_id ORDER BY recs.rec_id DESC;";
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
        'massege' =>  'Ok', 
        'respJSON' =>  $result, 
        // 'respJSON' => $data
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


