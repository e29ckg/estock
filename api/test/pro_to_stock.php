<?php
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

try{
    /*ดึงข้อมูลทั้งหมด*/
    // rec
    // $dbcon->beginTransaction();
    $data = array();

    $sql = "SELECT * FROM `products` ORDER BY pro_id ASC LIMIT 0,10;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $res_pros = $query->fetchAll(PDO::FETCH_OBJ);

    
    $into_stock =array();
    $ord_own = 'administrator';
    $comment = '';
    
    foreach($res_pros as $res_pro){
        $pro_id = $res_pro->pro_id;
        $pro_name = $res_pro->pro_name;
        $unit_name = $res_pro->unit_name;

        
        
        $sql = "SELECT * FROM `ord_lists` WHERE pro_id=$pro_id ;";
        $query = $dbcon->prepare($sql);
        $query->execute();
        $res_ord_lists = $query->fetchAll(PDO::FETCH_OBJ);
        $qua = 0;
        $instock = 0;
        
        foreach($res_ord_lists as $rols){
            
            $sql = "SELECT * FROM `stock` WHERE pro_id=$pro_id ORDER BY stck_id DESC LIMIT 1;";
            $query = $dbcon->prepare($sql);
            $query->execute();
            $res_stock = $query->fetchAll(PDO::FETCH_OBJ);
            $instock = $res_stock[0]->bal;

            if($rols->qua > $instock){
                
                // $sql = "SELECT * FROM `rec_lists` WHERE pro_id=$pro_id AND qua_for_ord > 0;";
                // $query = $dbcon->prepare($sql);
                // $query->execute();
                // $res_rec_lists = $query->fetchAll(PDO::FETCH_OBJ);

                // $sql = "INSERT INTO stock(pro_id, unit_name, price_one, bf, stck_in, stck_out, bal, rec_ord_id, rec_ord_list_id, comment) VALUE (:pro_id, :unit_name, :price_one, :bf, :stck_in, :stck_out, :bal, :rec_ord_id, :rec_ord_list_id, :comment)";
                // $query = $dbcon->prepare($sql); 
                // $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
                // $query->bindParam(':unit_name',$unit_name, PDO::PARAM_STR);
                // $query->bindParam(':price_one',$price_one, PDO::PARAM_STR);
                // $query->bindParam(':bf',$bf);
                // $query->bindParam(':stck_in',$stck_in, PDO::PARAM_INT);
                // $query->bindParam(':stck_out',$stck_out);
                // $query->bindParam(':bal',$bal, PDO::PARAM_INT);
                // $query->bindParam(':rec_ord_id',$rec_ord_id, PDO::PARAM_INT);
                // $query->bindParam(':rec_ord_list_id',$rec_ord_list_id, PDO::PARAM_INT);
                // $query->bindParam(':comment',$comment, PDO::PARAM_STR);
                // $query->execute();

            }else{


            }
            
        }

        array_push($data,array(
            "pro_name"=>$pro_name,
            "instock"=>$instock,
            "qua"=>$qua,
        ));
        
        /** นำเข้า RECS */
        
        // $sql = "SELECT * FROM `rec_lists` WHERE pro_id=$pro_id ;";
        // $query = $dbcon->prepare($sql);;
        // $query->execute();
        // $res_rec_lists = $query->fetchAll(PDO::FETCH_OBJ);
        
        // foreach($res_rec_lists as $rrl){
        //     $price_one = $rrl->price_one;

        //     $rec_ord_id = $rrl->rec_id;
        //     $rec_ord_list_id = $rrl->rec_list_id;
            
        //     /** intostock */
            
        //     $sql = "SELECT * FROM stock WHERE stock.pro_id =:pro_id ORDER BY stck_id DESC LIMIT 1";
        //     $query = $dbcon->prepare($sql);
        //     $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
        //     $query->execute();
        //     $res_stock = $query->fetchAll(PDO::FETCH_OBJ);
            
            
        //     if($res_stock){
        //         $bf = $res_stock[0]->bal;
        //         $stck_in = $rrl->qua;
        //         $stck_out = 0;
        //         $bal = $bf + $stck_in ;  
        //         // array_push($data,$res_stock);
        //     }else{
        //         $bf = 0;
        //         $stck_in = $rrl->qua;
        //         $stck_out = 0;
        //         $bal = $rrl->qua;   

        //     }
            

        //     $sql = "INSERT INTO stock(pro_id, unit_name, price_one, bf, stck_in, stck_out, bal, rec_ord_id, rec_ord_list_id, comment) VALUE (:pro_id, :unit_name, :price_one, :bf, :stck_in, :stck_out, :bal, :rec_ord_id, :rec_ord_list_id, :comment)";
        //     $query = $dbcon->prepare($sql); 
        //     $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
        //     $query->bindParam(':unit_name',$unit_name, PDO::PARAM_STR);
        //     $query->bindParam(':price_one',$price_one, PDO::PARAM_STR);
        //     $query->bindParam(':bf',$bf);
        //     $query->bindParam(':stck_in',$stck_in, PDO::PARAM_INT);
        //     $query->bindParam(':stck_out',$stck_out);
        //     $query->bindParam(':bal',$bal, PDO::PARAM_INT);
        //     $query->bindParam(':rec_ord_id',$rec_ord_id, PDO::PARAM_INT);
        //     $query->bindParam(':rec_ord_list_id',$rec_ord_list_id, PDO::PARAM_INT);
        //     $query->bindParam(':comment',$comment, PDO::PARAM_STR);
        //     $query->execute();

        // }





        /*****             เบิกออก     */



        

    }
         
        
        // $i = 0;
               
    // $dbcon->commit();

    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'message' =>  'Ok',
        'data' =>  $data,
        // 'res_pros' =>  $res_pros,
        // 'Ord_lists' =>  $Ord_lists,
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}
