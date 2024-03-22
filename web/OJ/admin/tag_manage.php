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
            echo "<script>location.href='/OJ/admin/tag_manage.php';</script>";
        } else {
            echo ("Error adding tag");
            exit(1);
        }
    }

    $stmt->close();
    $mysqli->close();
}
?>

<style>
    .tag {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .tag-items {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        list-style: none;
        margin: 0;
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
<ol>
    <div class="tag row">
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
    </div>
    <form class="form-inline" method='post' action='tag_manage.php'>
        <input class="tag-input form-control" type="text" placeholder="标签" name="addtag">
        <input class="btn btn-default" type='submit' value='添加'>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    </form>


    <script>
        function deleteTagFromDatabase(tagName) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'tag_del.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            let csrfToken = document.querySelector('input[name="csrf_token"]').value;
            let params = 'deltag=' + encodeURIComponent(tagName) + '&csrf_token=' + encodeURIComponent(csrfToken);
            xhr.send(params); // 发送请求
        }

        let tags = document.querySelectorAll('.tag');
        tags.forEach((tag) => {
            tag.addEventListener('click', (e) => {
                let del = e.target.closest('.tag-item-del');
                if (del !== null) {
                    let item = del.closest('.tag-item');
                    let tagName = item.querySelector('span').textContent;
                    deleteTagFromDatabase(tagName); // 删除数据库中的标签
                    item.remove();
                }
            });
        });
    </script>

    <?php
    require_once("admin-footer.php")
    ?>
