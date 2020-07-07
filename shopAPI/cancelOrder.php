<?php
require '_mysql.php';

class res {
    public $errorCode = 404;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$id = $arr_query['id'];

$sqlStr = 'select GNo, Count from `Detail` where ONo='.$id.';';
$rs = querySQL($sqlStr);
while ($r = $rs->fetch_row()) {
    $sqlStr = 'select Inventory from `Goods` where GNo='.$r[0].';';
    $rst = querySQL($sqlStr);
    $rt = $rst->fetch_row();
    $sqlStr = 'update `Goods` set Inventory=('.$rt[0].' + '.$r[1].') where GNo='.$r[0].';';
    $rst = querySQL($sqlStr);
}

$sqlStr = 'update `Order` set status = 3 where ONo='.$id.';';
$rs = querySQL($sqlStr);
$ans = new res();
$ans->errorCode = 0;

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
