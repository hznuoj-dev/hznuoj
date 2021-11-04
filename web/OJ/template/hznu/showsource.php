<?php
/**
* This file is created
* by yybird
* @2016.05.24
* last modified
* by yybird
* @2016.05.25
**/
?>

<?php 
if (is_numeric($cid) && !isset($_GET['normal_mod'])) {
    $_GET['cid']=$cid;
    require_once "contest_header.php";
}
else require_once "header.php";
require_once("include/const.inc.php");

//比赛中禁用页面
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
if (!HAS_PRI('enter_admin_page') && $OJ_FORBIDDEN) {
    $view_errors = "The page is temporarily closed!";
    return require_once("error.php");
}
?>

<div class="am-container">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <style type="text/css">
        .solution-info {
            display: inline-block;
            margin: 5px;
        }
    </style>
    <?php
    if ($ok==true){
        $res_class="danger";
        $time="-";
        $memory="-";
if($sresult==4){//AC
    $res_class="success";
    $time=$stime."ms";
    $memory=$smemory."kB";
}
else if($sresult<=3){//pending or rejudging or compling or judging
    $res_class="default";
}

echo "<hr>";
echo "<div class='am-text-center'>";
echo "<div class='solution-info'>";
echo "Problem_ID: ";
if (is_numeric($cid)){
    $p_lable=PID($num);
    echo "<span class='am-badge am-badge-secondary am-text-sm'><a href='problem.php?cid=$cid&pid=$num' style='color: white;'>$p_lable</a>";
}
else echo "<span class='am-badge am-badge-primary am-text-sm'><a href='problem.php?id=$pid' style='color: white;'>$pid</a>";
echo "</span>";
echo "</div>";
$html_sup = "";
$html_link = "";
if($is_temp_user) {
    $html_sup = "<sup title='this is a temporary user in a special contest'><a href=\"/OJ/contest.php?cid=$cid\" style='color: white;'>$cid</a></sup>";
}
else {
    $html_link = "href=\"/OJ/userinfo.php?user=$suser_id\"";
}
echo <<<HTML
<div class='solution-info'>
Result: <span class='am-badge am-badge-$res_class am-text-sm'>$judge_result[$sresult]</span>
</div>
<div class='solution-info'>
Time: <span class='am-badge am-badge-warning am-text-sm'>$time</span>
</div>
<div class='solution-info'>
Memory: <span class='am-badge am-badge-warning am-text-sm'>$memory</span>
</div>
<div class='solution-info'>
Author: <span class='am-badge am-badge-primary am-text-sm'>
<a $html_link style='color: white;'>$suser_id</a>{$html_sup}
</span>
</div>
HTML;
if($cid) {
    echo <<<HTML
    <div class='solution-info'>
    In contest: <span class='am-badge am-badge-primary am-text-sm'>
    <a href="contest.php?cid=$cid" style='color: white;'>$cid</a>
    </span>
    </div>
HTML;
}
$copy_source = htmlentities($view_source);
echo "<button type = \"button\" class=\"sample-copy-btn am-btn am-radius am-btn-link am-btn-xs\" data-clipboard-text = \"{$copy_source}\"style = \"height:20px;margin-left:30px;margin-top:-4px;padding-top:2px;padding-left: 8px;padding-right: 8px;\">复制代码</button>";
echo <<<HTML
</div>
<hr>
HTML;

$brush=strtolower($language_name[$slanguage]);
if ($brush=='pascal') $brush='delphi';
if ($brush=='obj-c') $brush='c';
if ($brush=='freebasic') $brush='vb';
if ($brush=='swift') $brush='csharp';
echo "<pre style='background-color: transparent;'><code style='background-color: transparent;'>";
echo htmlentities(str_replace("\r\n","\n",$view_source),ENT_QUOTES,"utf-8");
echo "</code></pre>";
} else {
    echo "<div am-text-center><h2>I am sorry, You could not view this code!</h2></div>";
}
?>
</div>
</div> <!-- /container -->

<?php require_once "footer.php" ?>
<!-- highlight.js START-->
<link href="/OJ/plugins/highlight/styles/github-gist.css" rel="stylesheet">
<script src="/OJ/plugins/highlight/highlight.pack.js"></script>
<script src="/OJ/plugins/highlight/highlightjs-line-numbers.min.js"></script>
<style type="text/css">
    .hljs-line-numbers {
        text-align: right;
        border-right: 1px solid #ccc;
        color: #999;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>

<script src="/OJ/plugins/clipboard/dist/clipboard.min.js"></script>
<script>
    $(document).ready(function(){
        var clipboard = new ClipboardJS('.sample-copy-btn');
        clipboard.on('success', function(e) {
            e.clearSelection();
        });
        clipboard.on('error', function(e) {
        });
    });
</script>   

<script>
    hljs.initHighlightingOnLoad();
    hljs.initLineNumbersOnLoad();
</script>
<!-- highlight.js END-->