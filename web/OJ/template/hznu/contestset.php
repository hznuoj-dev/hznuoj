<?php
    /**
    * This file is created
    * by yybird
    * @2016.03.22
    * last modified
    * by yybird
    * @2016.03.23
    **/
?>

<?php 
    $title="ContestSet";
    require_once("header.php");

function generate_url($page){
    global $search; 
    $link = "contestset.php?";
    $link .= "page=".$page;
    $link .= "&search=".$search;
    return $link;
}
?>

<div class="am-container">

<!--   
<div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
<ul class="am-nav am-nav-tabs">
<li class="am-active"><a href="/OJ/contest.php">Local</a></li>
<li><a href="/OJ/recent-contest.php">Remote</a></li>
</ul>
</div>
 -->

<div class="am-avg-md-1">


    <!-- 通过关键词查找 start -->
    <div class='am-g' style = "width:35%;padding-top:20px;">
        <form class="am-form am-form-horizontal">
            <div class="am-u-sm-9">
                <div class="am-form-group am-form-icon">
                    <i class="am-icon-binoculars"></i>
                    <input type="text" class="am-form-field" placeholder=" &nbsp;Keywords" name="search" value="<?php echo $search ?>">
                </div>
            </div>
            <button type="submit" class="am-u-sm-3 am-btn am-btn-secondary ">Search</button>
        </form>
    </div>
    <!-- 通过关键词查找 end -->

    <?php if ($view_total_page > 1): ?>
    <!-- 页标签 start -->
    <div class="am-g">
        <ul class="am-pagination am-text-center">
            <?php
                for ($i = 1; $i <= $view_total_page; $i++){
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

    <table class="am-table am-table-hover" style = "margin-top:0px;">
        <thead>
            <th class='am-text-center'>ID</th>
            <th class='am-text-center'>Name</th>
            <th class='am-text-center'>Start</th>
            <th class='am-text-center'>End</th>
            <th class='am-text-center'>Status</th>
            <th class='am-text-center'>Type</th>
        </thead>
        <tbody>
            <?php
                foreach($view_contest as $row) {
                    echo "<tr class = 'am-text-center'>";
                    foreach ($row as $table_cell) {
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

<?php if ($view_total_page > 1): ?>
<!-- 页标签 start -->
<div class="am-g">
    <ul class="am-pagination am-text-center">
        <?php
            for ($i = 1; $i <= $view_total_page; $i++){
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



<?php 
    require_once("footer.php");
 ?>
