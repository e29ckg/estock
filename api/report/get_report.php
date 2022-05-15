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
try{
    $datas = array();

    /*ดึงข้อมูลทั้งหมด*/
    $sql = "SELECT * FROM catalogs ORDER BY cat_sort ASC;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result_cat = $query->fetchAll(PDO::FETCH_OBJ);
    $i=1;
    $price_all = 0;
    foreach($result_cat as $rc){

        $sql = "SELECT rec_lists.*, products.cat_name FROM rec_lists INNER JOIN products ON rec_lists.pro_id = products.pro_id WHERE products.cat_name = '$rc->cat_name' ORDER BY rec_lists.pro_id ASC;";
        $query = $dbcon->prepare($sql);
        $query->execute();
        $result_rec_lists = $query->fetchAll(PDO::FETCH_OBJ);
        $lists = array();
        
        foreach($result_rec_lists as $rl){
            $price = $rl->qua_for_ord * $rl->price_one;
            array_push($lists,array(
                "no" => $i++,
                "rec_date" => $rl->rec_date,
                "pro_id" => $rl->pro_id,
                "pro_name" => $rl->pro_name,
                "cat_name" => $rl->cat_name,
                "unit_name" => $rl->unit_name,
                "qua_for_ord" => $rl->qua_for_ord,
                "price_one" => $rl->price_one,
                "price" => $price,
            ));   
            $price_all = $price_all + $price;
        }
        array_push($datas,array(
            "cat_name" => $rc->cat_name,
            "lists" => $lists
        ));

    }
    
    
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'massege' =>  'Ok', 
        'respJSON' => $datas,
        'price_all' => $price_all
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}