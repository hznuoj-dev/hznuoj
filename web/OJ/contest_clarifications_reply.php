<?php
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
require_once './include/const.inc.php';
require_once "template/hznu/contest_header.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/const.inc.php";
if (isset($_GET['cid'])){
    if(!HAS_PRI("contest_reply")) {
        require_once "template/hznu/footer.php"; 
        exit(0); 
    }
    $contest_id = $mysqli->real_escape_string($_GET['cid']);  
    if(isset($_GET['problem_id']) && $_GET['problem_id'] != -1) {
        $problem_id = intval($_GET['problem_id']);
        $sql = "SELECT id, user_id, problem_id, content, reply, in_date, reply_date FROM contest_discuss WHERE contest_id = '$contest_id' AND problem_id='$problem_id' ORDER BY in_date DESC";
    } else {
        $problem_id = -1; 
        $sql = "SELECT id, user_id, problem_id, content, reply, in_date, reply_date FROM contest_discuss WHERE contest_id = '$contest_id'  ORDER BY in_date DESC";
    }
    $result = $mysqli->query($sql);  
    $discuss_list = [];
    if ($result){
        while ($row=$result->fetch_object()) { 
            array_push($discuss_list, $row);  
        }
    }
    $result->free();

    $sql = "SELECT num FROM contest_problem WHERE contest_id='$contest_id' ORDER BY num";
    $res = $mysqli->query($sql);
    $problem_list=[];
    while($row=$res->fetch_object()) {
        array_push($problem_list, $row);
    }

    $sql = "SELECT id, content FROM contest_broadcast WHERE contest_id = '$contest_id' ORDER BY id DESC";
    // echo $sql;
    $broadcast_list = [];
    if ($res = $mysqli->query($sql)) {
        while ($row = $res->fetch_object()) {
            array_push($broadcast_list, $row); 
        } 
    }
}
?>

<div class="am-container">
    <button style = "margin-bottom: 10px; margin-right: 10px;" id = "send_broadcast">发布广播</button>
    <table class="am-table am-table-bordered am-text-center">
        <thead>
            <tr>
                <th class='am-text-center' style = "width:5%">#</th>
                <th class='am-text-center' style = "width:90%">Content</th>
                <th class='am-text-center' style = "width:5%">OP</th> 
            </tr>
        </thead>
        <?php
        foreach($broadcast_list as $broadcast) {
            $content = htmlentities($broadcast->content);
            echo <<<HTML
            <tr>
            <td>$broadcast->id</td> 
            <td>
            <div class="am-text-break" style="width: 1200px; padding: 10px;">
            $content
            </div>
            </td>
            <td><a href="#" class="update_btn" id="update_btn_{$broadcast->id}">Update</a></td>
            </tr>
HTML;
        }
        ?>
    </table>
</div>


<div class="am-container">
    <select id="problem_id" style = "margin-bottom: 10px;">
        <option value="-1">All</option>
        <?php
        foreach ($problem_list as $problem) {
            $pid_show = PID($problem->num); 
            $selected = $problem->num == $problem_id ? "selected" : "";
            echo "<option value=\"{$problem->num}\" $selected>$pid_show</option>";
        }
        ?>
    </select>
    <table class="am-table am-table-bordered am-text-center">
        <thead>
            <tr>
                <th class='am-text-center' style = "width:5%">#</th>
                <th class='am-text-center' style = "width:5%">Prob.ID</th>
                <th class='am-text-center' style = "width:8%">User ID</th>
                <th class='am-text-center' style = "width:40%">Question</th>
                <th class='am-text-center' style = "width:40%">Reply</th>
                <th class='am-text-center' style = "width:2%">OP</th>
            </tr>
        </thead>
        <?php
        foreach($discuss_list as $discuss) {
            $pid_show = PID($discuss->problem_id);
            $content = htmlentities($discuss->content);
            $reply = htmlentities($discuss->reply);
            echo <<<HTML
            <tr>
            <td>$discuss->id</td>
            <td>$pid_show</td>
            <td>$discuss->user_id</td>
            <td>            
            <div class="am-text-break" style="width: 520px; padding: 10px;">
            $content
            </div>
            </td>
            <td>                
            <div class="am-text-break" style="width: 520px; padding: 10px;">
            $reply
            </div>
            </td>
            <td><a href="#" class="reply_btn" id="reply_btn_{$discuss->id}">Reply</a></td>
            </tr>
HTML;
        }
        ?>
    </table>
</div>


<div class="am-modal am-modal-prompt" tabindex="-1" id="my-prompt">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">Edit Reply Here</div>
        <div class="am-modal-bd">
            <textarea id="reply_content" cols="50" rows="15"></textarea>
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>Cancel</span>
            <span class="am-modal-btn" data-am-modal-confirm>Submit</span>
        </div>
    </div>
</div>


<div class="am-modal am-modal-prompt" tabindex="-1" id="broadcast_prompt">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">Edit Broadcast Here</div>
        <div class="am-modal-bd">
            <textarea id="broadcast_content" cols="50" rows="15"></textarea>
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>Cancel</span>
            <span class="am-modal-btn" data-am-modal-confirm>Submit</span>
        </div>
    </div>
</div>


<?php
require_once "template/hznu/footer.php";
?>

<script type="text/javascript">
    $(".reply_btn").click(function() {
        var question_id = $(this).attr("id").split("_")[2];
        $.post("ajax/contest_discuss/getReplyContent.php", {
            id: question_id,
        }, function(data) {
            $("#reply_content").val(data['reply']);
        }, "json");

        $('#my-prompt').modal({
            relatedTarget: this,
            onConfirm: function(e) {
                $.post("ajax/contest_discuss/reply.php", {
                    id: question_id,
                    content: $("#reply_content").val()
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
            },
            onCancel: function(e) {
            }
        });
    });

    $(".update_btn").click(function() {
        var id = $(this).attr("id").split("_")[2];
        $.post("ajax/contest_discuss/getBroadcastContent.php", {
            id: id,
        }, function(data) {
            $("#broadcast_content").val(data['content'] + id);
        }, "json");

        $('#broadcast_prompt').modal({ 
            relatedTarget: this,
            onConfirm: function(e) {
                $.post("ajax/contest_discuss/updateBroadcast.php", {
                    id: id,
                    content: $("#broadcast_content").val()
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
            },
            onCancel: function(e) {

            }
        });
    });

    $("#send_broadcast").click(function() {
        $('#broadcast_content').val("");
        $('#broadcast_prompt').modal({
            relatedTarget: this,
            onConfirm: function(e) {
                $.ajax({
                    type: "POST",  
                    url: 'ajax/contest_discuss/send_broadcast.php',  
                    data: {
                        cid: <?php echo $contest_id; ?>,
                        content: $("#broadcast_content").val(),
                    },
                    context: this, 
                    success: function(data){  
                        var json = JSON.parse(data);
                        AMUI.dialog.alert({ 
                            title: '提示',
                            content: json.msg,  
                            onConfirm: function() {
                                if(json.result) {
                                    location.reload();
                                }
                            }
                        });
                    },
                    complete: function(){
                        console.log("ajax complete!");
                    },
                    error: function(xmlrqst,info){
                        console.log(info);
                    }
                })
            },
            onCancel: function(e) {
            
            }
        });
    });

    $("#problem_id").change(function() {
        var pid = $(this).val();
        var cid = <?php echo $cid; ?>;
        window.location.href="contest_clarifications_reply.php?cid=" + cid + "&problem_id=" + pid; 
    })


// $(document).ready(function(){
//     setInterval(function(){ location.reload(); }, 5000);
// });

</script>



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