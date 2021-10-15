<?php
$cache_time=1;
require_once('./include/cache_start.php');
require_once("./include/db_info.inc.php");
require_once("./include/setlang.php");
$view_title= "RESET PASSWORD";

if (isset($_SESSION['user_id'])){
    $view_errors = "<a href=logout.php>Please logout First!</a>";
    return require_once("template/hznu/error.php");
}
if (!isset($_SESSION['tmp_user_id'])) {
    $view_errors= "<a href=./loginpage.php>$MSG_Login</a>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
} 

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/resetPasswordPage.php");
/////////////////////////Common foot


if(file_exists('./include/cache_end.php'))
        require_once('./include/cache_end.php');
?>