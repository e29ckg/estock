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
    $sql = "SELECT stock.*, products.pro_name, products.instock FROM stock LEFT JOIN products ON stock.pro_id = products.pro_id ORDER BY products.pro_name,stock.stck_id ASC;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $total_row = count($result);

    $begin = $total_row - 20;
    if($begin < 0){$begin = 0;}
    $end = $total_row;

    // $sql = "SELECT stock.*, products.pro_name, products.instock FROM stock LEFT JOIN products ON stock.pro_id = products.pro_id ORDER BY products.pro_name,stock.stck_id ASC";
    // // $sql = "SELECT stock.*, products.pro_name, products.instock FROM stock LEFT JOIN products ON stock.pro_id = products.pro_id LIMIT $begin,$end;";
    // $query = $dbcon->prepare($sql);
    // $query->execute();
    // $result = $query->fetchAll(PDO::FETCH_OBJ);
    
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'message' =>  'Ok', 
        'respJSON' =>  $result, 
        'total_row' => $total_row
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


