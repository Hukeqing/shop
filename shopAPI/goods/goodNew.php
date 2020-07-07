<?php
require '../_mysql.php';

class res {
    public $errorCode = 404;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$name = $arr_query['name'];
$tag = $arr_query['tag'];
$img = $arr_query['img'];
$price = $arr_query['price'];

$sqlStr = 'insert Goods (GName, Price, Tags, Img, Inventory) value ("'.$name.'", '.$price.', "'.$tag.'", "'.$img.'", 0);';
$rs = querySQL($sqlStr);
$ans = new res();
$ans->errorCode = 0;

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
