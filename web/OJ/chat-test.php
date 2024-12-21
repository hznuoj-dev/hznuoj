<?php
// echo "Test Chat API<br><br>" . PHP_EOL;
// $endpoint = 'http://172.22.233.71:8081/OJ/chat.php';
// $numberOfRequests = 50; // Number of concurrent requests
// $question = '写个C语言的累加程序';

// $multiHandle = curl_multi_init();
// $curlHandles = [];

// for ($i = 0; $i < $numberOfRequests; $i++) {
//     $curlHandles[$i] = curl_init();
//     curl_setopt($curlHandles[$i], CURLOPT_URL, $endpoint . '?q=' . urlencode($question));
//     curl_setopt($curlHandles[$i], CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($curlHandles[$i], CURLOPT_HTTPHEADER, [
//         'Accept: text/event-stream',
//         'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,zh-TW;q=0.6,en-US;q=0.5',
//         'Cache-Control: no-cache',
//         'Connection: keep-alive',
//         'Pragma: no-cache',
//         'Referer: http://172.22.233.71:8081/OJ/',
//         'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0'
//     ]);
//     curl_setopt($curlHandles[$i], CURLOPT_COOKIE, 'PHPSESSID=dc9ja08djppauf1m5uvpcb73b5');
//     curl_setopt($curlHandles[$i], CURLOPT_SSL_VERIFYPEER, false); // --insecure option
//     curl_multi_add_handle($multiHandle, $curlHandles[$i]);
// }

// $running = null;
// do {
//     curl_multi_exec($multiHandle, $running);
//     curl_multi_select($multiHandle);
// } while ($running > 0);

// foreach ($curlHandles as $index => $handle) {
//     $response = curl_multi_getcontent($handle);
//     echo "Request " . ($index + 1) . ": Response length - " . strlen($response) . " characters<br>";
//     $info = curl_getinfo($handle);
//     echo "Request " . ($index + 1) . ": Time taken - " . $info['total_time'] . " seconds<br><br><br>";
//     curl_multi_remove_handle($multiHandle, $handle);
//     curl_close($handle);
// }

// curl_multi_close($multiHandle);
