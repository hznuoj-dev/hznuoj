<?php
require("admin-header.php");

require_once("../include/check_post_key.php");
if (isset($_POST['deltag'])) {
    $tag = $_POST['deltag'];

    // 删除标签
    $stmt = $mysqli->prepare("DELETE FROM all_problem_tag WHERE tag = ?");
    $stmt->bind_param('s', $tag);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo 'Tag deleted successfully';
    } else {
        echo 'No tag found';
    }

    $stmt->close();
    $mysqli->close();
}
?>
