<?php
include "template/hznu/contest_header.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/const.inc.php";
?>


<div class="am-container" style = "margin-top:20px;">
    <h1>BroadCast</h1>
    <hr>
    <table class="am-table am-table-bordered am-text-center">
        <thead>
            <tr>
                <th class='am-text-center' style = "width:5%">#</th>
                <th class='am-text-center' style = "width:95%">Content</th>
            </tr>
        </thead>
        <?php
        foreach($broadcast_list as $broadcast) {
            $content = htmlentities($broadcast->content);
            echo <<<HTML
            <tr>
            <td>$broadcast->id</td> 
            <td>
            <div class="am-text-break" style="width: 1280px; padding: 10px;">
            $content
            </div>
            </tr>
HTML;
        }
        ?>
    </table>
</div>

<div class="am-container">
    <h1>Clarifications</h1>
    <hr>
    <form class="am-form">
        <select id="problem_id">
            <?php
            foreach ($problem_list as $problem) {
                $pid_show = PID($problem->num);
                echo "<option value=\"{$problem->num}\">$pid_show</option>";
            }
            ?>
        </select>
        <br>
        <div class="am-form-group">
            <textarea id="content" id="" rows="10" class="kindeditor"></textarea>
        </div>
        <button id="submit" value="submit" class="am-btn am-btn-lg am-btn-primary am-btn-block" style = "margin-left:46%;width:110px;height:38px;">
        Confirm
        </button>
    </form>
    <h1>History</h1>
    <hr>
    <table class="am-table am-table-bordered">
        <thead>
            <tr>
                <th class='am-text-center' style = "width:5%">#</th>
                <th class='am-text-center' style = "width:5%">Prob.ID</th>
                <th class='am-text-center' style = "width:45%">Question</th>
                <!-- <th class='am-text-center' style = "width:10%">in_date</th> -->
                <th class='am-text-center' style = "width:45%">Reply</th> 
                <!-- <th class='am-text-center' style = "width:10%">reply_date</th> -->
            </tr>
        </thead>
        <?php
        foreach($discuss_list as $discuss) {
            $pid_show = PID($discuss->problem_id);
            $content = htmlentities($discuss->content);
            $reply = htmlentities($discuss->reply);
            echo <<<HTML
            <tr>
            <td class='am-text-center' >$discuss->id</td>
            <td class='am-text-center' >$pid_show</td>
            <td class='am-text-center' >
            <div class="am-text-break" style="width: 600px; padding: 10px;">
                $content
            </div>
            </td> 
            <td>
            <div class="am-text-break" style="width: 600px; padding: 10px;">
                $reply
            </div>
            </td> 
            </tr>
HTML;
        }
        ?>
    </table>

</div>

<?php include "footer.php" ?>


<script type="text/javascript">
    $("#submit").click(function(e) {
        e.preventDefault();
        var content = $("#content").val();
        if (content.length <= 3) {
            AMUI.dialog.alert({
                title: '提示',
                content: "question is too short to be submited.",  
                onConfirm: function() {
                }
            });
            return;
        } else if (content.length > 512) {
            AMUI.dialog.alert({
                title: '提示',
                content: "question is too long to be submited.",  
                onConfirm: function() {
                }
            });
            return;
        }
        $.post("ajax/contest_discuss/ask.php", { 
            cid: <?php echo $contest_id ?>,
            problem_id: $("#problem_id").val(),
            content: $("#content").val()
        }, function(data) {
            AMUI.dialog.alert({
                title: '提示',
                content: data['msg'],  
                onConfirm: function() {
                    if(data['result']) {
                        location.reload();
                    }
                }
            });
        }, "json");
    });
</script>


<!-- 增加Mathjax渲染支持 -->
  <script type="text/x-mathjax-config">
  MathJax.Hub.Config({
  showProcessingMessages: false, //关闭js加载过程信息
   messageStyle: "none", //不显示信息
    extensions: ["tex2jax.js"],
    jax: ["input/TeX", "output/HTML-CSS"],
    tex2jax: {
      //$表示行内元素，$$表示块状元素
      inlineMath: [ ['$','$'], ["\\(","\\)"] ],
      displayMath: [ ['$$','$$'], ["\\[","\\]"] ],
      processEscapes: true
    },
    "HTML-CSS": { 
    availableFonts: ["TeX"] 
  }
  });
</script>
<!--加载MathJax的最新文件， async表示异步加载进来 -->
<script type="text/javascript" async src="/OJ/plugins/MathJax/MathJax.js">
</script>