<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

$data = json_decode(file_get_contents("php://input"));
$rec_id = $data->rec_id;

try{
    /*ดึงข้อมูลทั้งหมด*/
    $sql = "SELECT * FROM `recs` 
    INNER JOIN store ON recs.str_id = store.str_id
    WHERE recs.rec_id = $rec_id ;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result_rec = $query->fetchAll(PDO::FETCH_OBJ);

    $sql = "SELECT * FROM `recs` 
            INNER JOIN rec_lists ON recs.rec_id = rec_lists.rec_id
            WHERE recs.rec_id = $rec_id ;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $datas = array();
    $price = 0;
    foreach($result as $rs){
        $price = $price + $rs->price;
    }
       
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'message' =>  'Ok', 
        'respJSON' =>  $result,
        'price_all' => $price,
        'rec' => $result_rec[0],
    ));

}catch(PDOException $e){
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}