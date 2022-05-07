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
$Recs = $data->Recs[0];

$password = "password";

$rec_own = '';

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

// http_response_code(200);
// echo json_encode(array('status' => 'success', 'massege' => 'เพิ่มข้อมูลเรียบร้อย', 'responseJSON' => $Recs->str_id ));
// die; 

try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data_auth = $decoded->data;
    
    
    
    if($Recs->action == 'insert'){
        $dbcon->beginTransaction();
        $rec_id = time();
        
        $sql = "INSERT INTO recs(rec_id, rec_date, str_id, price_total, rec_own, comment) VALUE(:rec_id, :rec_date, :str_id, :price_total, :rec_own, :comment);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':rec_id', $rec_id,PDO::PARAM_INT);
        $query->bindParam(':rec_date', $Recs->rec_date);
        $query->bindParam(':str_id',$Recs->str_id, PDO::PARAM_INT);
        $query->bindParam(':price_total',$Recs->price_total, PDO::PARAM_STR);
        $query->bindParam(':rec_own',$rec_own, PDO::PARAM_STR);
        $query->bindParam(':comment',$Recs->comment, PDO::PARAM_STR);
        $query->execute();  
        
        $Rec_lists = $data->Rec_lists;
        $i = 0;
        foreach($Rec_lists as $rls){
            if($Rec_lists[$i]->pro_id != ''){
                $sql = "INSERT INTO rec_lists(rec_id, pro_id, pro_name, unit_name, qua, price_one, price, rec_own) VALUE(:rec_id, :pro_id, :pro_name, :unit_name, :qua, :price_one, :price, :rec_own);";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':rec_id', $rec_id, PDO::PARAM_INT);
                $query->bindParam(':pro_id', $Rec_lists[$i]->pro_id, PDO::PARAM_INT);
                $query->bindParam(':pro_name', $Rec_lists[$i]->pro_name, PDO::PARAM_STR);
                $query->bindParam(':unit_name', $Rec_lists[$i]->unit_name, PDO::PARAM_STR);
                $query->bindParam(':qua', $Rec_lists[$i]->qua, PDO::PARAM_INT);
                $query->bindParam(':price_one', $Rec_lists[$i]->price_one, PDO::PARAM_STR);
                $query->bindParam(':price', $Rec_lists[$i]->price, PDO::PARAM_STR);
                $query->bindParam(':rec_own', $rec_own, PDO::PARAM_STR);
                $query->execute();  
            }
            $i++ ;
        }
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'massege' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $Rec_lists));

        $dbcon->commit();
        exit;
        
    }

    if($Recs->action == 'update'){
        $dbcon->beginTransaction();
        
        $sql = "UPDATE recs SET rec_date=:rec_date, str_id=:str_id, price_total=:price_total, comment=:comment, rec_own=:rec_own WHERE rec_id = :rec_id ;"; 
        $query = $dbcon->prepare($sql);
        $query->bindParam(':rec_date', $Recs->rec_date);
        $query->bindParam(':str_id',$Recs->str_id, PDO::PARAM_INT);
        $query->bindParam(':price_total',$Recs->price_total, PDO::PARAM_STR);
        $query->bindParam(':comment',$Recs->comment, PDO::PARAM_STR);
        $query->bindParam(':rec_own',$rec_own, PDO::PARAM_STR);
        $query->bindParam(':rec_id', $Recs->rec_id, PDO::PARAM_STR);
        $query->execute();  
        
        $i = 0;
        $sql = "DELETE FROM rec_lists WHERE rec_id = $Recs->rec_id";
        $dbcon->exec($sql);
        $Rec_lists = $data->Rec_lists;
        foreach($Rec_lists as $rls){
            if($Rec_lists[$i]->pro_id != ''){
                
                    $sql = "INSERT INTO rec_lists(rec_id, pro_id, pro_name, unit_name, qua, price_one, price, rec_own) VALUE(:rec_id, :pro_id, :pro_name, :unit_name, :qua, :price_one, :price, :rec_own);";        
                    $query = $dbcon->prepare($sql);
                    $query->bindParam(':rec_id', $Recs->rec_id, PDO::PARAM_INT);
                    $query->bindParam(':pro_id', $Rec_lists[$i]->pro_id, PDO::PARAM_INT);
                    $query->bindParam(':pro_name', $Rec_lists[$i]->pro_name, PDO::PARAM_STR);
                    $query->bindParam(':unit_name', $Rec_lists[$i]->unit_name, PDO::PARAM_STR);
                    $query->bindParam(':qua', $Rec_lists[$i]->qua, PDO::PARAM_INT);
                    $query->bindParam(':price_one', $Rec_lists[$i]->price_one, PDO::PARAM_STR);
                    $query->bindParam(':price', $Rec_lists[$i]->price, PDO::PARAM_STR);
                    $query->bindParam(':rec_own', $rec_own, PDO::PARAM_STR);
                    $query->execute();  
                
            }
            $i++ ;
        }
        
        // echo "เพิ่มข้อมูลเรียบร้อย ok";
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'massege' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $Recs));
        $dbcon->commit();
        exit;
    }
    if($Recs->action == 'delete'){    
        $dbcon->beginTransaction();
        $sql = "DELETE FROM recs WHERE rec_id = $Recs->rec_id";
        $dbcon->exec($sql);

        $sql = "DELETE FROM rec_lists WHERE rec_id = $Recs->rec_id";
        $dbcon->exec($sql);

        $dbcon->commit();
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'massege' => 'Record deleted successfully'));  
        
    }    

}catch(PDOException $e){
    if ($dbcon->inTransaction()) {
        $dbcon->rollback();
        // If we got here our two data updates are not in the database
    }

    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


