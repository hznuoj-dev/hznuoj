<?php

class OllamaChat
{
    private $api_url = '';
    private $streamHandler;
    private $question;
    private $dfa = NULL;
    private $check_sensitive = TRUE;
    private $model = '';

    public function __construct($url, $model)
    {
        $this->api_url = $url;
        $this->model = $model;
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

        $this->question = $params['system'] . $params['question'];
        $this->streamHandler = new StreamHandler([
            'qmd5' => md5($this->question . '' . time())
        ]);
        if ($this->check_sensitive) {
            $this->streamHandler->set_dfa($this->dfa);
        }

        // 开启检测且提问包含敏感词
        if ($this->check_sensitive && $this->dfa->containsSensitiveWords($this->question)) {
            $this->streamHandler->end('您的问题不合适，AI暂时无法回答');
            return;
        }

        // 根据Ollama API的要求构建请求正文
        $json = json_encode([
            'prompt' => $this->question,
            'model' => $this->model,
        ]);

        $headers = array(
            "Content-Type: application/json",
        );

        $this->ollamaApiCall($json, $headers);
    }

    private function ollamaApiCall($json, $headers)
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
