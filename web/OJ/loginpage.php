<?php
echo "test";
$cache_time=1;
require_once('./include/cache_start.php');
require_once("./include/db_info.inc.php");
require_once("./include/setlang.php");
$view_title= "LOGIN";

if (isset($_SESSION['user_id'])){
    $view_errors = "<a href=logout.php>Please logout First!</a>";
    return require_once("template/hznu/error.php");
}

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/loginpage.php");
/////////////////////////Common foot


if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

