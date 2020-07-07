<?php
require '_mysql.php';

class res {
    public $errorCode = 404;
}

$arr_query = convertUrlQuery($_SERVER["QUERY_STRING"]);
$id = $arr_query['id'];

$sqlStr = 'delete from Cart where CNo='.$id.';';
$rs = querySQL($sqlStr);

$ans = new res();
$ans->errorCode = 0;

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>
