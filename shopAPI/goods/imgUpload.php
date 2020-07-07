<?php
header("Access-Control-Allow-Origin: *");
header("Content-type: text/html; charset=utf-8");

class res {
    public $errorCode = 404;
    public $url;
}

$hash = "CR-";
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$newchars = str_split($chars);
shuffle($newchars);
$chars_key = array_rand($newchars, 15);
for($i = 0; $i < 15; $i++){
    $fnstr .= $newchars[$chars_key[$i]];
}
$ans = new res();

if ($_FILES["file"]["error"] != 0) {
    $ans->errorCode = 1;
} else {
    if (file_exists("./upload/" . $_FILES["file"]["name"])) {
        $ans->errorCode = 1;
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], 'upload/'.$fnstr)) {
            $ans->errorCode = 0;
            $ans->url = 'http://119.3.172.223/vue/shopAPI/goods/upload/'.$fnstr;
        } else {
            $ans->errorCode = 1;
        }
    }
}

echo json_encode($ans, JSON_UNESCAPED_UNICODE);
?>