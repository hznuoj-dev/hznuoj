<?php
$title = "Show all keywords ";
$cache_time=90;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/my_func.inc.php");
$view_title= "Source Code"; 

/////////////////////////Template
include_once "keywords_sum_show.php";
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

