<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/OJ/include/db_info.inc.php";
global $mysqli;
$user = $_POST['user'];
$tag = $_POST['tag'];

$sql = "SELECT p.problem_id, s.result
        FROM problem_tag pt
        JOIN problem p ON pt.problem_id = p.problem_id
        LEFT JOIN solution s ON p.problem_id = s.problem_id AND s.user_id = ?
        WHERE pt.tag = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param('ss', $user, $tag);

$stmt->execute();

$result = $stmt->get_result();

$data = array(
    'solved' => array(),
    'unsolved' => array()
);

while ($row = $result->fetch_assoc()) {
    if ($row['result'] == 4) {
        $data['solved'][] = $row['problem_id'];
    } else {
        $data['unsolved'][] = $row['problem_id'];
    }
}

$result->free();

$mysqli->close();

echo json_encode($data);
