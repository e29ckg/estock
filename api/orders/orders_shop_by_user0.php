<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";
$data = json_decode(file_get_contents("php://input"));
$user_own = $data->user_own;


try{
    
    $sql = "SELECT * FROM ords WHERE ord_own=:ord_own AND st=0;";
    $query = $dbcon->prepare($sql);
    $query->bindParam(':ord_own', $user_own,PDO::PARAM_STR);
    $query->execute();
    $result = count($query->fetchAll(PDO::FETCH_OBJ));
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
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


