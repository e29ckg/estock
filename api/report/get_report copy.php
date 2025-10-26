<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include "../dbconfig.php";

$data = json_decode(file_get_contents("php://input"));
$year = (int)($data->year ?? date("Y")) - 543; // รับปี พ.ศ. แล้วแปลงเป็น ค.ศ.

$date_end = $year . "/09/30";
$date_end = date("Y-m-d", strtotime($date_end));

$year_thai = $year + 543;
$text_head = "ณ วันที่ 30 กันยายน $year_thai ประจำปีงบประมาณ $year_thai ";

try {
    // ✅ Query เดียว + GROUP BY
    $sql = "SELECT 
                c.cat_name,
                p.pro_id,
                p.pro_name,
                r.unit_name,
                SUM(r.qua_for_ord) AS qua_for_ord,
                r.price_one,
                MIN(r.rec_date) AS rec_date,
                MAX(r.updated_at) AS updated_at,
                (SUM(r.qua_for_ord) * r.price_one) AS price
            FROM catalogs c
            JOIN products p ON p.cat_id = c.cat_id
            JOIN rec_lists r ON r.pro_id = p.pro_id
            WHERE r.qua_for_ord > 0
              AND r.st = 1
              AND r.updated_at < :date_end
            GROUP BY c.cat_name, p.pro_id, p.pro_name, r.unit_name, r.price_one
            ORDER BY c.cat_sort ASC, p.pro_name ASC, r.price_one ASC";

    $stmt = $dbcon->prepare($sql);
    $stmt->execute([':date_end' => $date_end]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $datas = [];
    $price_all = 0;

    $seqMap = [];   // map pro_id → ลำดับ
    $seqCounter = 1;

    foreach ($rows as $row) {
        $cat = $row['cat_name'];
        if (!isset($datas[$cat])) {
            $datas[$cat] = [
                "cat_name"     => $cat,
                "price_total"  => 0,
                "count_items"  => 0,
                "lists"        => []
            ];
        }

        // ✅ กำหนดลำดับ
        if (!isset($seqMap[$row['pro_id']])) {
            $seqMap[$row['pro_id']] = $seqCounter++;
        }
        $seq = $seqMap[$row['pro_id']];

        $datas[$cat]["lists"][] = [
            "seq"        => $seq, // ✅ เพิ่มลำดับ
            "rec_date"   => $row['rec_date'],
            "pro_id"     => $row['pro_id'],
            "pro_name"   => $row['pro_name'],
            "unit_name"  => $row['unit_name'],
            "qua_for_ord"=> $row['qua_for_ord'],
            "price_one"  => $row['price_one'],
            "price"      => $row['price'],
            "updated_at" => $row['updated_at']
        ];

        $datas[$cat]["price_total"] += $row['price'];
        $datas[$cat]["count_items"] += 1;
        $price_all += $row['price'];
    }

    $datas = array_values($datas);

    http_response_code(200);
    echo json_encode([
        "status"     => true,
        "message"    => "Ok",
        "respJSON"   => $datas,
        "price_all"  => $price_all,
        "year"       => $year,
        "text_head"  => $text_head,
        "date_end"   => $date_end
    ]);

} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode([
        "status"  => false,
        "message" => "เกิดข้อผิดพลาด.." . $e->getMessage()
    ]);
}