<?php $title = "Daily Detail"; ?>
<?php
require_once("header.php");
?>

<div class="am-avg-md-1" style="width: 90%; margin:5vh auto">
    <form class="am-form am-form-inline" action='student_daily_detail.php'>
        <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b">
        <div class='am-form-group' style="margin-right: 40px;">
            <label for="class" style="margin-right: 10px;">班级:</label>
            <select data-am-selected=" {searchBox: 1, maxHeight: 400}" id='class' name='class' style='width:110px'>
                <option value='all' <?php if (isset($_GET['class']) && $_GET['class'] == "" || !isset($_GET['class'])) echo "selected"; ?>>全部</option>
                <?php
                foreach ($classSet as $class) {
                    $selected = "";
                    $class = substr($class, 5);
                    if (isset($_GET['class']) && $_GET['class'] == $class) $selected = "selected";
                    echo "<option value='" . $class . "' " . $selected . ">" . $class . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="am-form-group" style="margin-right: 40px;">
            <label for="start_date" style="margin-right:10px;">开始日期:</label>
            <input type="date" id="start_date" style="display:inline-block;width:200px;" name="start_date" value="<?php echo $start_date; ?>">
        </div>
        <div class="am-form-group" style="margin-right: 40px;">
            <label for="end_date" style="margin-right: 10px;">结束日期:</label>
            <input type="date" style="display:inline-block;width:200px;" name="end_date" value="<?php echo $end_date; ?>">
        </div>
        <div class="am-form-group" style="margin-right: 40px;">
            <label for="order_by" style="margin-right: 10px;">排序:</label>
            <select data-am-selected="{searchBox: 1, maxHeight: 400}" name='order_by'>
                <option value="solved" <?php echo ($first_order_by == "solved") ? 'selected' : ''; ?>>过题数</option>
                <option value="user_id" <?php echo ($first_order_by == "user_id") ? 'selected' : ''; ?>>学号</option>
                <option value="strength" <?php echo ($first_order_by == "strength") ? 'selected' : ''; ?>>能力值</option>
            </select>
        </div>
        <button type="submit" class="am-btn am-btn-primary">筛选</button>
    </form>

    <table class="am-table am-table-striped am-margin-top">
        <thead>
            <tr>
                <th class='am-text-center' style='width:4%;'>Rank</th>
                <th class='am-text-center' style='width:10%;'>User</th>
                <th class='am-text-center' style='width:10%;'>Name</th>
                <th class='am-text-center' style='width:4%;'>Solved</th>
                <th class='am-text-center'>Daily</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($view_rank as $row) {
                echo "<tr>";
                foreach ($row as $table_cell) {
                    echo "<td align='center'>";
                    echo $table_cell;
                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php require_once("footer.php") ?>
