<?php
require '_mysql.php';

class u {
    public $id;
    public $user;
    public $admin;
}

class res {
    public $errorCode = 404;
    public $data;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$user = $arr_query['user'];
$pwd = $arr_query['pwd'];

$sqlStr = 'select * from User where UName = "'.$user.'";';
$rs = querySQL($sqlStr);
$ans = new res();

if ($r = $rs->fetch_row()) {
    $ans->errorCode = 200;
} else {
    $sqlStr = 'insert into User(UName, Admin, Password) Values ("'.$user.'", 0, "'.$pwd.'");';
    $rs = querySQL($sqlStr);

    $sqlStr = 'select * from User where UName = "'.$user.'";';
    $rs = querySQL($sqlStr);
    $r = $rs->fetch_row();

    $ans->errorCode = 0;
    $ans->data = new u();
    $ans->data->id = (int) $r[0];
    $ans->data->user = $r[1];
    $ans->data->Admin = false;
}
echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
