<?php
require '_mysql.php';

class u {
    public $id;
    public $gid;
    public $count;
}

class res {
    public $errorCode = 404;
    public $data;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$user = $arr_query['user'];

$sqlStr = 'select * from `Cart` where UNo='.$user.';';
$rs = querySQL($sqlStr);
$ans = new res();
$ans->data = array();
$ans->errorCode = 0;

while ($r = $rs->fetch_row()) {
    $tmp = new u();
    $tmp->id = (int) $r[0];
    $tmp->gid = (int) $r[2];
    $tmp->count = (int) $r[3];
    array_push($ans->data, $tmp);
}

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
