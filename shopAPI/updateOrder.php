<?php
require '_mysql.php';

class res {
    public $errorCode = 404;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$orderId = $arr_query['orderId'];
$id = $arr_query['id'];
$cnt = $arr_query['cnt'];
$cost = $arr_query['cost'];

$ans = new res();

$sqlStr = 'select Inventory from `Goods` where GNo='.$id.';';
$rs = querySQL($sqlStr);
$r = $rs->fetch_row();

if ($r[0] < $cnt) {
    $ans->errorCode = 1;
    echo json_encode($ans, JSON_UNESCAPED_UNICODE);
    die();
}

$sqlStr = 'update `Goods` set Inventory=('.$r[0].' - '.$cnt.') where GNo='.$id.';';
$rs = querySQL($sqlStr);

$sqlStr = 'insert into `Detail` (ONo, GNo, Count, Cost) values ('.$orderId.', '.$id.', '.$cnt.', '.$cost.');';
$rs = querySQL($sqlStr);

$sqlStr = 'select Cost from `Order` where ONo='.$orderId.';';
$rs = querySQL($sqlStr);
$r = $rs->fetch_row();

$sqlStr = 'update `Order` set Cost=('.$cost.' + '.$r[0].') where ONo='.$orderId.';';
$rs = querySQL($sqlStr);

$ans->errorCode = 0;

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
