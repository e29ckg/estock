<?php
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estock";

try{
    $conn = new PDO("mysql:host=$servername;", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->query('SHOW DATABASES');
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $dbAll = array();
    foreach($databases as $database){
        //$database will contain the database name
        //in a string format
        if($database == $dbname){
            echo "พบฐานข้อมูล $dbname <br>";
            $sql = "DROP DATABASE $dbname;";
            $conn->exec($sql);
            echo "DROP Database $dbname successfully<br>";
        }
        // echo $database, '<br>';
        array_push($dbAll,$database);
    }
    // if(in_array($dbname,$dbAll)){
    //     $sql = "DROP DATABASE $dbname;";
    //     $conn->exec($sql);
    //     echo "Database DROP $dbname successfully<br>";
    //     echo 'ok <br>';
    // }    
    // die;

    $sql = "CREATE DATABASE $dbname CHARACTER SET utf8 COLLATE utf8_general_ci;";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database created successfully<br>";


    /** table users */
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql to create table
    $sql = "CREATE TABLE users (
        user_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(250) NOT NULL,
        password VARCHAR(250) NOT NULL,
        token VARCHAR(250) NULL,
        email VARCHAR(250) NOT NULL,
        role VARCHAR(100) NOT NULL,
        fullname VARCHAR(250) NOT NULL,
        dep VARCHAR(250) NULL,
        phone VARCHAR(100) NULL,
        st INT(13) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE (user_id)
    )";
    $conn->exec($sql);
    echo "Table users created successfully<br>";

    $password = "admin";
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO users(username, password, email, role, fullname, dep, phone, st)
    VALUES ('admin', '$password_hash', 'admin@example.com', 'admin', 'administartor','-','0123456789',10)";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Admin New record created successfully<br>";


    //** TABLE Products */
    $sql = "CREATE TABLE products(
        pro_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        pro_name VARCHAR(250) NOT NULL,
        pro_detail TEXT NULL,
        cat_name VARCHAR(250) NOT NULL,
        unit_name VARCHAR(250) NOT NULL,
        instock INT(10) DEFAULT 0,
        locat VARCHAR(250) DEFAULT 1,
        lower INT(10) DEFAULT 1,
        min INT(10) DEFAULT 1,
        st INT(10) DEFAULT 0,
        img VARCHAR(250) NULL,
        own VARCHAR(250) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table Products created successfully<br>";

    $sql = "INSERT INTO products(pro_name, cat_name, unit_name,min)
            VALUES  ('กระดาษ A4', 'วัสดุสำนักงาน', 'รีม', 5)
                    ";
    $conn->exec($sql);
    echo "ADD catalogs New record created successfully<br>";
    //** ------------------ */

    //** TABLE Catalogs */
    $sql = "CREATE TABLE catalogs(
        cat_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        cat_name VARCHAR(250) NOT NULL,
        cat_detail VARCHAR(250) NULL,
        cat_sort INT(13) NULL
    )";
    $conn->exec($sql);
    echo "Table Catalogs created successfully<br>";

    $sql = "INSERT INTO catalogs(cat_name, cat_sort)
            VALUES  ('วัสดุสำนักงาน', 1),
                    ('วัสดุคอมพิวเตอร์', 2),
                    ('วัสดุไฟฟ้า', 3),
                    ('วัสดุงานบ้านงานครัว', 4)";
    $conn->exec($sql);
    echo "ADD catalogs New record created successfully<br>";
    //** ------------------ */
    
    //** TABLE Units */
    $sql = "CREATE TABLE units (
        unit_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        unit_name VARCHAR(250) NOT NULL
    )";
    $conn->exec($sql);
    echo "Table Units created successfully<br>";

    $sql = "INSERT INTO units(unit_name)
            VALUES  ('รีม'),('ใบ'),('กล่อง'),('อัน'),
                    ('ม้วน'),('ซอง'),('แท่ง'),('ตลับ'),('ด้าม'),
                    ('คู่'),('เล่ม'),('ขวด'),('ก้อน'),('ไม้'),('แผ่น')";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "ADD Unit New record created successfully<br>";
    //** ------------------ */
    
    //** TABLE Store */
    $sql = "CREATE TABLE store (
        str_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        str_name VARCHAR(250) NOT NULL,
        str_detail VARCHAR(250) NULL,
        str_phone VARCHAR(250) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table Units created successfully<br>";

    $sql = "INSERT INTO `store` VALUES (1,'อุ่นรุ่งกิจ ','67/1-4 ถ.พิทักษ์ชาติ ต.ประจวบคีรีขันธ์ อ.เมือง จ.ประจวบคีรีขันธ์','032-602150','2022-05-10 10:22:31','2022-05-10 13:25:36'),(2,'บริษัท อาร์.เอส.ที.ออโตเมชั่น จำกัด (สำนักงานใหญ่)','227/16 ม.4 ถ.ชนเกษม ต.มะขามเตี้ย อ.เมือง จ.สุราษฎร์ธานี','077-218-6934','2022-05-10 10:22:31','2022-05-10 13:30:21'),(3,'บริษัท มิสเตอร์ อิ๊งค์ คอมพิวเตอร์ เซอร์วิส จำกัด (สำนักงานใหญ่)','6 ซ.วัดสุขใจ 5 แขวงทรายกองดิน เขตคลองสามวา กรุงเทพมหานคร','02-914-5200/ 02-914-5300/ 02-543-6926-30/ 086-345-5960-1','2022-05-10 10:22:31','2022-05-10 13:35:58'),(4,'พีเค ซัพพลาย','55/276 ม.6 ซ.เจริญใจ ถ.เทพารักษ์ ต.บางเมือง อ.เมือง จ.สมุทรปราการ','091-043-6653/ 062-069-9664/ 095-016-7019','2022-05-10 10:22:31','2022-05-10 13:29:37'),(5,'ร้าน ทีพีพี พริ้นติ้ง','264/95 ม.4 ถ.อำเภอ ต.มะขามเตี้ย อ.เมือง จ.สุราษฎร์ธานี','077-310137','2022-05-10 13:32:34','2022-05-10 13:32:34'),(6,'ร้านทิพย์รัตน์','51/2 ถ.ประจวบ อ.เมือง จ.ประจวบคีรีขันธ์','-','2022-05-10 13:34:06','2022-05-10 13:34:06');";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "ADD store New record created successfully<br>";
    //** ------------------ */
    
    //** TABLE Stock */
    $sql = "CREATE TABLE stock(
        stck_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        pro_id INT(13) NOT NULL,
        unit_name VARCHAR(250) NULL,
        price_one VARCHAR(100) NULL,
        bf INT(10) NOT NULL,
        stck_in INT(10) NULL,
        stck_out INT(10) NULL,
        bal INT(10) NOT NULL,
        rec_ord_id INT(10) NULL,
        rec_ord_list_id INT(10) NULL,
        comment VARCHAR(250) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table Units created successfully<br>";
    //** ------------------ */

    //** TABLE Recs */
    $sql = "CREATE TABLE recs(
        rec_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        rec_own VARCHAR(250) NULL,
        rec_app VARCHAR(250) NULL,
        rec_date DATE NULL,
        str_id INT(13) NOT NULL,
        price_total VARCHAR(250) NULL,
        comment VARCHAR(250) NULL,
        st INT(10) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table Recs created successfully<br>";
    //** ------------------ */
    
    //** TABLE Rec_lists */
    $sql = "CREATE TABLE rec_lists(
        rec_list_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        rec_id INT(13) NOT NULL,
        rec_date DATE NULL,
        pro_id INT(13) NOT NULL,
        pro_name VARCHAR(250) NULL,
        unit_name VARCHAR(250) NOT NULL,
        qua INT(10) NOT NULL,
        qua_for_ord INT(10) NOT NULL,
        price_one VARCHAR(250) NOT NULL,
        price VARCHAR(250) NOT NULL,
        rec_own VARCHAR(250) NOT NULL,
        rec_app VARCHAR(250) NOT NULL,
        st INT(10) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table Recs created successfully<br>";
    //** ------------------ */

    //** TABLE Ords */
    $sql = "CREATE TABLE ords(
        ord_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        ord_own VARCHAR(250) NULL,
        ord_date DATE DEFAULT CURRENT_TIMESTAMP,
        ord_app VARCHAR(250) NULL,
        ord_pay_date DATETIME NULL,
        ord_pay_own VARCHAR(250) NULL,
        comment VARCHAR(250) NULL,
        st INT(10) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table Ords created successfully<br>";
    //** ------------------ */

    //** TABLE Ord_lists */
    $sql = "CREATE TABLE ord_lists(
        ord_list_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        ord_id INT(13) NOT NULL,
        pro_id INT(13) NOT NULL,
        pro_name VARCHAR(250) NULL,
        unit_name VARCHAR(250) NOT NULL,
        qua INT(10) DEFAULT 0,
        qua_pay INT(10) DEFAULT 0,
        ord_own VARCHAR(250) NOT NULL,
        ord_app VARCHAR(250) NOT NULL,
        st INT(10) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table Ord_lists created successfully<br>";
    //** ------------------ */

    //** TABLE STA */
    $sql = "CREATE TABLE sta(
        st_id INT(13) AUTO_INCREMENT PRIMARY KEY,
        st_name VARCHAR(250) NOT NULL        
    )";
    $conn->exec($sql);
    echo "Table STA created successfully<br>";
    //** ------------------ */

}
catch(PDOException $e)
{
echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>