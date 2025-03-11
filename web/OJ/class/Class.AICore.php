<?php
require_once  __DIR__ . '/Class.StreamHandler.php';

class AICore
{
    private $api_url = '';
    private $streamHandler;
    private $system;
    private $tip;
    private $question;
    private $dfa = NULL;
    private $check_sensitive = TRUE;
    private $model = '';
    private $api_type = '';
    private $more_params = [];

    public function __construct($params)
    {
        $this->api_url = $params['url'];
        $this->model = $params['model'];
        $this->api_type = $params['type'];
        $this->more_params = array_diff_key($params, array_flip(['url', 'model', 'type']));
    }

    public function set_dfa(&$dfa)
    {
        $this->dfa = $dfa;
        if (!empty($this->dfa) && $this->dfa->is_available()) {
            $this->check_sensitive = TRUE;
        }
    }

    public function qa($params)
    {
        $this->system = $params['system'];
        $this->tip = $params['tip'];
        $this->question = $params['question'];
        $this->streamHandler = new StreamHandler([
            'qmd5' => md5($this->question . '' . time()),
            'api_type' => $this->api_type
        ]);
        if ($this->check_sensitive) {
            $this->streamHandler->set_dfa($this->dfa);
        }

        // 开启检测且提问包含敏感词
        if ($this->check_sensitive && $this->dfa->containsSensitiveWords($this->question)) {
            $this->streamHandler->end('您的问题不合适，AI暂时无法回答');
            return;
        }

        // 构建请求 json
        if ($this->api_type == 'generate') {
            $json = json_encode(array_merge([
                'model' => $this->model,
                'prompt' => $this->question
            ], $this->more_params));
        } else if ($this->api_type == 'chat' || $this->api_type == 'vllm-chat') {
            $json = json_encode(array_merge([
                'model' => $this->model,
                'messages' => [[
                    "role" => "system",
                    "content" => $this->system
                ], [
                    "role" => "user",
                    "content" => $this->tip
                ], [
                    "role" => "user",
                    "content" => $this->question
                ]],
            ], $this->more_params));
        }

        $headers = array(
            "Content-Type: application/json",
        );

        $this->openaiApiCall($json, $headers);
    }

    private function openaiApiCall($json, $headers)
    {
        // 注意 curl 需要开启 php 拓展
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 如果不是HTTPS请求，可以注释或删除此行
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 如果不是HTTPS请求，可以注释或删除此行
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_WRITEFUNCTION, [$this->streamHandler, 'callback']);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            file_put_contents('./log/curl.error.log', curl_error($ch) . PHP_EOL . PHP_EOL, FILE_APPEND);
        }

        curl_close($ch);
    }
}
