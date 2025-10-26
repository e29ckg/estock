<?php
require '../dbconfig.php';        // ใช้ dbconfig.php ที่สร้าง $dbcon

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    $data = json_decode(file_get_contents("php://input"));

    // ✅ ตรวจสอบ input
    if (empty($data->fullname) || empty($data->email) || empty($data->username) || empty($data->password)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบถ้วน"]);
        exit;
    }

    $fullname = trim($data->fullname);
    $email    = trim($data->email);
    $username = trim($data->username);
    $password = $data->password;

    // ✅ ตรวจสอบ email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "รูปแบบอีเมลไม่ถูกต้อง"]);
        exit;
    }

    // ✅ ตรวจสอบว่ามี email หรือ username ซ้ำหรือไม่
    $query = "SELECT user_id FROM users WHERE email = :email OR username = :username LIMIT 1";
    $stmt = $dbcon->prepare($query);
    $stmt->execute([':email' => $email, ':username' => $username]);

    if ($stmt->rowCount() > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["status" => "error", "message" => "Username หรือ E-Mail มีอยู่ในระบบแล้ว"]);
        exit;
    }

    // ✅ เข้ารหัส password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // ✅ Insert user
    $query = "INSERT INTO users
                (fullname, email, username, password, st, created_at) 
              VALUES 
                (:fullname, :email, :username, :password, 10, NOW())";
    $stmt = $dbcon->prepare($query);

    $success = $stmt->execute([
        ':fullname' => $fullname,
        ':email'    => $email,
        ':username' => $username,
        ':password' => $password_hash
    ]);

    if ($success) {
        http_response_code(201); // Created
        echo json_encode(["status" => true, "message" => "สมัครสมาชิกสำเร็จ"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => false, "message" => "ไม่สามารถสมัครสมาชิกได้"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Server error: " . $e->getMessage()]);
}