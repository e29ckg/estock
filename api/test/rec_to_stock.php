<?php
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

try{
    
    $sql = "TRUNCATE TABLE stock;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    
    /*ดึงข้อมูลทั้งหมด*/
    // rec
    $sql = "SELECT * FROM recs ORDER BY rec_date,rec_id ASC;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $res_ords = $query->fetchAll(PDO::FETCH_OBJ);

    $data = array();
    $into_stock =array();
    // rec
    foreach($res_ords as $res_o){
        $rec_id = $res_o->rec_id;
        $rec_date = $res_o->rec_date;
        
        //rec_lists
        $sql = "SELECT * FROM rec_lists WHERE rec_id=$rec_id;";
        $query = $dbcon->prepare($sql);
        $query->execute();
        $res_rec_lists = $query->fetchAll(PDO::FETCH_OBJ);

        foreach($res_rec_lists as $rrl){
            $pro_id = $rrl->pro_id;
            $qua_for_ord = $rrl->qua;
            
            $sql = "UPDATE rec_lists SET qua_for_ord=:qua_for_ord WHERE rec_list_id = :rec_list_id ;"; 
            $query = $dbcon->prepare($sql);           
            $query->bindParam(':qua_for_ord', $qua_for_ord, PDO::PARAM_STR);
            $query->bindParam(':rec_list_id', $rrl->rec_list_id, PDO::PARAM_INT);
            $query->execute(); 

            $sql = "SELECT * FROM stock 
                    WHERE stock.pro_id =:pro_id 
                    ORDER BY stck_id DESC";
            $query = $dbcon->prepare($sql);
            $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);

            $sql = "SELECT * FROM products WHERE pro_id =:pro_id LIMIT 0,1";
            $query = $dbcon->prepare($sql);
            $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
            $query->execute();
            $result_pro = $query->fetchAll(PDO::FETCH_OBJ);
    
            /** ckeck row */
            if(count($result) == 0){
                $bf = 0;
                $stck_in = $rrl->qua;
                $stck_out = 0;
                $bal = $rrl->qua;                
            }else{
                $bf = $result[0]->bal;
                $stck_in = $rrl->qua;
                $stck_out = 0;
                $bal = $result[0]->bal + $rrl->qua;
            }

            $rec_ord_id = $rrl->rec_id;
            $rec_list_id = $rrl->rec_list_id;
            $unit_name = $result_pro[0]->unit_name;
            $price_one = $rrl->price_one;
            $comment = '';

            

            array_push($into_stock,array(
                "pro_id" => $pro_id,
                "unit_name" => $unit_name,
                "price_one" => $price_one,
                "bf" => $bf,
                "stck_in" => $stck_in,
                "stck_out" => $stck_out,
                "bal" => $bal,
                "rec_ord_id" => $rec_ord_id,
                "rec_list_id" => $rec_list_id,
            ));
    
            $sql = "INSERT INTO stock(pro_id, unit_name, price_one, bf, stck_in, stck_out, bal, rec_ord_id, rec_ord_list_id, comment) VALUE (:pro_id, :unit_name, :price_one, :bf, :stck_in, :stck_out, :bal, :rec_ord_id, :rec_ord_list_id, :comment)";
            $query = $dbcon->prepare($sql); 
            $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
            $query->bindParam(':unit_name',$unit_name, PDO::PARAM_STR);
            $query->bindParam(':price_one',$price_one, PDO::PARAM_STR);
            $query->bindParam(':bf',$bf);
            $query->bindParam(':stck_in',$stck_in, PDO::PARAM_INT);
            $query->bindParam(':stck_out',$stck_out);
            $query->bindParam(':bal',$bal, PDO::PARAM_INT);
            $query->bindParam(':rec_ord_id',$rec_ord_id, PDO::PARAM_INT);
            $query->bindParam(':rec_ord_list_id',$rec_list_id, PDO::PARAM_INT);
            $query->bindParam(':comment',$comment, PDO::PARAM_STR);
            $query->execute();
    
            // /** set products->insock */
            $sql = "SELECT * FROM `products` WHERE pro_id =:pro_id LIMIT 0,1;";
            $query = $dbcon->prepare($sql);
            $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);
    
            if(count($result) > 0){    
    
                $instock = $bal;
                $sql = "UPDATE products SET instock=:instock WHERE pro_id =:pro_id;";
                $query = $dbcon->prepare($sql);
                $query->bindParam(':instock',$instock, PDO::PARAM_INT);
                $query->bindParam(':pro_id',$pro_id, PDO::PARAM_INT);
                $query->execute();
            }
        }


        array_push($data,array(
            "into_stock" => $into_stock,
            "result" => $result,
            "res_rec_id" => $rec_id,
            "res_rec_date" => $rec_date,
            "res_rec_lists" => $res_rec_lists,
        ));

    }

    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'message' =>  'Ok', 
        'data' => $data
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}
