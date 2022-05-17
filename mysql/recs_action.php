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
    
    $rec_own = $data_auth->fullname;    
    
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
        foreach($Rec_lists as $rls){
            if($rls->pro_id != ''){
                $sql = "INSERT INTO rec_lists(rec_id, rec_date, pro_id, pro_name, unit_name, qua, qua_for_ord, price_one, price, rec_own) VALUE(:rec_id, :rec_date, :pro_id, :pro_name, :unit_name, :qua, :qua_for_ord, :price_one, :price, :rec_own);";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':rec_id', $rec_id, PDO::PARAM_INT);
                $query->bindParam(':rec_date', $Recs->rec_date);
                $query->bindParam(':pro_id', $rls->pro_id, PDO::PARAM_INT);
                $query->bindParam(':pro_name', $rls->pro_name, PDO::PARAM_STR);
                $query->bindParam(':unit_name', $rls->unit_name, PDO::PARAM_STR);
                $query->bindParam(':qua', $rls->qua, PDO::PARAM_INT);                
                $query->bindParam(':qua_for_ord', $rls->qua, PDO::PARAM_INT);
                $query->bindParam(':price_one', $rls->price_one, PDO::PARAM_STR);
                $query->bindParam(':price', $rls->price, PDO::PARAM_STR);
                $query->bindParam(':rec_own', $rec_own, PDO::PARAM_STR);
                $query->execute();  
            }
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
            if($rls->pro_id != ''){
                
                    $sql = "INSERT INTO rec_lists(rec_id, rec_date, pro_id, pro_name, unit_name, qua, qua_for_ord, price_one, price, rec_own) VALUE(:rec_id, :rec_date, :pro_id, :pro_name, :unit_name, :qua, :qua_for_ord, :price_one, :price, :rec_own);";        
                    $query = $dbcon->prepare($sql);
                    $query->bindParam(':rec_id', $Recs->rec_id, PDO::PARAM_INT);
                    $query->bindParam(':rec_date', $Recs->rec_date, PDO::PARAM_INT);
                    $query->bindParam(':pro_id', $rls->pro_id, PDO::PARAM_INT);
                    $query->bindParam(':pro_name', $rls->pro_name, PDO::PARAM_STR);
                    $query->bindParam(':unit_name', $rls->unit_name, PDO::PARAM_STR);
                    $query->bindParam(':qua', $rls->qua, PDO::PARAM_INT);
                    $query->bindParam(':qua_for_ord', $rls->qua, PDO::PARAM_INT);
                    $query->bindParam(':price_one', $rls->price_one, PDO::PARAM_STR);
                    $query->bindParam(':price', $rls->price, PDO::PARAM_STR);
                    $query->bindParam(':rec_own', $rec_own, PDO::PARAM_STR);
                    $query->execute();  
                
            }
            $i++ ;
        }        
        // echo "เพิ่มข้อมูลเรียบร้อย ok";
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'massege' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $data_auth->fullname));
        $dbcon->commit();
        exit;
    }

    if($Recs->action == 'active'){
        $dbcon->beginTransaction();
        
        $sql = "UPDATE recs SET st=1, rec_app=:rec_app WHERE rec_id = :rec_id ;"; 
        $query = $dbcon->prepare($sql);
        $query->bindParam(':rec_app',$rec_own, PDO::PARAM_STR);
        $query->bindParam(':rec_id', $Recs->rec_id, PDO::PARAM_STR);
        $query->execute();  
        
        // $i = 0;
        $Rec_lists = $data->Rec_lists;
        foreach($Rec_lists as $rls){            
                
            $sql = "UPDATE rec_lists SET st=1, rec_app=:rec_app WHERE rec_id = :rec_id ;"; 
            $query = $dbcon->prepare($sql);           
            $query->bindParam(':rec_app', $rec_own, PDO::PARAM_STR);
            $query->bindParam(':rec_id', $Recs->rec_id, PDO::PARAM_INT);
            $query->execute(); 
            

            /** save stock newrrc ดึงข้อมูลสอนค้าในสตอก created_at last  
            /**
             *  stck_id INT(13) AUTO_INCREMENT PRIMARY KEY,
             *   pro_id INT(13) NOT NULL,
             * unit_name
             * price_one VARCHAR(100) NULL,
            *    bf INT(10) NOT NULL,
            *    stck_in INT(10) NULL,
            *    stck_out INT(10) NULL,
            *    bal INT(10) NOT NULL,
            *    rec_ord_id INT(10) NULL,
            *    rec_ord_list_id INT(10) NULL,
            *    comment VARCHAR(250) NULL,
             * 
             */
            $sql = "SELECT * FROM `stock` WHERE pro_id =:pro_id ORDER BY stck_id DESC LIMIT 0,1;";
            $query = $dbcon->prepare($sql);
            $query->bindParam(':pro_id',$rls->pro_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);

            /** ckeck row */
            if(count($result) == 0){
                $bf = 0;
                $stck_in = $rls->qua;
                $stck_out = 0;
                $bal = $rls->qua;                
            }else{
                $bf = $result[0]->bal;
                $stck_in = $rls->qua;
                $stck_out = 0;
                $bal = $result[0]->bal + $rls->qua;
            }

            $sql = "INSERT INTO stock(pro_id, unit_name, price_one, bf, stck_in, stck_out, bal, rec_ord_id, rec_ord_list_id, comment) VALUE (:pro_id, :unit_name, :price_one, :bf, :stck_in, :stck_out, :bal, :rec_ord_id, :rec_ord_list_id, :comment)";
            $query = $dbcon->prepare($sql); 
            $query->bindParam(':pro_id',$rls->pro_id, PDO::PARAM_INT);
            $query->bindParam(':unit_name',$rls->unit_name, PDO::PARAM_STR);
            $query->bindParam(':price_one',$rls->price_one, PDO::PARAM_STR);
            $query->bindParam(':bf',$bf);
            $query->bindParam(':stck_in',$stck_in, PDO::PARAM_INT);
            $query->bindParam(':stck_out',$stck_out);
            $query->bindParam(':bal',$bal, PDO::PARAM_INT);
            $query->bindParam(':rec_ord_id',$rls->rec_id, PDO::PARAM_INT);
            $query->bindParam(':rec_ord_list_id',$rls->rec_list_id, PDO::PARAM_INT);
            $query->bindParam(':comment',$Recs->comment, PDO::PARAM_STR);
            $query->execute();

            /** set products->insock */
            $sql = "SELECT * FROM `products` WHERE pro_id =:pro_id LIMIT 0,1;";
            $query = $dbcon->prepare($sql);
            $query->bindParam(':pro_id',$rls->pro_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);

            if(count($result) > 0){


                $instock = $bal;
                $sql = "UPDATE products SET instock=:instock WHERE pro_id =:pro_id;";
                $query = $dbcon->prepare($sql);
                $query->bindParam(':instock',$instock, PDO::PARAM_INT);
                $query->bindParam(':pro_id',$rls->pro_id, PDO::PARAM_INT);
                $query->execute();
            }
            
        //     $i++ ;
        }        
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'massege' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $result));
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


