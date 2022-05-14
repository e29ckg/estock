<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig_main.php";

try{
    /*ดึงข้อมูลทั้งหมด*/
    // $sql = "SELECT * FROM catalog ORDER BY created_at DESC";
    $sql = "SELECT * FROM user INNER JOIN profile ON user.id = profile.user_id WHERE user.status = 10;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $datas = array();

    foreach($result as $res){
        array_push($datas,array(
            "user_id" => $res->id,
            "username" => $res->username,
            "password" => $res->password_hash,
            "email" => $res->email,
            "fullname" => $res->fname.$res->name.' '.$res->sname,
            "dep" => $res->dep,
            "phone" => $res->phone,
            "st" => $res->status,
        ));
    }
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'massege' =>  'Ok', 
        // 'respJSON' =>  $result, 
        'respJSON' => $datas
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


