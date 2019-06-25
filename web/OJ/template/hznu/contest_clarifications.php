<?php
include "template/hznu/contest_header.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/const.inc.php";
?>


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
                <th style = "width:5%">id</th>
                <th style = "width:5%">problem_id</th>
                <th style = "width:30%">Question</th>
                <th style = "width:15%">in_date</th>
                <th style = "width:30%">Answer</th>
                <th style = "width:15%">reply_date</th>
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
            alert("question is too short to be submited.");
            return;
        }
        $.post("ajax/contest_discuss/ask.php", {
            cid: <?php echo $contest_id ?>,
            problem_id: $("#problem_id").val(),
            content: $("#content").val()
        }, function(data) {
            alert(data['msg']);
            if(data['result']) {
                location.reload();
            }
        }, "json");
    });
</script>