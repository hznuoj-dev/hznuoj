<?php require("admin-header.php");

if (!HAS_PRI("set_more_settings")) {
    echo "Permission denied!";
    exit(1);
}
?>

<?php
if (isset($_POST['update_daily_details'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (strtotime($start_date) > strtotime($end_date)) {
        echo "时间不合法";
        exit(1);
    }

    $sql = "UPDATE more_settings SET start_time = '$start_date', end_time = '$end_date'";
    $mysqli->query($sql);
}
if (isset($_POST['update_ai_module'])) {
    $ai_module = isset($_POST['ai_module']) ? 1 : 0;
    $sql = "UPDATE more_settings SET ai_model = $ai_module";
    $mysqli->query($sql);
}
?>

<?php
$now_year = date("Y");
$display_year_cnt = 7;

$sql = "SELECT * FROM more_settings";
$res = $mysqli->query($sql);
$row = $res->fetch_assoc();

$start_date = date("Y-m-d", strtotime($row['start_time']));
$end_date = date("Y-m-d", strtotime($row['end_time']));
$ai_module = $row['ai_model'];
?>
<style>
    .form-group-warp {
        margin: 20px;
        padding: 20px;
        background-color: rgb(223, 223, 223);
    }

    .am-form-group {
        display: flex !important;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 10px;

        label {
            font-size: 16px;
            line-height: 16px;
            margin: 0;
        }
    }
</style>
<div class="form-group-warp">
    <form class="am-form am-form-inline" action='more_settings.php' method="POST" style="display: flex; gap: 20px;margin: 0;">
        <div class="am-form-group">
            <label for="start_date">开始日期:</label>
            <input type="date" style="width: 150px;" name="start_date" value="<?php echo $start_date; ?>">
        </div>
        <div class="am-form-group">
            <label for="end_date">结束日期:</label>
            <input type="date" style="width: 150px;" name="end_date" value="<?php echo $end_date; ?>">
        </div>
        <button type="submit" name="update_daily_details" class="am-btn am-btn-primary">更新</button>
    </form>
</div>
<div class="form-group-warp">
    <form class="am-form am-form-inline" action='more_settings.php' method="POST" style="display: flex; gap: 20px;margin: 0;">
        <div class="am-form-group">
            <label for="start_date">是否开放AI模块:</label>
            <input type="checkbox" name="ai_module" style="width: 16px; height: 16px;" <?php echo $ai_module ? 'checked' : ''; ?>>
        </div>
        <button type="submit" name="update_ai_module" class="am-btn am-btn-primary">更新</button>
    </form>
</div>
<?php
require_once("admin-footer.php")
?>
