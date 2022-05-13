<?php
include_once "../dbconfig.php";
require "../auth/vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

$jwt = null;

$data = json_decode(file_get_contents("php://input"));
$user = $data->user;

// http_response_code(200);
// echo json_encode(array('status' => 'success', 'massege' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $user->password));
// exit;

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
$arr = explode(" ", $authHeader);

try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data_auth = $decoded->data;   
   
    $dbcon->beginTransaction();

    /*ดึงข้อมูลทั้งหมด*/
    // $sql = "SELECT * FROM catalog ORDER BY created_at DESC";
        $sql = "SELECT username FROM `users` WHERE username = :username";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':username',$user->username, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'massege' => 'Username นี้มีในระบบแล้ว', 'responseJSON' => $query->fetchAll(PDO::FETCH_OBJ)));
            exit;
        }

        $sql = "INSERT INTO users(fullname,username,password,email,dep,phone) VALUE(:fullname, :username,:password,:email,:dep,:phone);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':fullname',$user->fullname, PDO::PARAM_STR);
        $query->bindParam(':username',$user->username, PDO::PARAM_STR);
        $query->bindParam(':password',$user->password, PDO::PARAM_STR);
        $query->bindParam(':email',$user->email, PDO::PARAM_STR);
        $query->bindParam(':dep',$user->dep, PDO::PARAM_STR);
        $query->bindParam(':phone',$user->phone, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'massege' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $data));
            $dbcon->commit();
        }else{            
            $dbcon->rollback();
                // If we got here our two data updates are not in the database           
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'massege' => 'มีบางอย่างผิดพลาด', 'responseJSON' => $data));
        }
        exit;
   

}catch(PDOException $e){
    if ($dbcon->inTransaction()) {
        $dbcon->rollback();
        // If we got here our two data updates are not in the database
    }
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


