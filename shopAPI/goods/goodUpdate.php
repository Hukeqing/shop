<?php
require '../_mysql.php';

class res {
    public $errorCode = 404;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$id = $arr_query['id'];
$name = $arr_query['name'];
$tag = $arr_query['tag'];
$img = $arr_query['img'];
$price = $arr_query['price'];

$sqlStr = 'update Goods set GName = "'.$name.'", Price = '.$price.', Tags = "'.$tag.'", Img = "'.$img.'" where GNo = '.$id.';';
$rs = querySQL($sqlStr);
$ans = new res();
$ans->errorCode = 0;

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
