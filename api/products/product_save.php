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

$key = "__test_secret__";
$jwt = null;
// $databaseService = new DatabaseService();
// $conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));
$product = $data->product[0];

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

// http_response_code(200);
// echo json_encode(array('status' => true, 'massege' => 'เพิ่มข้อมูลเรียบร้อย', 'responseJSON' => $pro_id ));
// exit;           
try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data = $decoded->data;

    if($product->action == 'insert'){
        $sql = "SELECT pro_name FROM `products` WHERE pro_name = :pro_name";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':pro_name',$product->pro_name, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => false, 'massege' => 'ชื่อสินค้านี้มีในระบบแล้ว', 'responseJSON' => $query->fetchAll(PDO::FETCH_OBJ)));
            exit;
        }

        $sql = "INSERT INTO products(pro_name, pro_detail, cat_id, unit_id, locat, lower, min, st, own) 
                              VALUE(:pro_name, :pro_detail, :cat_id, :unit_id, :locat, :lower, :min, :st, :own);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':pro_name',$product->pro_name, PDO::PARAM_STR);
        $query->bindParam(':pro_detail',$product->pro_detail, PDO::PARAM_STR);
        $query->bindParam(':cat_id',$product->cat_id, PDO::PARAM_INT);
        $query->bindParam(':unit_id',$product->unit_id, PDO::PARAM_INT);
        $query->bindParam(':locat',$product->locat, PDO::PARAM_STR);
        $query->bindParam(':lower',$product->lower, PDO::PARAM_INT);
        $query->bindParam(':min',$product->min, PDO::PARAM_INT);
        $query->bindParam(':st',$product->st, PDO::PARAM_INT);
        $query->bindParam(':own',$data->fullname, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $data));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => false, 'massege' => 'มีบางอย่างผิดพลาด', 'responseJSON' => $data));
        }
        exit;
    }
    if($product->action == 'update'){
        $sql = "UPDATE products SET pro_name =:pro_name, pro_detail =:pro_detail, cat_id =:cat_id, unit_id =:unit_id, locat =:locat, lower =:lower,
        min=:min, st =:st, own =:own WHERE pro_id = :pro_id ";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':pro_name',$product->pro_name, PDO::PARAM_STR);
        $query->bindParam(':pro_detail',$product->pro_detail, PDO::PARAM_STR);
        $query->bindParam(':cat_id',$product->cat_id, PDO::PARAM_INT);
        $query->bindParam(':unit_id',$product->unit_id, PDO::PARAM_INT);
        $query->bindParam(':locat',$product->locat, PDO::PARAM_STR);
        $query->bindParam(':lower',$product->lower, PDO::PARAM_INT);
        $query->bindParam(':min',$product->min, PDO::PARAM_INT);
        $query->bindParam(':st',$product->st, PDO::PARAM_INT);
        $query->bindParam(':own',$data->fullname, PDO::PARAM_STR);
        $query->bindParam(':pro_id',$product->pro_id, PDO::PARAM_INT);
        $query->execute();
        if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $product));
        }else{
            // echo "มีบางอย่างผิดพลาด";
            http_response_code(200);
            echo json_encode(array('status' => false, 'massege' => 'ไม่มีการปรับปรุง', 'responseJSON' => $product));
        }
        exit;
    }
    if($product->action == 'delete'){
    
        $sql = "DELETE FROM products WHERE pro_id = $product->pro_id";
        $dbcon->exec($sql);
        http_response_code(200);
        echo json_encode(array('status' => true, 'massege' => 'Record deleted successfully'));  
        exit;
    }
    /*ดึงข้อมูลทั้งหมด*/
    // $sql = "SELECT * FROM products ORDER BY created_at DESC";
    // $sql = "SELECT products.pro_name, products.pro_id, catalogs.cat_name, units.unit_name FROM products JOIN units ON products.unit_id = units.unit_id JOIN catalogs ON products.cat_id = catalogs.cat_id;";
    // $query = $dbcon->prepare($sql);
    // $query->execute();
    // $result = $query->fetchAll(PDO::FETCH_OBJ);
    // $data = array();

    // foreach($result as $res){
    //     array_push($data,array(
    //         "pro_id" => $res->pro_id,
    //         "pro_name" => $res->pro_name,
    //         "unit_name" => $res->unit_name,
    //         "cat_name" => $res->cat_name
    //     ));
    // }
    // http_response_code(200);
    // echo json_encode(array(
    //     'status' => true, 
    //     'massege' =>  'Ok', 
    //     // 'massege' =>  $result, 
    //     // 'respJSON' => $data->fullname
    //     'respJSON' => $pro_id
    // ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


