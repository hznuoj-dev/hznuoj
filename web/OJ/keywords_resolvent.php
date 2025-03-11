<?php $title="Search Result"; ?>
<?php
// require_once "template/hznu/header.php"; 
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/my_func.inc.php");
require_once("./include/const.inc.php");
?>
<?php
    // ini_set('display_errors', 'On');
    // ini_set('display_startup_errors', 'On');
    // error_reporting(E_ALL);
    // $cache_time=10; 
    // $OJ_CACHE_SHARE=false;

    //得到选择关键字
    $getselect=$_POST["select"];
    // echo "select:";
    // echo $getselect;
    // echo "<br>";

    // check user
    $user=$_SESSION['user_id'];
    if (!is_valid_user_name($user)){
        echo "No such User!";
        exit(0);
    }

    $user_mysql=$mysqli->real_escape_string($user);
    //显示用户id
    // echo "user_mysql:";
    // echo $user_mysql;
    $sql="SELECT `school`,`email`,`nick`,level,color,strength,real_name,class,stu_id FROM `users` WHERE `user_id`='$user_mysql'";

    $result=$mysqli->query($sql);
    $row_cnt=$result->num_rows;
    if ($row_cnt==0){ 
        $view_errors= "No such User!";
        // require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }

    //测试显示邮箱
    // $row=$result->fetch_object();
    // echo "<br>";
    // print_r($row);
    // $email=$row->email;
    // echo "<br>email:";
    // echo $email;
    // $result->free();

    $selectcnt=0;
    //寻找AC代码
    $sql="SELECT  solution_id FROM `solution` WHERE `user_id`='$user_mysql' AND result=4";
    $result=$mysqli->query($sql);
    while($row=$result->fetch_object()){
        $solutionid=$row->solution_id;
        $sql2="SELECT * FROM `source_code` WHERE solution_id='$solutionid'";
        $result2=$mysqli->query($sql2);
        $row2=$result2->fetch_object();
        $source_code=$row2->source;
        // echo $source_code;
        // print_r($row2->source);
        $result2->free();
        //显示提取的原代码
        // echo "<br>solution_id:";
        // echo $solutionid;
        // echo "<br>";
        // echo "<pre style='background-color: transparent;'><code style='background-color: transparent;'>";
        // echo htmlentities(str_replace("\r\n","\n",$source_code),ENT_QUOTES,"utf-8");
        // echo "</code></pre>";
        $pos=strrpos($source_code,$getselect);
        if($pos!=false){
            $selectcnt++;
        }
    }
    //显示次数
    // echo "<br>cnt:";
    // echo $selectcnt;
    $result->free();
    // echo $getselect;
    // if(!isset($_POST["select"]))$json=array('status'=>'fail','status'=>'you have not select any words');
    $json=array('status'=>'success', 'select'=>$selectcnt);
    echo json_encode($json);
?>