<?php

require_once('./include/cache_start.php');

date_default_timezone_set('PRC');
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);
while (@ob_end_flush()) {
}
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');

// 未登录禁止使用
if (isset($_SESSION['user_id'])) {
    $uid = $mysqli->real_escape_string($_SESSION['user_id']);
} else {
    echo "data: " . json_encode(["code" => "499", "error" => "No user"]) . "\n\n";
    flush();
    exit();
}

// 检查请求头，确保是通过 EventSource 发起的请求
if ($_SERVER['HTTP_ACCEPT'] !== 'text/event-stream') {
    header('HTTP/1.1 403 Forbidden');
    echo "This endpoint can only be accessed via EventSource.";
    exit();
}

// TODO：查询是否禁用AI功能

// 查询当前用户是否正处于AI对话中

$currentTime = time();
if (isset($_SESSION['last_chat_time'])) {
    $lastChatTime = $_SESSION['last_chat_time'];
    if (($currentTime - $lastChatTime) < 5) {
        echo "data: " . json_encode(["code" => "498", "error" => "In Conversation now"]) . "\n\n";
        flush();
        exit();
    }
}
$_SESSION['last_chat_time'] = $currentTime;


require_once './include/static.php';
require './class/Class.DFA.php';
require './class/Class.StreamHandler.php';
require './class/Class.OllamaChat.php';

echo 'data: ' . json_encode(['time' => date('Y-m-d H:i:s'), 'content' => '']) . PHP_EOL . PHP_EOL;
flush();

$question = urldecode($_GET['q'] ?? '');
if (empty($question)) {
    echo "event: close" . PHP_EOL;
    echo "data: Connection closed" . PHP_EOL . PHP_EOL;
    flush();
    exit();
}
$question = str_ireplace('{[$add$]}', '+', $question);

// api 和 模型选择
$chat = new OllamaChat(
    "http://$AI_HOST:11434/api/generate",
    "$AI_MODEL"
);

$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$dfa = new DFA([
    'words_file' => "$DOCUMENT_ROOT/OJ/plugins/hznuojai/dict.txt",
]);
$chat->set_dfa($dfa);


// 开始提问
$chat->qa([
    'system' => '你是杭州师范大学在线测评系统的智能代码助手，你负责且只负责回答代码相关的问题，并且使用中文回答，代码部分使用```包围，下面是问题：',
    'question' => $question,
]);


// echo "*************************************" . PHP_EOL;
unset($_SESSION['last_chat_time']);

if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
