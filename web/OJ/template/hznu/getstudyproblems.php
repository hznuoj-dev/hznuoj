<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/OJ/include/db_info.inc.php";
global $mysqli;
$user = $_POST['user'];
$tag = $_POST['tag'];

$sql = "SELECT p.problem_id, s.result, p.score
        FROM problem_tag pt
        JOIN problem p ON pt.problem_id = p.problem_id
        LEFT JOIN solution s ON p.problem_id = s.problem_id AND s.user_id = ?
        WHERE pt.tag = ?
        ORDER BY p.score, p.problem_id";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param('ss', $user, $tag);

$stmt->execute();

$result = $stmt->get_result();

$data = array(
    'solved' => array(),
    'unsolved' => array()
);

while ($row = $result->fetch_assoc()) {
    $problem = array(
        'id' => $row['problem_id'],
        'score' => $row['score']
    );
    if ($row['result'] == 4) {
        $data['solved'][] = $problem;
    } else {
        $data['unsolved'][] = $problem;
    }
}

function array_unique_by_key(&$array, $key)
{
    $tmp = [];
    $result = [];

    foreach ($array as $value) {
        if (!in_array($value[$key], $tmp)) {
            array_push($tmp, $value[$key]);
            array_push($result, $value);
        }
    }

    $array = $result;
}

array_unique_by_key($data['solved'], 'id');
array_unique_by_key($data['unsolved'], 'id');

// unsolved中可能有solved的题目，需要去重
$data['unsolved'] = array_filter($data['unsolved'], function ($problem) use ($data) {
    foreach ($data['solved'] as $solved) {
        if ($problem['id'] == $solved['id']) {
            return false;
        }
    }
    return true;
});

$data['unsolved'] = array_values($data['unsolved']);

$result->free();

$mysqli->close();

echo json_encode($data);
