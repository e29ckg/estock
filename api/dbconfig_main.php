<?php
$servername = "localhost";
$username = "root";
$userpass = "";
$dbname = "main";

try{
    $dbcon = new PDO("mysql:host=$servername;dbname=$dbname", $username, $userpass);
    // set the PDO error mode to exception
    $dbcon->exec("set names utf8");
    $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    exit;
}


