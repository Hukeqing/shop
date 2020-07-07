<?php
require '_mysql.php';

class res {
    public $errorCode = 404;
    public $id;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$user = $arr_query['user'];

$sqlStr = 'insert into `Order` (UNo, Cost, Time, Status) values ('.$user.', 0, now(), 0);';
$rs = querySQL($sqlStr);


$sqlStr = 'select max(ONo) from `Order` where UNo='.$user.';';
$rs = querySQL($sqlStr);

$ans = new res();

if ($r = $rs->fetch_row()) {
    $ans->errorCode = 0;
    $ans->id = (int) $r[0];
}

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
