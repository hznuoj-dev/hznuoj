<?php require("admin-header.php");

if (!HAS_PRI("set_dailydetails")) {
	echo "Permission denied!";
	exit(1);
}
?>

<?php if(isset($_POST['update'])){
    $start_year = $_POST['start_year'];
    $start_month = $_POST['start_month'];
    $start_day = $_POST['start_day'];
    $start_date = "$start_year-$start_month-$start_day";

    $end_year = $_POST['end_year'];
    $end_month = $_POST['end_month'];
    $end_day = $_POST['end_day'];
    $end_date = "$end_year-$end_month-$end_day";

    if(strtotime($start_date) > strtotime($end_date)){
        echo "时间不合法";
        exit(1);
    }

    $sql = "UPDATE dailydetails SET start_time = '$start_date', end_time = '$end_date'";
    $mysqli->query($sql);
}
?>

<?php 
    $now_year = date("Y");
    $display_year_cnt = 7;
    
    $sql = "SELECT * FROM dailydetails";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();

    $start_date = new DateTime($row['start_time']);
    $end_date = new DateTime($row['end_time']);

    $select_start_year = $start_date->format('Y');
    $select_start_month = $start_date->format('m');
    $select_start_day = $start_date->format('d');

    $select_end_year = $end_date->format('Y');
    $select_end_month = $end_date->format('m');
    $select_end_day = $end_date->format('d');
?>

<form action = "set_dailydetails.php" method=post>
  <label for="start_year" style = "font-size: 18px;">开始时间</label>
  <label for="start_year" style = "font-size: 18px; margin-left: 20px;">年:</label>
  <select id="start_year" name="start_year" style="width: 80px; height: 35px; text-align: center; line-height: 15px; font-size: 17px;">
    <?php
        //往后一年开始
        for($i = $now_year + 1; $i >= $now_year + 1 - $display_year_cnt + 1; $i--){
            if($i == $select_start_year) echo "<option value='$i' selected>";
            else echo "<option value='$i'>";
            echo $i;
            echo "</option>";
        }
    ?>
  </select>

  <label for="start_month" style = "font-size: 18px; margin-left: 20px;">月:</label>
  <select id="start_month" name="start_month" style="width: 80px; height: 35px; text-align: center; line-height: 15px; font-size: 17px;">
    <?php
        for($i = 1; $i <= 12; $i++){
            if($i == $select_start_month) echo "<option value='$i' selected>";
            else echo "<option value='$i'>";
            echo $i;
            echo "</option>";
        }
    ?>
  </select>

  <label for="start_day" style = "font-size: 18px; margin-left: 20px;">日:</label>
  <select id="start_day" name="start_day" style="width: 80px; height: 35px; text-align: center; line-height: 15px; font-size: 17px;">
    <?php
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $select_start_month, $select_start_year);
        for($i = 1; $i <= $daysInMonth; $i++){
            if($i == $select_start_day) echo "<option value='$i' selected>";
            else echo "<option value='$i'>";
            echo $i;
            echo "</option>";
        }
    ?>
  </select>

  <h1></h1>

  <label for="end_year" style = "font-size: 18px;">结束时间</label>
  <label for="end_year" style = "font-size: 18px; margin-left: 20px;">年:</label>
  <select id="end_year" name="end_year" style="width: 80px; height: 35px; text-align: center; line-height: 15px; font-size: 17px;">
    <?php
        //往后一年开始
        for($i = $now_year + 1; $i >= $now_year + 1 - $display_year_cnt + 1; $i--){
            if($i == $select_end_year) echo "<option value='$i' selected>";
            else echo "<option value='$i'>";
            echo $i;
            echo "</option>";
        }
    ?>
  </select>

  <label for="end_month" style = "font-size: 18px; margin-left: 20px;">月:</label>
  <select id="end_month" name="end_month" style="width: 80px; height: 35px; text-align: center; line-height: 15px; font-size: 17px;">
    <?php
        for($i = 1; $i <= 12; $i++){
            if($i == $select_end_month) echo "<option value='$i' selected>";
            else echo "<option value='$i'>";
            echo $i;
            echo "</option>";
        }
    ?>
  </select>

  <label for="end_day" style = "font-size: 18px; margin-left: 20px;">日:</label>
  <select id="end_day" name="end_day" style="width: 80px; height: 35px; text-align: center; line-height: 15px; font-size: 17px;">
    <?php
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $select_end_month, $select_end_year);
        for($i = 1; $i <= $daysInMonth; $i++){
            if($i == $select_end_day) echo "<option value='$i' selected>";
            else echo "<option value='$i'>";
            echo $i;
            echo "</option>";
        }
    ?>
  </select>

  <h1></h1>

  <button type=submit name="update" class="btn btn-default", style = "width: 90px; height: 45px;text-align: center; line-height: 15px; font-size: 17px;">update</button>

</form>

<?php 
  require_once("admin-footer.php")
?>

<script type="text/javascript">
document.getElementById('start_year').onchange = update_start_days;
document.getElementById('start_month').onchange = update_start_days;
document.getElementById('end_year').onchange = update_end_days;
document.getElementById('end_month').onchange = update_end_days;

function update_start_days() {
    var start_year = document.getElementById('start_year').value;
    var start_month = document.getElementById('start_month').value;
    var daySelect = document.getElementById('start_day');
    var daysInMonth = new Date(start_year, start_month, 0).getDate();
    daySelect.innerHTML = '';
    for (var i = 1; i <= daysInMonth; i++) {
        var option = document.createElement('option');
        option.value = i;
        option.text = i;
        daySelect.appendChild(option);
    }
}

function update_end_days() {
    var end_year = document.getElementById('end_year').value;
    var end_month = document.getElementById('end_month').value;
    var daySelect = document.getElementById('end_day');
    var daysInMonth = new Date(end_year, end_month, 0).getDate();
    daySelect.innerHTML = '';
    for (var i = 1; i <= daysInMonth; i++) {
        var option = document.createElement('option');
        option.value = i;
        option.text = i;
        daySelect.appendChild(option);
    }
}
</script>