<?php
include_once './database.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$fullname = '';
$email = '';
$password = '';
$conn = null;

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));

$fullname = $data->fullname;
$email = $data->email;
$password = $data->password;
$username = $data->username;

$table_name = 'Users';


$query = "SELECT user_id, fullname, password, role FROM " . $table_name . " WHERE email = ? OR username = ? LIMIT 0,1";

$stmt = $conn->prepare( $query );
$stmt->bindParam(1, $email);
$stmt->bindParam(2, $username);
$stmt->execute();
$num = $stmt->rowCount();

if($num > 0){
    http_response_code(200);
    echo json_encode(array("message" => "Username OR E-Mail มีอยู่ในระบบแล้ว."));
    exit;
}

$query = "INSERT INTO " . $table_name . "
                SET fullname = :fullname,
                    email = :email,
                    username = :username,
                    password = :password,
                    st = 0";

$stmt = $conn->prepare($query);

$stmt->bindParam(':fullname', $fullname);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':username', $username);

$password_hash = password_hash($password, PASSWORD_BCRYPT);

$stmt->bindParam(':password', $password_hash);


if($stmt->execute()){

    http_response_code(200);
    echo json_encode(array("status" => "ok","message" => "User was successfully registered."));
}
else{
    http_response_code(400);

    echo json_encode(array("status" => "error","message" => "Unable to register the user."));
}
?>