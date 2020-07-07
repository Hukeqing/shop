<?php
require '_mysql.php';

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$user = $arr_query['user'];

$sqlStr = 'delete from `Order` where UNo='.$user.' and Cost = 0;';
$rs = querySQL($sqlStr);
?>
