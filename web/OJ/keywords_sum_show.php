<?php  
    if(!isset($user_id)){
        require("template/".$OJ_TEMPLATE."/loginpage.php");
        exit(0);
    }
?>
<?php
    $cache_time=10; 
    $OJ_CACHE_SHARE=false;
    $user=$_SESSION['user_id'];
    if (!is_valid_user_name($user)){
        echo "No such User!";
        exit(0);
    }
    $user_mysql=$mysqli->real_escape_string($user);
?>
<?php
    require_once "template/hznu/header.php";
    $my_keywords=array("","int","char","return","struct","typedef",
    "void","switch","if","else","break","continue","goto","do",
    "while","for","sizeof","\\n","\\\\","\'","\?","short",
    "double","float","long","long long","long double","unsigned int","unsigned long","unsigned long long",
    "enum");
    $keywords_len = count($my_keywords);
    $GLOBALS['keywords_num'] = array();
    $GLOBALS['vis'] = array();
    for ($i=0;$i<$keywords_len;$i++) {
        array_push($GLOBALS['keywords_num'], 0);
        array_push($GLOBALS['vis'], 0);
    }
    
    class AcAutomation {
        private $root;

        public function __construct($keywords = array()) {
            $this->root = $this->createNode();
            $idx = 0;
            foreach ($keywords as $keyword) {
                $this->addKeyword($keyword, $idx);
                $idx++;
            }
            $this->buildFailIndex();
        }

        private function createNode($value = "") {
            $node = new stdClass();
            $node->value = $value;
            $node->next  = array();
            $node->fail  = NULL;
            $node->len   = 0; // Last index of the string in the trie
            return $node;
        }

        private function addKeyword($keyword, $idx) {
            $keyword = trim($keyword);
            if (!$keyword) {
                return;
            }
            $cur = $this->root;
            $matches = unpack('N*',iconv('UTF-8', 'UCS-4', strtolower($keyword)));
            for ($i = 1; isset($matches[$i]); $i++) {
                $v = $matches[$i];
                if (!isset($cur->next[$v])) {
                    $node = $this->createNode($v);
                    $cur->next[$v] = $node;
                }
                if (!isset($matches[$i+1])) {
                    $cur->next[$v]->len = $idx;
                }
                $cur = $cur->next[$v];
            }
        }

        private function buildFailIndex() {
            $queue = array();
            foreach ($this->root->next as $node) {
                $node->fail = $this->root;
                $queue[] = $node;
            }
            while ($queue) {
                $node = array_shift($queue);
                foreach ($node->next as $child_node) {
                    $val = $child_node->value;
                    $p = $node->fail;
                    while ($p != NULL) {
                        if (isset($p->next[$val])) {
                            $child_node->fail = $p->next[$val];
                            break;
                        }
                        $p = $p->fail;
                    }
                    if ($p === NULL) {
                        $child_node->fail = $this->root;
                    }
                    $queue[] = $child_node;
                }
            }
        }

        public function search($content) {
            $p = $this->root;
            $matches = unpack('N*',iconv('UTF-8', 'UCS-4', strtolower($content)));
            for ($i = 1; isset($matches[$i]); $i++) {
                $val = $matches[$i];
                while (!isset($p->next[$val]) && $p != $this->root) {
                    $p = $p->fail;
                }
                $p = isset($p->next[$val]) ? $p->next[$val] : $this->root;
                $temp = $p;
                while ($temp != $this->root) {
                    if ($temp->len) {
                        if ($GLOBALS['vis'][$temp->len] == 0) {
                            $GLOBALS['keywords_num'][$temp->len]++;
                            $GLOBALS['vis'][$temp->len] = 1;
                        }
                    }
                    $temp = $temp->fail;
                }
            }
        }
    }

    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
    error_reporting(E_ALL);
    $cache_time=10; 
    $OJ_CACHE_SHARE=false;

    $ac = new AcAutomation($my_keywords);
    global $user_mysql, $mysqli;
    $sql_solution_id="SELECT  solution_id FROM `solution` WHERE `user_id`='$user_mysql' AND result=4";
    $result_solution_id=$mysqli->query($sql_solution_id);

    while($row=$result_solution_id->fetch_object()) {
        $solutionid=$row->solution_id;
        $sql_source_code="SELECT * FROM `source_code` WHERE solution_id='$solutionid'";
        $result_source_code=$mysqli->query($sql_source_code);
        $source_code=$result_source_code->fetch_object()->source;
        for ($i=0;$i<$keywords_len;$i++) $GLOBALS['vis'][$i] = 0;
        $ac->search($source_code);
    }

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
        for($i=1;$i<$keywords_len;){
            echo "<tr>";
            for($j=1;$j<=5;$j++){
                echo "<td colspan='3'>";
                echo "<p>".$my_keywords[$i]."</p>";
                echo "</td>";
                echo "<td colspan='7'>";
                result($GLOBALS['keywords_num'][$i++]);
                echo "</td>";
                if($i==$keywords_len)break;
            }
            if($i==$keywords_len)break;
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        ?>
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

