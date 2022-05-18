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
$Ord = $data->Ord[0];

$ord_own = '';

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

// http_response_code(200);
// echo json_encode(array('status' => 'success', 'message' => 'เพิ่มข้อมูลเรียบร้อย', 'responseJSON' => $Ord->str_id ));
// die; 
empty($Ord->ord_date) ? $ord_date = date("Y-m-d h:s:i") : $ord_date = $Ord->ord_date;
try{
    $jwt = $arr[1];
    $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']); 
    $data_auth = $decoded->data;
    
    $ord_own = $data_auth->fullname;    
    
    if($Ord->action == 'insert'){
        $dbcon->beginTransaction();
        $ord_id = time();
        
        $sql = "INSERT INTO ords(ord_id, ord_date, ord_own, comment) VALUE(:ord_id, :ord_date, :ord_own, :comment);";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':ord_id', $ord_id,PDO::PARAM_INT);
        $query->bindParam(':ord_date', $ord_date);
        $query->bindParam(':ord_own',$ord_own, PDO::PARAM_STR);
        $query->bindParam(':comment',$Ord->comment, PDO::PARAM_STR);
        $query->execute();  
        
        $Ord_lists = $data->Ord_lists;
        foreach($Ord_lists as $ord_l){
            if($ord_l->pro_id != '' && $ord_l->qua != 0  && $ord_l->qua != ''){
                $sql = "INSERT INTO ord_lists(ord_id, pro_id, pro_name, unit_name, qua, ord_own) VALUE(:ord_id, :pro_id, :pro_name, :unit_name, :qua, :ord_own);";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':ord_id', $ord_id, PDO::PARAM_INT);
                $query->bindParam(':pro_id', $ord_l->pro_id, PDO::PARAM_INT);
                $query->bindParam(':pro_name', $ord_l->pro_name, PDO::PARAM_STR);
                $query->bindParam(':unit_name', $ord_l->unit_name, PDO::PARAM_STR);
                $query->bindParam(':qua', $ord_l->qua, PDO::PARAM_INT);
                $query->bindParam(':ord_own', $ord_own, PDO::PARAM_STR);
                $query->execute();  
            }
        }
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'message' => 'เพิ่มข้อมูลเรียบร้อย ok', 'responseJSON' => $Ord_lists));

        $dbcon->commit();
        exit;
        
    }

    if($Ord->action == 'update'){
        $dbcon->beginTransaction();
        
        $sql = "UPDATE ords SET ord_date=:ord_date, comment=:comment, ord_own=:ord_own WHERE ord_id = :ord_id ;"; 
        $query = $dbcon->prepare($sql);
        $query->bindParam(':ord_date', $ord_date);
        $query->bindParam(':comment',$Ord->comment, PDO::PARAM_STR);
        $query->bindParam(':ord_own',$ord_own, PDO::PARAM_STR);
        $query->bindParam(':ord_id', $Ord->ord_id, PDO::PARAM_STR);
        $query->execute();  
        
        $sql = "DELETE FROM ord_lists WHERE ord_id = $Ord->ord_id";
        $dbcon->exec($sql);
        $Ord_lists = $data->Ord_lists;
        foreach($Ord_lists as $ord_l){
            if($ord_l->pro_id != '' && $ord_l->qua != 0  && $ord_l->qua != ''){                
                $sql = "INSERT INTO Ord_lists(ord_id, pro_id, pro_name, unit_name, qua, ord_own) VALUE(:ord_id, :pro_id, :pro_name, :unit_name, :qua, :ord_own);";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':ord_id', $Ord->ord_id, PDO::PARAM_INT);
                $query->bindParam(':pro_id', $ord_l->pro_id, PDO::PARAM_INT);
                $query->bindParam(':pro_name', $ord_l->pro_name, PDO::PARAM_STR);
                $query->bindParam(':unit_name', $ord_l->unit_name, PDO::PARAM_STR);
                $query->bindParam(':qua', $ord_l->qua, PDO::PARAM_INT);
                $query->bindParam(':ord_own', $ord_own, PDO::PARAM_STR);
                $query->execute();  
                
            }
    //         $i++ ;
        }        
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'message' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => $data_auth->fullname));
        $dbcon->commit();
        exit;
    }

    if($Ord->action == 'active'){
        $dbcon->beginTransaction();
        
        $sql = "UPDATE ords SET st=1, ord_app=:ord_app, ord_pay_own=:ord_pay_own WHERE ord_id = :ord_id ;"; 
        $query = $dbcon->prepare($sql);
        $query->bindParam(':ord_app',$ord_own, PDO::PARAM_STR);
        $query->bindParam(':ord_pay_own', $ord_own, PDO::PARAM_STR);
        $query->bindParam(':ord_id', $Ord->ord_id, PDO::PARAM_STR);
        $query->execute();  
        
        $Ord_lists = $data->Ord_lists;        

        foreach($Ord_lists as $ord_l){
            
            $qua = $ord_l->qua;         //  3 จำนวนที่ขอเบิก
            $pro_id = $ord_l->pro_id;   //  1 

            $price_one  = '';           //  หาจาก rec_lists
            $rec_id     = 0 ;           //  หาจาก rec_lists

            $bf         = 0;
            $stck_in    = 0;
            $stck_out   = 0;
            $bal        = 0;

            $qua_pay    = 0;            // จำนวนที่จ่ายได้

            $unit_name =  $ord_l->unit_name;
            $rec_ord_id =  $ord_l->ord_id;
            $rec_ord_list_id = $ord_l->ord_list_id;
            $comment = $Ord->comment;

            if($qua > 0 ){
                /** rec_lists  หาลอดตัด */
                $sql = "SELECT * FROM `rec_lists` WHERE pro_id =:pro_id AND qua_for_ord > 0 ORDER BY rec_list_id ASC ";
                $query = $dbcon->prepare($sql);
                $query->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
                $query->execute();
                $rep_rec_lists = $query->fetchAll(PDO::FETCH_OBJ);

                $instock = 0;
                $instock_qua = $qua;

                $product_instock = 0;
                foreach($rep_rec_lists as $rep_r){
                    $product_instock = $product_instock + $rep_r->qua_for_ord;
                }

                // if($product_instock > $qua){ /*** $product_instock > $qua  */

                    foreach($rep_rec_lists as $rrl){
                        $instock = $instock + $rrl->qua_for_ord; 
    
                        if($qua > 0){
    
                            $rec_list_id = $rrl->rec_list_id;
                            /** เลือก stock pro_id ล่าสุด */
                            $sql = "SELECT * FROM stock WHERE pro_id = :pro_id ORDER BY stck_id DESC LIMIT 0,1;"; 
                            $query = $dbcon->prepare($sql);           
                            $query->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
                            $query->execute(); 
                            $rep_stck_desc = $query->fetchAll(PDO::FETCH_OBJ);
        
                            $bf     = $rep_stck_desc[0]->bal;
                            $qua_for_ord = $rrl->qua_for_ord;
                                   // หน้าจำนวนรวมที่สามารถเบิกได้ ใน rec_list 
        
                            if($qua_for_ord < $qua  ){                      //  qf 1   3     3 - 1  ออก  1  เหลือ 2
                                $stck_out   = $rrl->qua_for_ord;            //  1
                                $qua        = $qua - $rrl->qua_for_ord ;     //  Q = 2   เหลือต้องเบิกอีก 3-1
        
                                $price_one  = $rrl->price_one;              //  ราคาของ lot นี้ rec_lists
                                $rec_id     = $rrl->rec_id;
        
                                $stck_in    = 0;
                                $stck_out   = $stck_out;
                                $bal        = $bf - $stck_out;              // 
                                $qua_for_ord = $qua_for_ord - $stck_out;    //  เหลือ rec_list
                                
        
                            }elseif($qua_for_ord > $qua){                   //  10   Q 8   
                                $stck_out   =   $qua;                       //  8
                                $qua        =   0;                          //  Q = 0   เหลือต้องเบิกอีก
        
                                $price_one  =   $rrl->price_one;              //  ราคาของ lot นี้ rec_lists
                                $rec_id     =   $rrl->rec_id;
        
                                $stck_in    =   0;
                                $stck_out   =   $stck_out;
                                $bal        =   $bf - $stck_out;             //  2
                                $qua_for_ord =  $qua_for_ord - $stck_out;   //  2   เหลือ rec_list
                                
        
                            }elseif($qua_for_ord == $qua){                  // 3    3   ออก     3   เหลือ    0
                                $stck_out   =   $qua;                       //  ออก 3
                                $qua        =   0;                          //  Q = 0   เหลือต้องเบิกอีก
        
                                $price_one  = $rrl->price_one;              //  ราคาของ lot นี้ rec_lists
                                $rec_id     = $rrl->rec_id;
        
                                $stck_in    = 0;
                                $stck_out   = $stck_out;
                                $bal        = $bf - $stck_out;;              // 
                                $qua_for_ord = $qua_for_ord - $stck_out;    //  เหลือ rec_list                            
        
                            }
                            $qua_pay = $qua_pay + $stck_out;
                            /** บันทึกรายการ ลง stock */
                            $sql = "INSERT INTO stock(pro_id, unit_name, price_one, bf, stck_in, stck_out, bal, rec_ord_id, rec_ord_list_id, comment) VALUE (:pro_id, :unit_name, :price_one, :bf, :stck_in, :stck_out, :bal, :rec_ord_id, :rec_ord_list_id, :comment)";
                            $query = $dbcon->prepare($sql); 
                            $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
                            $query->bindParam(':unit_name',$unit_name, PDO::PARAM_STR);
                            $query->bindParam(':price_one',$price_one, PDO::PARAM_STR);
                            $query->bindParam(':bf',$bf, PDO::PARAM_INT);
                            $query->bindParam(':stck_in', $stck_in,PDO::PARAM_INT);
                            $query->bindParam(':stck_out',$stck_out,PDO::PARAM_INT);
                            $query->bindParam(':bal',$bal, PDO::PARAM_INT);
                            $query->bindParam(':rec_ord_id',$rec_ord_id, PDO::PARAM_INT);
                            $query->bindParam(':rec_ord_list_id',$rec_ord_list_id, PDO::PARAM_INT);
                            $query->bindParam(':comment',$comment, PDO::PARAM_STR);
                            $query->execute();
                            
                            /** ตัด rec_list ที่หัก */
                            $sql = "UPDATE rec_lists SET qua_for_ord=:qua_for_ord WHERE rec_list_id = :rec_list_id;"; 
                            $query = $dbcon->prepare($sql); 
                            $query->bindParam(':qua_for_ord',$qua_for_ord, PDO::PARAM_INT);
                            $query->bindParam(':rec_list_id',$rec_list_id, PDO::PARAM_INT);
                            $query->execute();
        
                            $bf = $bal;     //bal -> fb ยอดยกไป รอบต่อไป  
                        }
                        
                        
                        $instock = $bal;
                        

                        /**     ปรับ products instock */
                        $sql = "UPDATE products SET instock=:instock WHERE pro_id = :pro_id;";
                        $query = $dbcon->prepare($sql); 
                        $query->bindParam(':instock',$instock, PDO::PARAM_INT);
                        $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
                        $query->execute();
    
                    } /**foreach */

                // }/*** $product_instock > $qua  */

                
            } /** Qua > 0 */
            
            $sql = "UPDATE ord_lists SET st=1, ord_app=:ord_app, qua_pay=:qua_pay WHERE ord_list_id = :ord_list_id ;"; 
            $query = $dbcon->prepare($sql);           
            $query->bindParam(':ord_app', $ord_own, PDO::PARAM_STR);
            $query->bindParam(':qua_pay', $qua_pay, PDO::PARAM_INT);
            $query->bindParam(':ord_list_id', $rec_ord_list_id, PDO::PARAM_INT);
            $query->execute();
            /**  order_list st = 1 */
            
        } /**foreach($Ord_lists as $ord_l)            */  
             
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'message' => 'บันทึกข้อมูลเรียบร้อย ok', 'responseJSON' => ''));
        $dbcon->commit();
        exit;
    }

    if($Ord->action == 'delete'){    
        $dbcon->beginTransaction();
        $sql = "DELETE FROM ords WHERE ord_id = $Ord->ord_id";
        $dbcon->exec($sql);

        $sql = "DELETE FROM Ord_lists WHERE ord_id = $Ord->ord_id";
        $dbcon->exec($sql);

        $dbcon->commit();
        http_response_code(200);
        echo json_encode(array('status' => 'success', 'message' => 'Record deleted successfully'));  
        
    }    

}catch(PDOException $e){
    if ($dbcon->inTransaction()) {
        $dbcon->rollback();
        // If we got here our two data updates are not in the database
    }

    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}


