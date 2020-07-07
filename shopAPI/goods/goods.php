<?php
require '../_mysql.php';

class u {
    public $id;
    public $name;
    public $price;
    public $tag;
    public $img;
    public $inventory;
}

class res {
    public $errorCode = 404;
    public $data;
}

$sqlStr = 'select * from Goods;';
$rs = querySQL($sqlStr);
$ans = new res();
$ans->data = array();
$ans->errorCode = 0;

while ($r = $rs->fetch_row()) {
    $tmp = new u();
    $tmp->id = (int) $r[0];
    $tmp->name = $r[1];
    $tmp->price = (float) $r[2];
    $tmp->tag = $r[3] == "" ? array() : explode(';', $r[3]);
    array_walk($tmp->tag, 'parseInt');
    $tmp->img = $r[4];
    $tmp->inventory = (int) $r[5];
    array_push($ans->data, $tmp);
}

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
