<?php

class StreamHandler
{
    private $data_buffer; //缓存，有可能一条data被切分成两部分了，无法解析json，所以需要把上一半缓存起来
    private $counter; //数据接收计数器
    private $qmd5; //问题md5
    private $chars; //字符数组，开启敏感词检测时用于缓存待检测字符
    private $punctuation; //停顿符号
    private $dfa = NULL;
    private $check_sensitive = FALSE;

    public function __construct($params)
    {
        $this->data_buffer = '';
        $this->counter = 0;
        $this->qmd5 = $params['qmd5'] ?? time();
        $this->chars = [];
        $this->punctuation = ['，', '。', '；', '？', '！', '……'];
    }

    public function set_dfa(&$dfa)
    {
        $this->dfa = $dfa;
        if (!empty($this->dfa) && $this->dfa->is_available()) {
            $this->check_sensitive = TRUE;
        }
    }

    public function callback($ch, $data)
    {
        $this->counter += 1;
        file_put_contents('./log/data.' . $this->qmd5 . '.log', $this->counter . '==' . $data . PHP_EOL . '--------------------' . PHP_EOL, FILE_APPEND);

        // echo $data;

        // $result = json_decode($data, TRUE);

        // if (is_array($result)) {
        //     $this->end('openai 请求错误：' . json_encode($result));
        //     return strlen($data);
        // }

        // 0、把上次缓冲区内数据拼接上本次的data
        $buffer = $this->data_buffer . "\n" . $data;
        $this->data_buffer = '';

        $lines = explode("\n", $buffer);
        $lines = array_filter($lines, function ($line) {
            return trim($line) !== '';
        });

        foreach ($lines as $li => $line) {
            // $this->data_buffer = $buffer;

            $line_data = json_decode($line, TRUE);

            if ($line_data['done'] == true) {
                //数据传输结束
                $this->data_buffer = '';
                $this->counter = 0;
                $this->sensitive_check();
                $this->end();
                break;
            }

            $this->sensitive_check($line_data['response']);
        }

        return strlen($data);
    }

    private function sensitive_check($content = NULL)
    {
        // 如果不检测敏感词，则直接返回给前端
        if (!$this->check_sensitive) {
            $this->write($content);
            return;
        }
        //每个 content 都检测是否包含换行或者停顿符号，如有，则成为一个新行
        if (!$this->has_pause($content)) {
            $this->chars[] = $content;
            return;
        }
        $this->chars[] = $content;
        $content = implode('', $this->chars);
        if ($this->dfa->containsSensitiveWords($content)) {
            $content = $this->dfa->replaceWords($content);
            $this->write($content);
        } else {
            foreach ($this->chars as $char) {
                $this->write($char);
            }
        }
        $this->chars = [];
    }

    private function has_pause($content)
    {
        if ($content == NULL) {
            return TRUE;
        }
        $has_p = false;
        if (is_numeric(strripos(json_encode($content), '\n'))) {
            $has_p = true;
        } else {
            foreach ($this->punctuation as $p) {
                if (is_numeric(strripos($content, $p))) {
                    $has_p = true;
                    break;
                }
            }
        }
        return $has_p;
    }

    private function write($content = NULL, $flush = TRUE)
    {
        if ($content != NULL) {
            echo 'data: ' . json_encode(['time' => date('Y-m-d H:i:s'), 'content' => $content], JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL;
        }

        if ($flush) {
            flush();
        }
    }

    public function end($content = NULL)
    {
        if (!empty($content)) {
            $this->write($content, FALSE);
        }

        echo 'retry: 86400000' . PHP_EOL;
        echo 'event: close' . PHP_EOL;
        echo 'data: Connection closed' . PHP_EOL . PHP_EOL;
        flush();
    }
}
