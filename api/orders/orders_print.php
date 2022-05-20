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
    $sql = "SELECT ords.*, users.dep, users.phone, users.email FROM `ords` 
            INNER JOIN users ON ords.ord_own = users.fullname
            WHERE ords.ord_id = $data->ord_id ;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result_order = $query->fetchAll(PDO::FETCH_OBJ);

    $sql = "SELECT * FROM `ord_lists` WHERE ord_id = $data->ord_id ;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $datas = array();

    foreach($result as $rs){
        $sql = "SELECT img FROM `products` WHERE pro_id = $rs->pro_id LIMIT 0,1;";
        $query = $dbcon->prepare($sql);
        $query->execute();
        $resp_product = $query->fetchAll(PDO::FETCH_OBJ);
        $resp_product[0]->img ? $img = $resp_product[0]->img : $img = 'none.png';
        
        
        array_push($datas,array(
            'ord_list_id' => $rs->ord_list_id,
            'ord_id' => $rs->ord_id,
            'pro_id' => $rs->pro_id,
            'pro_name' => $rs->pro_name,
            'img' => $img,
            'unit_name' => $rs->unit_name,
            'qua' => $rs->qua,
            'qua_pay' => $rs->qua_pay,
        ));
    }

    http_response_code(200);
    echo json_encode(array(
        'status' => 'success', 
        'message' => 'Ok', 
        // 'respJSON' =>  $result, 
        'order_lists' => $datas,
        'order' => $result_order,
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}