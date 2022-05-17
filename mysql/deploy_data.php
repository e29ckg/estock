<?php
// header("Content-Type: application/json; charset=UTF-8");
include "./dbconfig.php";

// $sql = "SELECT * FROM products"; 
// $query = $dbcon->prepare($sql);
// $query->execute();
// $result = $query->fetchAll(PDO::FETCH_OBJ);

// foreach($result as $rs){
//     echo $rs->pro_name ;

//     $sql = "UPDATE rec_lists
//             SET pro_id=$rs->pro_id
//             WHERE rec_date='$rs->pro_name'; "; 

//     $query = $dbcon->prepare($sql);
//     if($query->execute()){
//         echo ' --> ok <br>';
//     }else{
//         echo 'X <br>';
//     }
// }

$d =date("2021-09-30");
// echo $d;
// $sql = "UPDATE rec_lists
//         SET rec_date='$d' "; 

// $query = $dbcon->prepare($sql);
// if($query->execute()){
//     echo ' --> ok <br>';
// }else{
//     echo 'X <br>';
// }

// $sql = "UPDATE rec_lists
//         SET created_at='$d', updated_at='$d' "; 

// $query = $dbcon->prepare($sql);
// if($query->execute()){
//     echo ' --> ok <br>';
// }else{
//     echo 'X <br>';
// }


$sql = "SELECT * FROM products"; 
$query = $dbcon->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_OBJ);

foreach($result as $rs){
    $pro_id = $rs->pro_id;

    $sql = "SELECT * FROM rec_lists WHERE pro_id=$pro_id"; 
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    $instock = 0;
    foreach($result as $rs){
        $instock = $instock + $rs->qua_for_ord;
    }

    $sql = "UPDATE products
            SET instock=$instock
            WHERE pro_id=$pro_id"; 

    $query = $dbcon->prepare($sql);
    if($query->execute()){
        echo $rs->pro_id.' : '.$rs->pro_name.' ' .$instock.' --> ok <br>';
    }else{
        echo 'X <br>';
    }
}