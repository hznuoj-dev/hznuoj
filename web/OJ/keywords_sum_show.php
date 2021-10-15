<?php 
    // echo $user_id;   
    if(!isset($user_id)){
        /////////////////////////Template
        require("template/".$OJ_TEMPLATE."/loginpage.php");
        /////////////////////////Common foot
        exit(0);
    }
?>
<?php
    // ini_set('display_errors', 'On');
    // ini_set('display_startup_errors', 'On');
    // error_reporting(E_ALL);
    $cache_time=10; 
    $OJ_CACHE_SHARE=false;
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
    //寻找AC代码
    // $sql="SELECT  solution_id FROM `solution` WHERE `user_id`='$user_mysql' AND result=4";
    // $result=$mysqli->query($sql);
    // while($row=$result->fetch_object()){
    //     $solutionid=$row->solution_id;
    //     $sql2="SELECT * FROM `source_code` WHERE solution_id='$solutionid'";
    //     $result2=$mysqli->query($sql2);
    //     $row2=$result2->fetch_object();
    //     $source_code=$row2->source;
    //     // echo $source_code;
    //     // print_r($row2->source);
    //     $result2->free();
    //     // 显示提取的原代码
    //     echo "<br>solution_id:";
    //     echo $solutionid;
    //     echo "<br>";
    //     echo "<pre style='background-color: transparent;'><code style='background-color: transparent;'>";
    //     echo htmlentities(str_replace("\r\n","\n",$source_code),ENT_QUOTES,"utf-8");
    //     echo "</code></pre>";
    // }
?>
<?php
    require_once "template/hznu/header.php";
    $keywords=array("int","char","return","struct","typedef",
    "void","switch","if","else","break","continue","goto","do",
    "while","for","sizeof","\\n","\\\\","\'","\?","short",
    "double","float","long","long long","long double","unsigned int","unsigned long","unsigned long long",
    "enum");
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
    error_reporting(E_ALL);
    $cache_time=10; 
    $OJ_CACHE_SHARE=false;
    function search($string)
    {
        global $user_mysql,$mysqli; 
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
            // 显示提取的原代码
            // echo "<br>solution_id:";
            // echo $solutionid;
            // echo "<br>";
            // echo "<pre style='background-color: transparent;'><code style='background-color: transparent;'>";
            // echo htmlentities(str_replace("\r\n","\n",$source_code),ENT_QUOTES,"utf-8");
            // echo "</code></pre>";
            $pos=strrpos($source_code,$string);
            if($pos!=false){
                $selectcnt++;
            }
        }
        //显示次数
        // echo "<br>cnt:";
        // echo $selectcnt;
        $result->free();
        return $selectcnt;
    }
    // function test(){
    //     echo "hello world!";
    // }
    function color_font($color){
       return "#000";
    }
    function result($selectcnt){
        $search_name="none";
        if($selectcnt>0){
            if($selectcnt<=10)$search_name="newbie";
            else if($selectcnt<=50)$search_name="pupil";
            else if($selectcnt<=100)$search_name="expert";
            else if($selectcnt<=300)$search_name="candidate master";
            else if($selectcnt>300)$search_name="master";
        }   
        if($selectcnt==0)$color="#000000";
        else if($selectcnt<=10)$color="#fffacd";
        else if($selectcnt<=50)$color="#90ee90";
        else if($selectcnt<=100)$color="#87CEFA";
        else if($selectcnt<=300)$color="#BA55D3";
        else if($selectcnt>300)$color="#FF0000";
        $rever_color=color_font($color);
        // $res1=hex2rgb($color);
        // echo $res1;
        // echo $rever_color;
        if($selectcnt==0)echo "<div style='background:#fff;color:#000'>".$search_name."</div>";
        else echo "<div style='background:".$color.";color:".$rever_color."'>".$search_name."</div>";
    }
?>
<style>
    table{ 
    border-collapse:collapse;border:none; 
    } 
    td{ border:#ccc solid 1px; padding:5px; }
    .box{
      border: 1px solid #eee;
      padding: 30px 10px 50px 10px;
      margin: 25px 0px 15px 0;
      box-shadow: 2px 2px 10px 0 #ccc;
    }
    p{
        /* background-color:red; */
        margin:10px;
    }
</style>
<div class="am-container">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <style type="text/css">
        .solution-info {
            display: inline-block;
            margin: 5px;
        }
        </style>
        <div class="box">
            <div class="content-block-body">
                <h1 align="center">分数标准</h1>
                  <div class="am-text-center">
                    <div class="solution-info">
                        <table width="1200" class="detail-table">
                            <tbody>
                                <tr>
                                <td colspan="5">
                                    <p>熟练度</p>
                                </td>
                                <td colspan="5">
                                    <p>颜色</p>
                                </td>
                                <td colspan="5">
                                    <p>熟练度</p>
                                </td>
                                <td colspan="5">
                                    <p>颜色</p>
                                </td>
                                <td colspan="5">
                                    <p>熟练度</p>
                                </td>
                                <td colspan="5">
                                    <p>颜色</p>
                                </td>
                                <td colspan="5">
                                    <p>熟练度</p>
                                </td>
                                <td colspan="5">
                                    <p>颜色</p>
                                </td>
                                <td colspan="5">
                                    <p>熟练度</p>
                                </td>
                                <td colspan="5">
                                    <p>颜色</p>
                                </td>
                                </tr>
                                <tr>
                                <td colspan="5">
                                    <p>newbie</p>
                                </td>
                                <td colspan="5">
                                <canvas id="myCanvas" width="100" height="50" style="border:1px solid #c3c3c3;">
                                Your browser does not support the canvas element.
                                </canvas>
                                <script type="text/javascript">
                                    var canvas=document.getElementById('myCanvas');
                                    var ctx=canvas.getContext('2d');
                                    ctx.height=50;
                                    ctx.fillStyle='#fffacd';
                                    ctx.fillRect(0,0,150,50);
                                </script>
                                </td>
                                <td colspan="5">
                                    <p>pupil</p>
                                </td>
                                <td colspan="5">
                                <canvas id="myCanvas1" width="100" height="50" style="border:1px solid #c3c3c3;">
                                Your browser does not support the canvas element.
                                </canvas>
                                <script type="text/javascript">
                                    var canvas=document.getElementById('myCanvas1');
                                    var ctx=canvas.getContext('2d');
                                    ctx.height=50;
                                    ctx.fillStyle='#90ee90';
                                    ctx.fillRect(0,0,150,50);
                                </script>
                                </td>
                                <td colspan="5">
                                    <p>expert</p>
                                </td>
                                <td colspan="5">
                                <canvas id="myCanvas2" width="100" height="50" style="border:1px solid #c3c3c3;">
                                Your browser does not support the canvas element.
                                </canvas>
                                <script type="text/javascript">
                                    var canvas=document.getElementById('myCanvas2');
                                    var ctx=canvas.getContext('2d');
                                    ctx.height=50;
                                    ctx.fillStyle='#87CEFA';
                                    ctx.fillRect(0,0,150,50);
                                </script>
                                </td>
                                <td colspan="5">
                                    <p>candidate master</p>
                                </td>
                                <td colspan="5">
                                <canvas id="myCanvas3" width="100" height="50" style="border:1px solid #c3c3c3;">
                                Your browser does not support the canvas element.
                                </canvas>
                                <script type="text/javascript">
                                    var canvas=document.getElementById('myCanvas3');
                                    var ctx=canvas.getContext('2d');
                                    ctx.height=50;
                                    ctx.fillStyle='#BA55D3';
                                    ctx.fillRect(0,0,150,50);
                                </script>
                                </td>
                                <td colspan="5">
                                    <p>master</p>
                                </td>
                                <td colspan="5">
                                <canvas id="myCanvas4" width="100" height="50" style="border:1px solid #c3c3c3;">
                                Your browser does not support the canvas element.
                                </canvas>
                                <script type="text/javascript">
                                    var canvas=document.getElementById('myCanvas4');
                                    var ctx=canvas.getContext('2d');
                                    ctx.height=50;
                                    ctx.fillStyle='#FF0000';
                                    ctx.fillRect(0,0,150,50);
                                </script>
                                </td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                  </div>
            </div>
        </div>
        <?php
        // test();
        echo "<hr>";
        echo "<div class='box'>";
        echo "<h1 align='center'>汇总结果</h1>";
        echo "<div class='am-text-center'>";
        echo "<div class='solution-info'>";
        echo " <table width='1200' class='detail-table'>";
        echo "<tbody>";
        echo "<tr>";
        for($i=1;$i<=5;$i++){
            echo "<td colspan='3'>";
            echo "<p>关键词</p>";
            echo "</td>";
            echo "<td colspan='7'>";
            echo "<p>熟练度</p>";
            echo "</td>";
        }
        echo "</tr>";
        $sum=count($keywords);  
        for($i=0;$i<$sum;){
            echo "<tr>";
            for($j=1;$j<=5;$j++){
                echo "<td colspan='3'>";
                echo "<p>".$keywords[$i]."</p>";
                echo "</td>";
                echo "<td colspan='7'>";
                result(search($keywords[$i++]));
                echo "</td>";
                if($i==$sum)break;
            }
            if($i==$sum)break;
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        ?>
        <!-- <table width="841" class="detail-table">
            <tbody>
                <tr>
                <td colspan="5">
                    <p>熟练度</p>
                </td>
                <td colspan="5">
                    <p>颜色</p>
                </td>
                </tr>
            </tbody>
        </table> -->
    </div>
</div> <!-- /container -->
<?php require_once "template/hznu/footer.php" ?>
<!-- highlight.js START-->
<link href="/OJ/plugins/highlight/styles/github-gist.css" rel="stylesheet">
<script src="/OJ/plugins/highlight/highlight.pack.js"></script>
<script src="/OJ/plugins/highlight/highlightjs-line-numbers.min.js"></script>
<style type="text/css">
    .hljs-line-numbers {
        text-align: right;
        border-right: 1px solid #ccc;
        color: #999;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>
<script>
    hljs.initHighlightingOnLoad();
    hljs.initLineNumbersOnLoad();
</script>
<!-- highlight.js END-->



