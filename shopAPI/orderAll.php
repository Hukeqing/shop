<?php
require '_mysql.php';

class u {
    public $id;
    public $totalCost;
    public $status;
    public $time;
}

class res {
    public $errorCode = 404;
    public $data;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$user = $arr_query['user'];

$sqlStr = 'select * from `Order`';
$rs = querySQL($sqlStr);
$ans = new res();
$ans->data = array();
$ans->errorCode = 0;

while ($r = $rs->fetch_row()) {
    $tmp = new u();
    $tmp->id = (int) $r[0];
    $tmp->totalCost = (float) $r[2];
    $tmp->time = $r[3];
    $tmp->status = (int) $r[4];
    array_push($ans->data, $tmp);
}

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
