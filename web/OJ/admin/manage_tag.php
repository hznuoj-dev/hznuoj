<?php
require("admin-header.php");
if (!HAS_PRI("manage_tag")) {
    echo "Permission denied!";
    exit(1);
}

require_once('../include/db_info.inc.php');

if (isset($_POST['addtag'])) {
    // require_once("../include/check_post_key.php");
    $tag = $_POST['addtag'];

    if ($tag == "") {
        echo ("Tag empty!");
        exit(1);
    }

    $stmt = $mysqli->prepare("SELECT * FROM all_problem_tag WHERE tag = ?");
    $stmt->bind_param('s', $tag);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo ("Tag exists!");
        exit(1);
    } else {
        $stmt = $mysqli->prepare("INSERT INTO all_problem_tag (tag) VALUES (?)");
        $stmt->bind_param('s', $tag);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "<script>location.href='/OJ/admin/manage_tag.php';</script>";
        } else {
            echo ("Error adding tag");
            exit(1);
        }
    }

    $stmt->close();
    $mysqli->close();
}
if (isset($_POST['deltag'])) {
    require_once("../include/check_post_key.php");
    $tag = $_POST['deltag'];

    try {
        // 开始事务
        $mysqli->begin_transaction();

        // 从all_problem_tag删除标签
        $stmt = $mysqli->prepare("DELETE FROM all_problem_tag WHERE tag = ?");
        $stmt->bind_param('s', $tag);
        $stmt->execute();

        // 从problem_tag删除相应的记录
        $stmt = $mysqli->prepare("DELETE FROM problem_tag WHERE tag = ?");
        $stmt->bind_param('s', $tag);
        $stmt->execute();

        // 提交事务
        $mysqli->commit();

        echo "<script>location.href='/OJ/admin/manage_tag.php';</script>";
    } catch (Exception $e) {
        // 回滚事务
        $mysqli->rollback();

        echo '删除标签和相应记录时出错: ', $e->getMessage();
        exit(1);
    }

    $stmt->close();
    $mysqli->close();
}
?>

<style>
    .tag-warp {
        padding: 0px 10px;
    }

    .tag-items {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        list-style: none;
        margin: 20px 0px;
        padding: 0;
    }

    .tag-item {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        display: flex;
        align-items: center;
        margin: .5rem;
        padding: .5rem;
    }

    .tag-item-del {
        margin-left: .5rem;
        color: #dc3545;
        cursor: pointer;
    }

    .tag-input {
        margin: .5rem;
    }
</style>

<title>Update Tags</title>
<h1>Update Tags</h1>
<hr />
<div class="tag-warp">
    <ul class="tag-items">
        <?php
        $res = $mysqli->query("SELECT * FROM all_problem_tag");
        while ($row = $res->fetch_array()) {
            echo '<li class="tag-item">';
            echo '<span>' . $row['tag'] . '</span><i class="tag-item-del">x</i>';
            echo '</li>';
        }
        ?>
    </ul>
    <form class="form-inline" method='post' action='manage_tag.php'>
        <input class="tag-input form-control" type="text" placeholder="标签" name="addtag">
        <input class="btn btn-default" type='submit' value='添加'>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    </form>
</div>

<div class="modal fade" id="del-tag-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin-top: 25vh;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete Tag</h4>
            </div>
            <div class="modal-body">
                确定删除 <strong id="confirm-del-tag-name"></strong> ？这将删除Tag和所有题目上的相关记录。
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="confirm-del-tag" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>


<script>
    async function deleteTagFromDatabase(tagName) {
        let csrf_token = document.querySelector('input[name="csrf_token"]').value;
        let response = await fetch('manage_tag.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'deltag': tagName,
                'csrf_token': csrf_token
            })
        });

        if (response.ok) {
            location.reload();
        } else {
            console.error('Error deleting tag');
        }
    }

    let tags = document.querySelectorAll('.tag-item');
    tags.forEach((tag) => {
        tag.addEventListener('click', (e) => {
            let del = e.target.closest('.tag-item-del');
            let item = del.closest('.tag-item');
            let tagName = item.querySelector('span').textContent;
            $('#del-tag-modal').modal();
            $('#confirm-del-tag-name').text(tagName);
            $('#confirm-del-tag').click(function(e) {
                if (del) {
                    deleteTagFromDatabase(tagName); // 删除数据库中的标签
                }
            })
        });
    });
</script>

<?php
require_once("admin-footer.php")
?>