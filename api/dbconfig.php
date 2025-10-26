<?php
date_default_timezone_set("Asia/Bangkok");

// 🔑 JWT secret key (ควรเก็บใน .env)
$key = getenv('JWT_SECRET');

// ✅ โหลดค่าจาก environment variables (Docker Compose / .env)
$db_host = getenv('DB_HOST') ?: 'db';              // service name ของ MySQL ใน docker-compose
$db_name = getenv('MYSQL_DATABASE') ?: 'estock';
$db_user = getenv('MYSQL_USER') ?: 'myuser';
$db_pass = getenv('MYSQL_PASSWORD') ?: 'mypass';

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // โยน exception เมื่อ error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch เป็น associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // ใช้ native prepared statements
];

try {
    $dbcon = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => false,
        'message' => 'Database connection failed',
        'error'   => $e->getMessage()
    ]);
    exit;
}