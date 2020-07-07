<?php
require '_mysql.php';

class res {
    public $errorCode = 1;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$user = $arr_query['user'];
$id = $arr_query['id'];
$cnt = $arr_query['cnt'];

$sqlStr = 'select CNo, Count from Cart where GNo='.$id.' and UNo='.$user.';';
$rs = querySQL($sqlStr);
$ans = new res();

if ($r = $rs->fetch_row()) {
    $sqlStr = 'update Cart set Count='.((int) $cnt + (int) $r[1]).' where CNo='.$r[0].';';
    $rs = querySQL($sqlStr);
    $ans->errorCode = 0;
} else {
    $sqlStr = 'insert into Cart (UNo, GNo, Count) values ('.$user.', '.$id.', '.$cnt.');';
    $rs = querySQL($sqlStr);
    $ans->errorCode = 0;
}

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
