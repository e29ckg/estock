<?php
require __DIR__ . "/../dbconfig.php";
require __DIR__ . "/verify_jwt.php"; // ✅ ตรวจสอบ JWT และได้ $userData

$role = $userData['role'] ?? 'guest';



if ($role === 'admin') {
    // ✅ ดึงจำนวน Orders ที่ยังไม่ดำเนินการ
    $sql = "SELECT COUNT(*) AS cnt FROM orders WHERE st = 0";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $ordersCount = (int) $row['cnt'];

    $sql = "SELECT COUNT(*) AS cnt FROM recs WHERE st = 0";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $recsCount = (int) $row['cnt'];


    $menus = [
        [
            "menu_name" => "Dashboard(User)",
            "menu_url" => "index.php",
            "menu_icon_class" => "nav-icon fas fa-tachometer-alt",
            "menu_class" => "active",
            "menu_badge" => ""
        ],
        [
            "menu_name" => "Orders(Admin)",
            "menu_url" => "orders.php",
            "menu_icon_class" => "nav-icon fas fa-shopping-cart",
            "menu_class" => "",
            "menu_badge" => $ordersCount
        ],
        [
            "menu_name" => "Receipts", // เดิม rec → ปรับให้ชัดเจน
            "menu_url" => "recs.php",
            "menu_icon_class" => "nav-icon fas fa-receipt",
            "menu_class" => "",
            "menu_badge" => $recsCount
        ],
        [
            "menu_name" => "Report", // เดิม Repoart → แก้สะกด
            "menu_url" => "report.php",
            "menu_icon_class" => "nav-icon fas fa-chart-line",
            "menu_class" => "",
            "menu_badge" => ""
        ],
    ];

    $menus_setting = [
        
        [
            "menu_name" => "Products",
            "menu_url" => "products.php",
            "menu_icon_class" => "nav-icon fas fa-box-open", // ✅ icon เหมาะกับสินค้า
            "menu_class" => "",
            "menu_badge" => ""
        ],
        [
            "menu_name"       => "ประเภท", // ✅ ใช้คำที่ชัดเจนตรงกับเนื้อหา
            "menu_url"        => "catalogs.php",
            "menu_icon_class" => "nav-icon fas fa-box-open",
            "menu_class"      => "",
            "menu_badge"      => ""
        ],
        [
            "menu_name"       => "หน่วยนับ", // ✅ ใช้คำที่ชัดเจนตรงกับเนื้อหา
            "menu_url"        => "units.php",
            "menu_icon_class" => "nav-icon fas fa-box-open",
            "menu_class"      => "",
            "menu_badge"      => ""
        ],
        [
            "menu_name" => "ร้านค้า",
            "menu_url" => "store.php",
            "menu_icon_class" => "nav-icon fas fa-store", // ✅ icon เหมาะกับร้านค้า
            "menu_class" => "",
            "menu_badge" => ""
        ],
        [
            "menu_name" => "Users",
            "menu_url" => "users.php",
            "menu_icon_class" => "nav-icon fas fa-users", // ✅ เพิ่ม icon
            "menu_class" => "",
            "menu_badge" => ""
        ],
        [
            "menu_name" => "Profile",
            "menu_url" => "profile.php",
            "menu_icon_class" => "nav-icon fas fa-user",
            "menu_class" => "",
            "menu_badge" => ""
        ],
    ];
} else {
    $menus = [
        [
            "menu_name" => "Dashboard", 
            "menu_url" => "index.php", 
            "menu_icon_class" => "nav-icon fas fa-tachometer-alt", 
            "menu_class" => "active", 
            "menu_badge" => ""
        ]
    ];
    $menus_setting = [
        ["menu_name" => "Profile", 
        "menu_url" => "Profile.php", 
        "menu_icon_class" => "nav-icon fas fa-box-open", 
        "menu_class" => "", 
        "menu_badge" => ""]
    ];
}



echo json_encode([
    "status" => true,
    "menus" => $menus,
    "menus_setting" => $menus_setting
]);