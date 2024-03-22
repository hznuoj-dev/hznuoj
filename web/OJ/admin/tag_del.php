<?php
require("admin-header.php");

require_once("../include/check_post_key.php");
if (isset($_POST['deltag'])) {
    $tag = $_POST['deltag'];

    try {
        // 从all_problem_tag删除标签
        $stmt = $mysqli->prepare("DELETE FROM all_problem_tag WHERE tag = ?");
        $stmt->bind_param('s', $tag);
        $stmt->execute();

        // 从problem_tag删除相应的记录
        $stmt = $mysqli->prepare("DELETE FROM problem_tag WHERE tag = ?");
        $stmt->bind_param('s', $tag);
        $stmt->execute();

        // 如果没有抛出异常，提交事务
        $mysqli->commit();

        echo '成功删除标签和相应的记录';
    } catch (Exception $e) {
        // 抛出了异常
        // 我们必须回滚事务
        $mysqli->rollback();

        echo '删除标签和相应记录时出错: ',  $e->getMessage();
    }

    $stmt->close();
    $mysqli->close();
}
