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
        $sql = "SELECT id, problem_id, content, reply, in_date, reply_date FROM contest_discuss WHERE contest_id = '$contest_id' AND problem_id='$problem_id' ORDER BY in_date desc";
    } else {
        $problem_id = -1;
        $sql = "SELECT id, problem_id, content, reply, in_date, reply_date FROM contest_discuss WHERE contest_id = '$contest_id'  ORDER BY in_date desc";
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

}
?>
<div class="am-container">
    <select id="problem_id">
        <option value="-1">All</option>
        <?php
        foreach ($problem_list as $problem) {
            $pid_show = PID($problem->num);
            $selected = $problem->num == $problem_id ? "selected" : "";
            echo "<option value=\"{$problem->num}\" $selected>$pid_show</option>";
        }
        ?>
    </select>
    <table class="am-table am-table-bordered">
        <thead>
            <tr>
                <th style = "width:5%">id</th>
                <th style = "width:5%">problem_id</th>
                <th style = "width:30%">Question</th>
                <th style = "width:15%">in_date</th>
                <th style = "width:30%">Answer</th>
                <th style = "width:15%">reply_date</th>
                <th></th>
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
            <td>            
            <div class="am-text-break" style="width: 400px; padding: 10px;">
            $content
            </div>
            </td>
            <td>$discuss->in_date</td>
            <td>
            <div class="am-text-break" style="width: 400px; padding: 10px;">
                $reply
            </div>
            </td>
            <td>$discuss->reply_date</td>
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
<?php
require_once "template/hznu/footer.php";
?>

<script type="text/javascript">
    $(".reply_btn").click(function() {
        var question_id = $(this).attr("id").split("_")[2];
        $('#my-prompt').modal({
            relatedTarget: this,
            onConfirm: function(e) {
                $.post("ajax/contest_discuss/reply.php", {
                    id: question_id,
                    content: $("#reply_content").val()
                }, function(data) {
                    alert(data['msg']);
                    if(data['result']) {
                        location.reload();
                    }
                }, "json");
            },
            onCancel: function(e) {
//
}
});
    });
    $("#problem_id").change(function() {
        var pid = $(this).val();
        var cid = <?php echo $cid; ?>;
        window.location.href="contest_clarifications_reply.php?cid=" + cid + "&problem_id=" + pid;
    })
</script>