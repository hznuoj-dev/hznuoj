<?php
$TEAM_MODE = true;
?>

<?php
//return;
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
    cid:
    <input name="cid" rows="15" value = <?php
        if (isset($_POST['cid'])) {
            echo "'".$_POST['cid']."'";
        } else {
            echo "1359";
        }
    ?>>
    </input>
        <button>submit</button>
    </form>
    <br><br><br>



<?php


if (!isset($_POST['stuidList']) || !isset($_POST['cid'])) return;
$stuidList=explode("\r\n",trim($_POST['stuidList']));
$cid = intval($_POST['cid']);

require_once('../include/db_info.inc.php');
require_once("../include/const.inc.php");
require_once("../include/my_func.inc.php");

    echo <<<HTML
        <table class='am-table am-table-bordered am-table-striped' style='word-break:keep-all;'>
        <tr>
        <th>学号</th>
        <th>姓名</th>
        <th>班级</th>
        <th>账号</th>
        <th>Score</th>
        <th>Solved</th>
        <th>Penlty</th>
        <th>Rank</th>
        <th>Rank_Score</th>
        </tr>
HTML;


class TM {
    var $solved=0;
    var $time=0;
    var $score;
    var $p_wa_num;
    var $p_ac_sec;
    var $is_unknown;
    var $user_id;
    var $nick;
    var $real_name;
    var $stu_id;
    var $class;
    var $try_after_lock;
    function TM(){
        $this->score = 0;
        $this->solved=0;
        $this->time=0;
        $this->try_after_lock=array();
        $this->p_wa_num=array(0);
        $this->p_ac_sec=array(0);
        $this->is_unknown=array(0);
    }
    function Add($pid,$sec,$res){
        global $problem_score;
        if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0) return;
        if ($res==-1){ //Try times after locking
            $this->try_after_lock[$pid]++;
        }
        if ($res<=3){ //unknown status
            $this->is_unknown[$pid]=true;
        }
        if ($res!=4){
            //未知、CE、PE不算罚时
            if($res!=-1 && $res != 11 && $res != 5){
                if(isset($this->p_wa_num[$pid])){
                    $this->p_wa_num[$pid]++;
                }
                else{
                    $this->p_wa_num[$pid]=1;
                }
            }
        } else { // AC
            $this->p_ac_sec[$pid]=$sec;
            $this->solved++;
            $this->score += $problem_score[$pid];
            if(!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid]=0;
            $this->time+=$sec+$this->p_wa_num[$pid]*1200;
        }
    }
}

function s_cmp($A,$B){
    if ($A->score!=$B->score) return $A->score<$B->score;
    else if ($A->solved!=$B->solved) return $A->solved<$B->solved;
    else return $A->time>$B->time;
}

$sql = <<<SQL
    SELECT start_time FROM contest
    WHERE contest_id = '$cid';
SQL;

$res = $mysqli->query($sql) or die($mysqli->error); 
$row = $res->fetch_object();
$start_time = strtotime($row->start_time);


//get problem score list
$sql = <<<SQL
    SELECT
    	num, score 
    FROM
    	contest_problem 
    WHERE
    	contest_id = '$cid'
    ORDER BY
    	num
SQL;

$problem_score = array();
$res = $mysqli->query($sql);
while($row = $res->fetch_array()) {
    $problem_score[$row['num']] = intval($row['score']);
}

$U = array();
$user_cnt = 0;
foreach ($stuidList as $stuid) {
    $stuid = trim($stuid);
    $user_cnt++;
    $U[$user_cnt - 1] = new TM();
    $stuid = $mysqli->real_escape_string($stuid);
    $sql = <<<SQL
        SELECT DISTINCT user_id, stu_id, class, real_name FROM (
        SELECT 
        solution.user_id AS user_id,
        solution.contest_id AS contest_id,
        users.stu_id AS stu_id,
        users.class AS class,
        users.real_name AS real_name
        FROM solution
        LEFT JOIN users
        ON solution.user_id = users.user_id) AS solution
        WHERE contest_id = '$cid'
        AND stu_id = '$stuid';
SQL;

    if ($TEAM_MODE) {
        $sql = <<<SQL
            SELECT DISTINCT user_id, stu_id, class, real_name FROM (
            SELECT 
            solution.user_id AS user_id,
            solution.contest_id AS contest_id,
            team.stu_id AS stu_id,
            team.class AS class,
            team.real_name AS real_name
            FROM solution
            LEFT JOIN team
            ON solution.user_id = team.user_id) AS solution
            WHERE contest_id = '$cid'
            AND stu_id = '$stuid';
SQL;
    }

    $res = $mysqli->query($sql) or die($mysqli->error);
    if ($res->num_rows == 0) {
        $U[$user_cnt - 1]->user_id = "NULL";
        $U[$user_cnt - 1]->real_name = "NULL";
        $U[$user_cnt - 1]->stu_id = $stuid;
        $U[$user_cnt - 1]->class = "NULL";
    } else {
        $row = $res->fetch_object();
        $U[$user_cnt - 1]->user_id = $row->user_id;
        $U[$user_cnt - 1]->real_name = $row->real_name;
        $U[$user_cnt - 1]->stu_id = $row->stu_id;
        $U[$user_cnt - 1]->class = $row->class;
        $sql = <<<SQL
            SELECT num, in_date, result FROM solution
            WHERE solution.contest_id = '$cid'
            AND num >= 0 
            AND user_id = '$row->user_id'
            ORDER BY in_date
SQL;
        $res = $mysqli->query($sql) or die($mysqli->error);
        while ($row = $res->fetch_object()) {
            $U[$user_cnt - 1]->Add($row->num, strtotime($row->in_date) - $start_time, intval($row->result)); 
        }
    }
}
usort($U,"s_cmp");

$need = count($stuidList);
$done = 0;
foreach($stuidList as $stuid) {
    $stuid = trim($stuid);
    for ($i = 0; $i < $user_cnt; ++$i) {
        $rank = $i + 1;
        $rank_score = max(70, 100 - $i);
        if ($U[$i]->stu_id == $stuid) {
            ++$done;
            echo <<<HTML
                <tr>
                <td>{$U[$i]->stu_id}</td>
                <td>{$U[$i]->real_name}</td>
                <td>{$U[$i]->class}</td>
                <td>{$U[$i]->user_id}</td>
                <td>{$U[$i]->score}</td>
                <td>{$U[$i]->solved}</td>
                <td>{$U[$i]->time}</td>
                <td>$rank</td>
                <td>$rank_score</td>
                </tr>
HTML;
            break;
        }
    }
}

echo "</table>";
echo "need: ".$need;
echo "<br>";
echo "done: ".$done;
echo "<br>"; 
echo "DONE!";
?>


</body>
</html>
