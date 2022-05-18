<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

$data = json_decode(file_get_contents("php://input"));
$year = $data->year;
$year = (int)$year - 543;

// $year_1 = $year - 1;
// $date_start = $year_1 ."/10/01";;
// $date_start = date_create($date_start);

$date_end = $year ."/09/30";
// $date_end = date_create($date_end);
$date_end = date("Y-m-d", strtotime($date_end));
/** ปีงบประมาณ 2565 เรื่ม 1 ตุลาคม 2564 ถึง 30 กันยายน 2565  */
// $date_start = date("Y-m-d");
// $date_end = date("Y-m-d H:i:s");
$year_thai = $year + 543;
$text_head = "ณ วันที่ 30 กันยายน $year_thai ประจำปีงบประมาณ $year_thai ";


try{
    $datas = array();

    /*ดึงข้อมูลทั้งหมด*/
    $sql = "SELECT * FROM catalogs ORDER BY cat_sort ASC;";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $result_cat = $query->fetchAll(PDO::FETCH_OBJ);
    $i=1;
    $no = 0;
    $price_all = 0;
    foreach($result_cat as $rc){

        $sql = "SELECT rec_lists.rec_date, rec_lists.pro_id, rec_lists.unit_name, rec_lists.qua_for_ord, rec_lists.price_one, products.pro_name, products.cat_name, rec_lists.updated_at 
                FROM rec_lists INNER JOIN products ON rec_lists.pro_id = products.pro_id 
                WHERE products.cat_name = '$rc->cat_name' AND rec_lists.qua_for_ord > 0 AND rec_lists.st = 1 AND rec_lists.updated_at < '$date_end'
                ORDER BY products.pro_name ASC;";
        $query = $dbcon->prepare($sql);
        $query->execute();
        $result_rec_lists = $query->fetchAll(PDO::FETCH_OBJ);
        $lists = array();
        
        $pro_id_for_no_old = '';
        $pro_id_for_no_new = '';
        foreach($result_rec_lists as $rl){
            $pro_id_for_no_new = $rl->pro_id;
            // if($pro_id_for_no_new == $pro_id_for_no_old){
            //     $no = '';
            // }else{
                $no = $i++;
                
            // }
            $pro_id_for_no_old = $pro_id_for_no_new;

            $price = $rl->qua_for_ord * (float)$rl->price_one;
            array_push($lists,array(
                "no" => $no,
                "rec_date" => $rl->rec_date,
                "pro_id" => $rl->pro_id,
                "pro_name" => $rl->pro_name,
                "cat_name" => $rl->cat_name,
                "unit_name" => $rl->unit_name,
                "qua_for_ord" => $rl->qua_for_ord,
                "price_one" => $rl->price_one,
                "price" => $price,
                "updated_at" => $rl->updated_at,
            ));   
            $price_all = $price_all + $price;
        }
        array_push($datas,array(
            "cat_name" => $rc->cat_name,
            "lists" => $lists
        ));

    }
    
    
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'massege' =>  'Ok', 
        'respJSON' => $datas,
        'price_all' => $price_all,
        'year' => $year,
        'text_head' => $text_head,
        'date_end' => $date_end,
    ));

}catch(PDOException $e){
    echo "Faild to connect to database" . $e->getMessage();
    http_response_code(400);
    echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
}