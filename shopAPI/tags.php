<?php
require '_mysql.php';

class u {
    public $id;
    public $tag;
    public $work;
}

class res {
    public $errorCode = 404;
    public $data;
}

$sqlStr = 'select * from Tag;';
$rs = querySQL($sqlStr);
$ans = new res();
$ans->data = array();
$ans->errorCode = 0;

while ($r = $rs->fetch_row()) {
    $tmp = new u();
    $tmp->id = (int) $r[0];
    $tmp->tag = $r[1];
    $tmp->work = $r[2] == 1;
    array_push($ans->data, $tmp);
}

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
