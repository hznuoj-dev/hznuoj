<?php $title = "Daily Detail"; ?>
<?php
require_once("header.php");
?>
<style>
    .am-selected {
        width: 12vw;
    }
</style>
<div class="am-avg-md-1" style="width: 90%; margin:5vh auto">
    <form class="am-form am-form-inline" action='student_daily_detail.php'>
        <div class='am-form-group' style="margin-right: 20px;">
            <label for="course_team" style="margin-right: 10px;">Course Team:</label>
            <select data-am-selected="{searchBox: 1, maxHeight: 400}" id='course_team' name='course_team'>
                <?php foreach ($course_team_list as $course_team) {
                    $selected = "";
                    if (isset($_GET['course_team']) && $_GET['course_team'] == $course_team['team_id']) $selected = "selected";
                    echo "<option value='" . $course_team['team_id'] . "' " . $selected . ">" . $course_team['team_name'] . "</option>";
                } ?>
            </select>
        </div>
        <div class='am-form-group' style="margin-right: 20px;">
            <label for="class" style="margin-right: 10px;">Class:</label>
            <select data-am-selected=" {searchBox: 1, maxHeight: 400}" id='class' name='class'>
                <option value='all' <?php if (isset($_GET['class']) && $_GET['class'] == "" || !isset($_GET['class'])) echo "selected"; ?>>All</option>
                <?php
                foreach ($classSet as $class) {
                    $selected = "";
                    if (isset($_GET['class']) && $_GET['class'] == $class) $selected = "selected";
                    echo "<option value='" . $class . "' " . $selected . ">" . $class . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="am-form-group" style="margin-right: 20px;">
            <label for="start_date" style="margin-right:10px;">Begin Date:</label>
            <input type="date" id="start_date" style="display:inline-block;width:10vw;" name="start_date" value="<?php echo $start_date; ?>">
        </div>
        <div class="am-form-group" style="margin-right: 20px;">
            <label for="end_date" style="margin-right: 10px;">End Date:</label>
            <input type="date" style="display:inline-block;width:10vw;" name="end_date" value="<?php echo $end_date; ?>">
        </div>
        <div class="am-form-group" style="margin-right: 20px;">
            <label for="order_by" style="margin-right: 10px;">Order By:</label>
            <select data-am-selected="{searchBox: 1, maxHeight: 400}" name='order_by'>
                <option value="solved" <?php echo ($first_order_by == "solved") ? 'selected' : ''; ?>>过题数</option>
                <option value="submit" <?php echo ($first_order_by == "submit") ? 'selected' : ''; ?>>提交数</option>
                <option value="user_id" <?php echo ($first_order_by == "user_id") ? 'selected' : ''; ?>>学号</option>
                <option value="strength" <?php echo ($first_order_by == "strength") ? 'selected' : ''; ?>>能力值</option>
            </select>
        </div>
        <button type="submit" class="am-btn am-btn-primary">Fliter</button>
    </form>

    <table class="am-table am-table-striped am-margin-top">
        <thead>
            <tr>
                <th class='am-text-center' style='width:1%;'>Rank</th>
                <th class='am-text-center' style='width:10%;'>User</th>
                <th class='am-text-center' style='width:8%;'>Name</th>
                <th class='am-text-center' style='width:2%;'>Solved</th>
                <th class='am-text-center'>Daily</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($view_rank as $row) {
                echo "<tr>";
                foreach ($row as $table_cell) {
                    echo "<td align='center' style='padding:3px'>";
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