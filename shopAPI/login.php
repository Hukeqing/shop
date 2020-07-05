<?php
header("Content-type: text/html; charset=utf-8");
require '_mysql.php';

class res {
    public $errorCode = 404;
    public $data = {};
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);

$user = $arr_query['user'];
$pwd = $arr_query['pwd'];

$sqlStr = 'select * from account where UName = "'.$user.'";';

$rs = querySQL($sqlStr);
$ans = new res();

if ($r = $rs->fetch_row()) {
    if ($r[3] == $pwd) {
        $ans->errorCode = 0;
        $ans->data->$id = $r[0];
        $ans->data->$user = $r[1];
        $ans->data->$Admin = $r[2] == 1;
    } else {
        $ans->errorCode = 200;
    }
}

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
