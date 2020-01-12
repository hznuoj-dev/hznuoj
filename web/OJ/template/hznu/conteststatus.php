<?php
/**
* This file is created
* by yybird
* @2016.03.24
* last modified
* by yybird
* @2016.03.24
**/
?>

<?php $title="Contest Status";?>
<?php include "contest_header.php" ?>

<?php

function generate_url($page){
    global $str2;
    $link = "status.php?";
    $link .= $str2;
    $link .= "&page=".$page;
    return $link;
}


?>

<style type="text/css">
.pp{
    margin-top: 30px;
}
</style>

<div class="am-container pp">
    <!-- 搜索框 start -->
    <table>
        <tr>
            <td>
                <form action="status.php" method="get" class="am-form am-form-inline" role="form">
                    <div class="am-form-group">
                        <input type="text" class="am-form-field" placeholder="Problem ID" name="problem_id" value="<?php echo htmlentities($problem_id)?>">
                    </div>
                    <div class="am-form-group">
                        <input type="text" class="am-form-field" placeholder="User ID" name="user_id" value="<?php echo htmlentities($user_id)?>">
                        <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
                    </div>
                    <div class="am-form-group">
                        <select class="am-round" name="language" data-am-selected="{searchBox: 1, maxHeight: 400}">
                            <?php
                            if (isset($_GET['language'])) $language=$_GET['language'];
                            else $language=-1;
                            if ($language<0||$language>=count($language_name))
                                $language=-1;
                            if ($language==-1)
                                echo "<option value='-1' selected>All</option>";
                            else
                                echo "<option value='-1'>All</option>";
                            $lang_count=count($language_ext);
                            for($i=0 ; $i<$lang_count ; ++$i) {
                                $j = $language_order[$i];
                                if($OJ_LANGMASK & (1<<$j)) {
                                    if ($j==$language)
                                        echo "<option value=$j selected>$language_name[$j]</option>";
                                    else
                                        echo "<option value=$j>$language_name[$j]</option>";
                                }
                            }
                            ?>
                        </select>
                        <span class="am-form-caret"></span>
                    </div>
                    <div class="am-form-group">
                        <select class="am-round" name="jresult" data-am-selected="{btnWidth: '100px'}">
                            <?php
                            if (isset($_GET['jresult']))
                                $jresult_get=intval($_GET['jresult']);
                            else
                                $jresult_get=-1;
                            if ($jresult_get>=12||$jresult_get<0)
                                $jresult_get=-1;
/*if ($jresult_get!=-1){
$sql=$sql."AND `result`='".strval($jresult_get)."' ";
$str2=$str2."&jresult=".strval($jresult_get);
}*/
if ($jresult_get==-1)
    echo "<option value='-1' selected>All</option>";
else
    echo "<option value='-1'>All</option>";
for ($j=0;$j<12;$j++){
    $i=($j+4)%12;
    if ($i==$jresult_get) echo "<option value='".strval($jresult_get)."' selected>".$jresult[$i]."</option>";
    else echo "<option value='".strval($i)."'>".$jresult[$i]."</option>";
}
?>
</select>
<span class="am-form-caret"></span>
</div>
<button type="submit" class="am-btn am-btn-secondary"><span class='am-icon-filter'></span> Filter</button>
</form>
</td>
<td>
    <form action="status.php" method="get" class="am-form am-form-inline" role="form" style="float: left;">
        <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
        <button type="submit" class="am-btn am-btn-default">Reset</button>
    </form>
</td>
</tr>
</table>
<!-- 搜索框 start -->

</div>
<div class="am-container">
    <table class="am-table am-table-hover">
        <thead>
            <tr>
                <th>Run.ID</th>
                <th>User</th>
                <th>Prob.ID</th>
                <th>Result</th>
                <th>Memory</th>
                <th>Time</th>
                <th>Language</th>
                <th>Code Length</th>
                <th>Submit Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($view_status as $row){
                echo "<tr>";
                foreach($row as $table_cell){
                    echo "<td>";
                    echo $table_cell;
                    echo "</td>";
                }

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- 不用了 -->
<!-- <div class="am-container am-u-sm-centered am-u-sm-offset-10 am-u-sm-2">
    <ul class="am-pagination">
        <?php echo "<li><a href=\"status.php?".htmlentities($str2)."\">Top</a></li>&nbsp;&nbsp;";
        if (isset($_GET['prevtop']))
            echo "<li><a href=\"status.php?".htmlentities($str2)."&top=".intval($_GET['prevtop'])."\">&laquo; Previous</a></li>&nbsp;&nbsp;";
        else
            echo "<li><a href=\"status.php?".htmlentities($str2)."&top=".($top+20)."\">&laquo; Previous</a></li>&nbsp;&nbsp;";
        echo "<li><a href=\"status.php?".htmlentities($str2)."&top=".$bottom."&prevtop=$top\">Next &raquo;</a></li>";
        ?>
    </ul>
</div> -->

<?php if ($view_total_page > 1): ?>
<!-- 页标签 start -->
<div class="am-g">
    <ul class="am-pagination am-text-center">
        <?php
            $show_page = array();
            array_push($show_page, 1);
            array_push($show_page, $view_total_page);
            if ($page != $view_total_page && $page != 1) {
                array_push($show_page, $page);
            }
            $bit = 1;
            $current_page = $page;
            while (1) {
                $current_page -= $bit;
                if ($current_page > 1) {
                    array_push($show_page, $current_page);
                } else {
                    break;
                }
                $bit <<= 1;
            }

            $bit = 1;
            $current_page = $page;
            while (1) {
                $current_page += $bit;
                if ($current_page < $view_total_page) {
                    array_push($show_page, $current_page);
                } else {
                    break;
                }
                $bit <<= 1;
            }
            sort($show_page);
            foreach ($show_page as $i) {
                $link=generate_url($i);
                if($page == $i) 
                    echo "<li class='am-active'><a href=\"$link\">{$i}</a></li>";
                else
                    echo "<li><a href=\"$link\">{$i}</a></li>";
            }
        ?>
    </ul>
</div>
<!-- 页标签 end -->
<?php endif ?>


<?php include "footer.php" ?>