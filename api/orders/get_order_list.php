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
    $sql = "SELECT * FROM `ord_lists` WHERE ord_id = $data->ord_id ;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $datas = array();

    foreach($result as $rs){
        $sql = "SELECT bal FROM `stock` WHERE pro_id = $rs->pro_id ORDER BY stck_id DESC LIMIT 0,1;";
        $query = $dbcon->prepare($sql);
        $query->execute();
        $stock = $query->fetchAll(PDO::FETCH_OBJ);
        if($stock){
            $instock = $stock[0]->bal;
        }else{
            $instock = 0;
        }

        array_push($datas,array(
            'ord_list_id' => $rs->ord_list_id,
            'ord_id' => $rs->ord_id,
            'pro_id' => $rs->pro_id,
            'pro_name' => $rs->pro_name,
            'unit_name' => $rs->unit_name,
            'instock' => $instock,
            'qua' => $rs->qua,
            'qua_pay' => $rs->qua_pay,
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