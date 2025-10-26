<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../dbconfig.php";
require_once "../auth/checkAuth.php";
checkAuth($userData,['admin']);

date_default_timezone_set("Asia/Bangkok");

$input = json_decode(file_get_contents("php://input"), true);
$user = $input['user'] ?? null;

if (!$user) {
    echo json_encode(["status" => false, "message" => "Missing user data"]);
    exit;
}

try {
    // ✅ INSERT
    if ($user['action'] === 'insert') {
        // ตรวจสอบ username ซ้ำ
        $sql = "SELECT username FROM users WHERE username = :username";
        $stmt = $dbcon->prepare($sql);
        $stmt->bindParam(':username', $user['username'], PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => false, "message" => "Username นี้มีในระบบแล้ว"]);
            exit;
        }

        $sql = "INSERT INTO users(fullname, username, password, email, dep, phone, st, role, created_at, updated_at)
                VALUES(:fullname, :username, :password, :email, :dep, :phone, :st, :role, NOW(), NOW())";
        $stmt = $dbcon->prepare($sql);
        $stmt->execute([
            ":fullname" => $user['fullname'],
            ":username" => $user['username'],
            ":password" => password_hash($user['password'], PASSWORD_BCRYPT),
            ":email"    => $user['email'],
            ":dep"      => $user['dep'],
            ":phone"    => $user['phone'],
            ":st"       => $user['st'],
            ":role"     => $user['role']
        ]);

        echo json_encode(["status" => true, "message" => "เพิ่มผู้ใช้เรียบร้อยแล้ว"]);
        exit;
    }

    // ✅ UPDATE
    if ($user['action'] === 'update') {
        $sql = "UPDATE users SET 
                    fullname = :fullname,
                    username = :username,
                    email    = :email,
                    dep      = :dep,
                    phone    = :phone,
                    st       = :st,
                    role     = :role,
                    updated_at = NOW()";

        $params = [
            ":fullname" => $user['fullname'],
            ":username" => $user['username'],
            ":email"    => $user['email'],
            ":dep"      => $user['dep'],
            ":phone"    => $user['phone'],
            ":st"       => $user['st'],
            ":role"     => $user['role'],
            ":user_id"  => $user['user_id']
        ];

        // ถ้ามีการกรอกรหัสผ่านใหม่
        if (!empty($user['password'])) {
            $sql .= ", password = :password";
            $params[":password"] = password_hash($user['password'], PASSWORD_BCRYPT);
        }

        $sql .= " WHERE user_id = :user_id";

        $stmt = $dbcon->prepare($sql);
        $stmt->execute($params);

        echo json_encode(["status" => true, "message" => "แก้ไขผู้ใช้เรียบร้อยแล้ว"]);
        exit;
    }

    // ✅ Soft Delete
    if ($user['action'] === 'delete') {
        $sql = "UPDATE users 
                SET deleted_at = NOW(), st = 0, updated_at = NOW() 
                WHERE user_id = :user_id";
        $stmt = $dbcon->prepare($sql);
        $stmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["status" => true, "message" => "ปิดการใช้งานผู้ใช้เรียบร้อยแล้ว"]);
        exit;
    }

    // ถ้า action ไม่ถูกต้อง
    echo json_encode(["status" => false, "message" => "Invalid action"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}