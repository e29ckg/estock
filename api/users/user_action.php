<?php
include_once "../dbconfig.php";
require "../auth/vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set("Asia/Bangkok");

// $key = "__test_secret__";
$jwt = null;
// $databaseService = new DatabaseService();
// $conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));
$user = $data->user[0];
$password = "password";

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

// http_response_code(200);
// echo json_encode(array('status' => true, 'massege' => 'เพิ่มข้อมูลเรียบร้อย', 'responseJSON' => $user_id ));
// exit;           
try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data = $decoded->data;

    if($user->action == 'insert'){
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

        $sql = "INSERT INTO users(fullname,username,password,email,dep,phone,st,role) VALUE(:fullname, :username,:password,:email,:dep,:phone,:st,role);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':fullname',$user->fullname, PDO::PARAM_STR);
        $query->bindParam(':username',$user->username, PDO::PARAM_STR);
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $query->bindParam(':password',$password_hash, PDO::PARAM_STR);
        $query->bindParam(':email',$user->email, PDO::PARAM_STR);
        $query->bindParam(':dep',$user->dep, PDO::PARAM_STR);
        $query->bindParam(':phone',$user->phone, PDO::PARAM_STR);
        $query->bindParam(':st',$user->st, PDO::PARAM_INT);
        $query->bindParam(':role',$user->role, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'massege' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $data));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'massege' => 'มีบางอย่างผิดพลาด', 'responseJSON' => $data));
        }
        exit;
    }
    if($user->action == 'update'){
        $sql = "UPDATE users SET fullname=:fullname, username=:username, email=:email, dep=:dep, phone=:phone, st=:st, role=:role WHERE user_id = :user_id ";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':fullname',$user->fullname, PDO::PARAM_STR);
        $query->bindParam(':username',$user->username, PDO::PARAM_STR);
        // $password_hash = password_hash($password, PASSWORD_BCRYPT);
        // $query->bindParam(':password',$password_hash, PDO::PARAM_STR);
        $query->bindParam(':email',$user->email, PDO::PARAM_STR);
        $query->bindParam(':dep',$user->dep, PDO::PARAM_STR);
        $query->bindParam(':phone',$user->phone, PDO::PARAM_STR);
        $query->bindParam(':st',$user->st, PDO::PARAM_INT);
        $query->bindParam(':role',$user->role, PDO::PARAM_STR);
        $query->bindParam(':user_id',$user->user_id, PDO::PARAM_INT);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'massege' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $user));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => 'error', 'massege' => 'ไม่มีการปรับปรุง', 'responseJSON' => $user));
        }
        exit;
    }
    if($user->action == 'delete'){
    
        $sql = "DELETE FROM users WHERE user_id = $user->user_id";
        $dbcon->exec($sql);
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'massege' => 'Record deleted successfully'));  
        exit;
    }    

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


