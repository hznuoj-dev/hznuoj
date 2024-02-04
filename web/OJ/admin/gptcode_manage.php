<?php require("admin-header.php");

if (!HAS_PRI("manage_gptcode")) {
	echo "Permission denied!";
	exit(1);
}
?>

<?php if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	if (isset($_POST['gcid'])){
		$gcid=intval($_POST['gcid']);
		$sql = "SELECT * FROM problem WHERE problem_id='$gcid'";
		$res=$mysqli->query($sql);
		if($res->num_rows == 0){
			echo "该题不存在";
			exit(1);
		}
		else{
      $sql = "SELECT * FROM gpt_code WHERE problem_id='$gcid'";
		  $res=$mysqli->query($sql);
      if($res->num_rows == 0){
        $is_have = 0;
      }
      else{
        $is_have = 1;
      }
      $time = date('Y-m-d H:i:s');

      if($_POST['code'] != ""){
        $code = $_POST['code'];
      }
      else{
        
        //get GPTcode replace '123'
        
        $code = "123";
      }

      if($is_have){
        $sql="UPDATE `gpt_code` 
              SET `code`= '$code' , `last_update_time` = '$time'
              WHERE `problem_id`=".$gcid;
      }
      else{
        $sql = "INSERT INTO gpt_code (`problem_id`, `code`, `last_update_time`)
                VALUES ('$gcid', '$code', '$time');";
      }

      $mysqli->query($sql) or die($mysqli->error);
			$url="../problem.php?id=".$gcid;
			echo "Successful Update GPTcode Problem ".$gcid;
			echo "<script>location.href='$url';</script>";
		}
	}
}
?>

<?php if(isset($_POST['update'])){

	foreach($_POST['pid'] as $i){
		$sql = "SELECT * FROM gpt_code WHERE problem_id='$i'";
		$res=$mysqli->query($sql);

		//get GPT code
			
		$str = "123456";
		$time = date('Y-m-d H:i:s');

		if($res->num_rows){
			$sql = "UPDATE gpt_code
			SET code = $str, last_update_time = '$time'
			WHERE problem_id = $i;";
		}
		else{
			$sql = "INSERT INTO gpt_code (problem_id, code, last_update_time)
			VALUES ($i, $str, '$time');";
		}

		$res = $mysqli->query($sql);
	}
}
?>
<?php if(isset($_POST['delete'])){
	foreach($_POST['pid'] as $i){
		$sql = "SELECT * FROM gpt_code WHERE problem_id='$i'";
		$res=$mysqli->query($sql);
		if($res->num_rows){
			$sql = "DELETE FROM gpt_code WHERE problem_id = $i";
			$res=$mysqli->query($sql);
		}
	}
}
?>

	<title>Update GPTcode</title>
	<h1>Update GPTcode</h1><hr/>
	<ol>
	<h4>Problem id</h4>
	<form class="form-inline" action='gptcode_manage.php' method=post>
		<input class="form-control" type=input name='gcid'>	<input type='hidden' name='do' value='do'>
    <h4>Code</h4>
    <textarea class="form-control" name="code" rows="10" cols="50"></textarea>
		<h4></h4>
    <button type=submit class="btn btn-default">update</button>
		<?php require("../include/set_post_key.php");?>
	</form>
	<div style="height: 20px;"></div>

<?php
  if($_GET['page'])$page=$_GET['page'];
  else $page=1;
  $page_cnt=100;
  // get problems START
  $res_set = $mysqli->query("SELECT set_name,set_name_show FROM problemset");
  $problem_sets = array();
  while($row = $res_set->fetch_array()) {
    array_push($problem_sets,$row);
  }
  $set_name_show = array();
  foreach ($problem_sets as $key => $val){
    $set_name_show[$val["set_name"]]=$val['set_name_show'];
  }
  $first = true;
  $sql = "";
  $cnt = 0;
  foreach ($problem_sets as $key => $val){
    $set_name=$val["set_name"];
    if($_GET['OJ']=='' || $_GET['OJ']==$set_name){
      if(HAS_PRI("see_hidden_".$set_name."_problem")){
      
        //count the number of problem START
        $res = $mysqli->query("SELECT COUNT('problem_id') FROM `problem` WHERE problemset='$set_name'");
        $cnt += $res->fetch_array()[0];
        //count the number of problem END

        $t_sql = "SELECT p.problem_id, p.title, g.last_update_time, 
                  CASE WHEN g.problem_id IS NULL THEN 'N' ELSE 'Y' END AS is_exist
                  FROM problem p
                  LEFT JOIN gpt_code g
                  ON p.problem_id = g.problem_id
                  WHERE p.problemset = '$set_name'";

        if($first) $first = false;
        else $t_sql = " UNION ".$t_sql;
        $sql .= $t_sql;
      }
    }
  }
  $sql.=" ORDER BY `problem_id` DESC ";
  $st=($page-1)*$page_cnt;
  $sql.=" LIMIT $st,$page_cnt";

  if($first) $sql="";
  // get problems END
  /* 计算页数cnt start */
  $view_total_page=$cnt/$page_cnt+($cnt%$page_cnt?1:0);// 页数
  /* 计算页数cnt end */

?>

  <hr/>
  <form action=gptcode_manage.php>
    <select class='selectpicker' onchange="location.href='gptcode_manage.php?OJ=<?php echo $_GET['OJ']?>&page='+this.value;">
    <?php
      for ($i=1;$i<=$view_total_page;$i++){
        if ($i>1) echo '&nbsp;';
        if ($i==$page) echo "<option value='$i' selected>";
        else  echo "<option value='$i'>";
        echo "page ".$i;
        echo "</option>";
      }
    ?>
    </select>
	</form>
<?php
  //echo "<pre>$sql</pre>";
  $result=$mysqli->query($sql) or die($mysqli->error);
?>

  <style>
    .table td {
      vertical-align: middle !important;
    }
  </style>

<?php
  echo "<table class='table table-striped table-hover table-bordered table-condensed' style='white-space: nowrap;'>";
  echo "<form method=post action=gptcode_manage.php>";
  echo "<tr><td colspan=9><button type=submit name='update' value='update' class='btn btn-default' >update</button>";
  echo "<span> </span><button type=submit name='delete' value='delete' class='btn btn-default'>delete</button>"; 
  echo "<tr><td>Problem id<td>Title<td>Last updated time<td>exist gptcode</tr>";
  while($row=$result->fetch_object()){
      echo "<tr>";
      echo "<td>".$row->problem_id;
      echo "<input type=checkbox name='pid[]' value='$row->problem_id'>"."</td>";
      echo "<td><a href='../problem.php?id=$row->problem_id'>".$row->title."</a></td>";
      echo "<td>".$row->last_update_time."</td>";
	  echo "<td>".$row->is_exist."</td>";
      echo "</tr>";
  }
  echo "<tr><td colspan=9><button type=submit name='update' value='update' class='btn btn-default' >update</button>";
  echo "<span> </span><button type=submit name='delete' value='delete' class='btn btn-default'>delete</button>"; 
  echo "</tr></form>";
  echo "</table>";
  require("../oj-footer.php");
?>

<?php 
  require_once("admin-footer.php")
?>