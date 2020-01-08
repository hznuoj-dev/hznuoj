<?php
/**
* This file is created
* by yybird
* @2016.03.22
* last modified
* by yybird
* @2016.04.15
**/
?>

<?php 
$title="Ranklist";
require_once("header.php"); 
//比赛中禁用页面
if (!HAS_PRI('enter_admin_page') && $OJ_FORBIDDEN) {
    $view_errors = "The page is temporarily closed!";
    return require_once("error.php");
}
?>

<?php
function generate_url($page){
    $link = "ranklist.php?page=".$page;
    $link .= "&".$filter_url;
    return $link;
}
?>

<style>
.am-form-inline > .am-form-group {
    margin-left: 15px;
}
.am-form-inline {
    margin-bottom: 1.5rem;
}
</style>
<div class='am-container'>
    <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
        <ul class="am-nav am-nav-tabs">
            <li><a href="/OJ/problemset.php">Problems</a></li>
            <li><a href="/OJ/status.php">Status</a></li>
            <li class="am-active"><a href="/OJ/ranklist.php">Standings</a></li>
        </ul>
    </div>
    <div class='am-g'>
        <!-- 用户查找 start -->
        <div class='am-u-md-6'>
            <form class="am-form am-form-inline" action='userinfo.php'>
                <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b">
                <div class='am-form-group'>
                    <select data-am-selected="{searchBox: 1, maxHeight: 400}" id='class' style='width:110px'>
                        <option value='all' <?php if (isset($_GET['class']) && $_GET['class']=="" || !isset($_GET['class'])) echo "selected"; ?>>全部</option>
                        <?php
                        foreach($classSet as $class) {
                            $selected = "";
                            $class=substr($class, 5);
                            if (isset($_GET['class']) && $_GET['class']==$class) $selected = "selected";
                            echo "<option value='".$class."' ".$selected.">".$class."</option>";
                        }
                        ?>

                    </select>
                    <!-- 选择班级后自动跳转页面的js代码 start -->
                    <script type="text/javascript">
                        var oSelect=document.getElementById("class");
                        oSelect.onchange=function() { //当选项改变时触发
                            var valOption=this.options[this.selectedIndex].value; //获取option的value
                            var url = window.location.search;
                            var cid = url.substr(url.indexOf('=')+1,4);
                            var url = window.location.pathname+"?class="+valOption;
                            window.location.href = url;
                        }
</script>
<!-- 选择班级后自动跳转页面的js代码 end -->
</div>
<div class="am-form-group am-form-icon">
    <i class="am-icon-search"></i>
    <input type="text" class="am-form-field" placeholder=" &nbsp;Input user ID" name="user">
</div>
<button type="submit" class="am-btn am-btn-warning ">Go</button>
</form>
</div>
<!-- 用户查找 end -->

<!-- 排序模块 start -->
<!--     <div class='am-u-md-6 am-text-right am-text-middle'>
<b>For All:&nbsp</b>
<a href=ranklist.php?order_by=s>Level</a>&nbsp&nbsp&nbsp&nbsp
<b>For HZNU:</b>&nbsp
<a href=ranklist.php?order_by=ac>AC</a>&nbsp&nbsp
<a href=ranklist.php?scope=d>Day</a>&nbsp&nbsp
<a href=ranklist.php?scope=w>Week</a>&nbsp&nbsp
<a href=ranklist.php?scope=m>Month</a>&nbsp&nbsp
<a href=ranklist.php?scope=y>Year</a>&nbsp&nbsp
</div> -->
<!-- 排序模块 end -->

</div>
<div class="am-g" style="color: grey; text-align: center;">

<?php if ($view_total_page > 1): ?>
    <!-- 页标签 start -->
    <div class="am-g">
        <ul class="am-pagination am-text-center">
            <?php
                for ($i = 1; $i <= $view_total_page; $i++) { 
                    $tmp = abs($i - $page + 1);
                    if ($i == 1 || $i == $view_total_page || ($tmp & -$tmp) == $tmp) {
                        $link=generate_url($i);
                        if($page == $i) 
                            echo "<li class='am-active'><a href=\"$link\">{$i}</a></li>";
                        else
                            echo "<li><a href=\"$link\">{$i}</a></li>";
                    }
                }
            ?>
        </ul>
    </div>
    <!-- 页标签 end -->
<?php endif ?>

    <div>
        Standings won't update automatically. Please visit your user info page to update your information.
    </div>
</div>
<div class="am-avg-md-1">
    <table class="am-table am-table-striped">

        <!-- 表头 start -->
        <thead>
            <tr>
                <th class='am-text-center' style='width:5%;' >Rank</th>
                <th class='am-text-center' style='width:20%;' >User</th>
                <th class='am-text-center' style='width:45%;' >Nick</th>
                <th class='am-text-center' style='width:10%;' >Solved</th>
                <th class='am-text-center' style='width:10%;' >Level</th>
                <th class='am-text-center' style='width:10%;' >DouQi</th>
            </tr>
        </thead>
        <!-- 表头 end -->

        <!-- 列出排名 start -->
        <tbody>
            <?php
            foreach($view_rank as $row){
                echo "<tr>";
                foreach($row as $table_cell){
                    echo "<td align='center'>";
                    echo $table_cell;
                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
        <!-- 列出排名 end -->

    </table>
</div>


<?php if ($view_total_page > 1): ?>
<!-- 页标签 start -->
<div class="am-g">
    <ul class="am-pagination am-text-center">
        <?php
            for ($i = 1; $i <= $view_total_page; $i++) { 
                $tmp = abs($i - $page + 1);
                if ($i == 1 || $i == $view_total_page || ($tmp & -$tmp) == $tmp) {
                    $link=generate_url($i);
                    if($page == $i) 
                        echo "<li class='am-active'><a href=\"$link\">{$i}</a></li>";
                    else
                        echo "<li><a href=\"$link\">{$i}</a></li>";
                }
            }
        ?>
    </ul>
</div>
<!-- 页标签 end -->
<?php endif ?>

<?php include "footer.php" ?>
