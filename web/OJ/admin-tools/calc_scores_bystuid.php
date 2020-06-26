<?php
//通过学号获取系统分数
?>

<?php
	return;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="/OJ/plugins/AmazeUI/css/amazeui.min.css"/>
</head>
<body>
    <form action="" method="POST">
    stuidList:
    <textarea name="stuidList" rows="15">
    <?php
        if (isset($_POST['stuidList'])) {
            echo $_POST['stuidList'];
        } else {
            echo "2017212212001";
        }
    ?></textarea>
    time:
    <input name="time" rows="15" value = <?php
        if (isset($_POST['time'])) {
            echo "'".$_POST['time']."'";
        } else {
            echo "2020-01-08&nbsp;22:30:00";
        }
    ?>>
    </input>
        <button>submit</button>
    </form>
    <br><br><br>
<?php


if(isset($_POST['stuidList']) && isset($_POST['time'])) {
    require_once('../include/db_info.inc.php');
    require_once("../include/const.inc.php");
    require_once("../include/my_func.inc.php");

    $stuidList=explode("\r\n",trim($_POST['stuidList']));
    $time = trim($_POST['time']);

    echo <<<HTML
        <table class='am-table am-table-bordered am-table-striped' style='word-break:keep-all;'>
        <tr>
        <th>学号</th>
        <th>姓名</th>
        <th>班级</th>
        <th>账号</th>
        <th>题数</th><th>题量(0.4)</th><th>难度(0.2)</th><th>活跃(0.2)</th><th>独立(0.2)</th><th>总分</th>
        <th>100%雷同</th>
        <th>>90%雷同</th>
        </tr>
HTML;

    foreach ($stuidList as $stuid) {
        $stuid = $mysqli->real_escape_string($stuid);
        echo "<tr>";
        $sql = <<<SQL
            SELECT user_id FROM users 
            WHERE stu_id = '$stuid' 
            AND solved >= (
                SELECT MAX(solved) FROM users
                WHERE stu_id = '$stuid')
SQL;

        $res = $mysqli->query($sql) or die($mysqli->error);
        if ($res->num_rows == 0) {
            echo <<<HTML
                <td>$stuid</td>
                <td>NULL</td>
                <td>NULL</td>
                <td>NULL</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>          
                </tr>
HTML;
            continue;
        } else {
            $user_id = $res->fetch_object()->user_id;
            $user_mysql = $mysqli->real_escape_string($user_id);

            $sql="SELECT level,color,strength,real_name,class,stu_id,solved FROM `users` WHERE `user_id`='$user_mysql'";
            $res=$mysqli->query($sql) or die($mysqli->error);
            $row=$res->fetch_object();
            $real_name = $row->real_name;
            $stu_id=$row->stu_id;
            $class = $row->class;
            $strength = $row->strength;
            $total_ac = $row->solved;
            
            /* 计算图表相关信息 start */
            // 计算总解题量的解题分
            $sql = "SELECT MAX(solved) FROM users";
            $res = $mysqli->query($sql);
            $row = $res->fetch_array();
            $max_solved = intval($row[0]);
            $solved_score = round(100.0*$total_ac/$max_solved); // 解题分
            
            // 计算平均难度分
            if ($total_ac == 0) {
                $dif_score = 0;
            } else {
                $dif_score = round(1.0*$strength/$total_ac);
            }
            
            $delta = 100;
            // 计算活跃度分
            $sql = <<<SQL
                SELECT COUNT(DISTINCT DATE_FORMAT(in_date, '%Y-%m-%d')) AS cnt
                FROM solution
                WHERE user_id = '$user_mysql'
                AND in_date >= DATE_ADD('$time', interval -$delta day)
                AND in_date <= '$time'
                AND result = 4
SQL;
            $res = $mysqli->query($sql) or die($mysqli->error);
            $AC_day = 0;
            if ($res->num_rows > 0) {
                $AC_day = $res->fetch_object()->cnt;
            }

            $act_score = round(100.0 * $AC_day / $delta);

            // 计算抄袭分
            // 获取该用户所有AC的提交
            $sql = <<<SQL
                SELECT SUM(sim) AS sum,
                COUNT(*) AS cnt
                FROM sim
                RIGHT JOIN (
                    SELECT solution_id FROM solution 
                    WHERE user_id = '$user_mysql'
                    AND result = 4
                    AND in_date <= '$time') AS s
                ON sim.s_id = s.solution_id
SQL;
            // echo $sql;
            $res = $mysqli->query($sql) or die($mysqli->error);
            $copy_sum = 0;
            $AC_num = 0;
            if ($res->num_rows > 0) {
                $row = $res->fetch_object();
                $copy_sum = $row->sum;
                $AC_num = $row->cnt;
            }
            if ($AC_num) $idp_score = 100-round(1.0*$copy_sum/$AC_num); 
            else $idp_score = 0;

            //计算雷同率=100的数量
            $sql = <<<SQL
                SELECT COUNT(sim) AS cnt
                FROM 
                sim, solution
                WHERE sim = 100
                AND solution.user_id = '$user_mysql'
                AND sim.s_id = solution.solution_id
                AND result = 4
SQL;
            // echo $sql;
            $copy_100 = 0;
            $res = $mysqli->query($sql) or die($mysqli->error);
            if ($res->num_rows > 0) {
                $row = $res->fetch_object();
                $copy_100 = $row->cnt;
            }

            //计算雷同率[90, 100)的数量
            $sql = <<<SQL
                SELECT COUNT(sim) AS cnt
                FROM 
                sim, solution
                WHERE 
                sim >= 90
                AND solution.user_id = '$user_mysql'
                AND sim.s_id = solution.solution_id
                AND result = 4
SQL;
            $copy_90 = 0;
            $res = $mysqli->query($sql) or die($mysqli->error);
            if ($res->num_rows > 0) {
                $row = $res->fetch_object();
                $copy_90 = $row->cnt;
            }

            // 计算总分
            $avg_score = round($solved_score*0.4+$dif_score*0.2+$act_score*0.2+$idp_score*0.2);
            /* 计算图表相关信息 end */

            echo <<<HTML
                <td>$stuid</td>
                <td>$real_name</td>
                <td>$class</td>
                <td>$user_id</td>
                <td>$total_ac</td>
                <td>$solved_score</td>
                <td>$dif_score</td>
                <td>$act_score</td>
                <td>$idp_score</td>
                <td>$avg_score</td>
                <td>$copy_100</td>
                <td>$copy_90</td>          
                </tr>
HTML;
        }
    }
    echo "</table>";
    echo "DONE!";
}
?>


</body>
</html>



