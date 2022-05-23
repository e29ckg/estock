<?php
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

$data = array();

try{
    $sql = "SELECT * FROM recs ORDER BY rec_date,rec_id ASC;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $res_recs = $query->fetchAll(PDO::FETCH_OBJ);

    foreach($res_recs as $rr){
        $rec_date = $rr->rec_date;
        $rec_id = $rr->rec_id;

        $rec_date = date_create($rec_date);
        $rec_date = date_format($rec_date,"Y-m-d");

        $sql = "UPDATE stock SET created_at='$rec_date' WHERE rec_ord_id = $rec_id ;"; 
        $query = $dbcon->prepare($sql);           
        $query->execute(); 

        array_push($data,array(
            $rec_id => $rec_date . "->>ok"
        ));
    }

    $sql = "SELECT * FROM ords ORDER BY ord_date,ord_id ASC;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $res_ords = $query->fetchAll(PDO::FETCH_OBJ);

    foreach($res_ords as $ro){
        $ord_date = $ro->ord_date;
        $ord_id = $ro->ord_id;

        $ord_date = date_create($ord_date);
        $ord_date = date_format($ord_date,"Y-m-d");

        $sql = "UPDATE stock SET created_at='$ord_date' WHERE rec_ord_id = $ord_id ;"; 
        $query = $dbcon->prepare($sql);           
        $query->execute(); 

        array_push($data,array(
            $ord_id => $ord_date . " ->> ok"
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

