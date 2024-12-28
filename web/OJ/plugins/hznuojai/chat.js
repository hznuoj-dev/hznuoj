class ChatCore {
    constructor() {
        this.msgDiv = document.getElementById('chat-messages');
        this.input = document.getElementById('chat-input');
        this.chatButton = document.getElementById('chat-btn');
        this.eventSource = null;
        this.qaIdx = 0;
        this.answers = {};
        this.answerContent = '';
        this.answerWords = [];
        this.codeStart = false;
        this.inlineCodeStart = false;
        this.lastWord = '';
        this.lastLastWord = '';
        this.typingTimer = null;
        this.typing = false;
        this.typingIdx = 0;
        this.contentIdx = 0;
        this.contentEnd = false;
        this.isStop = false;

        this.md = markdownit({
            html: true,
            highlight: (str, lang) => {
                if (lang && hljs.getLanguage(lang)) {
                    try {
                        return '<pre class="hljs"><code>' + hljs.highlight(lang, str, true).value + '</code></pre>';
                    } catch (__) {
                        console.error(__);
                    }
                }
                return '<pre class="hljs"><code>' + this.md.utils.escapeHtml(str) + '</code></pre>';
            }
        }).use(markdownitIncrementalDOM);

        this.swapToSend();
        this.addEventListeners();
    }

    addEventListeners() {
        this.input.addEventListener('input', this.adjustInputHeight.bind(this));
        this.input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                this.sendMessage(this.input.value);
            }
        });
    }

    throttle(func, delay) {
        let lastCall = new Date().getTime();
        return (...args) => {
            const now = new Date().getTime();
            if (now - lastCall < delay) {
                return;
            }
            lastCall = now;
            return func.apply(this, args);
        };
    }

    throttledScrollToBottom = this.throttle((isBottom) => {
        if (isBottom) {
            this.msgDiv.scrollTop = this.msgDiv.scrollHeight;
        }
    }, 49);

    patchMd(node, content) {
        IncrementalDOM.patch(node, this.md.renderToIncrementalDOM(content));
    }

    swapToStop() {
        this.isStop = false;
        this.chatButton.classList.remove('am-icon-paper-plane');
        this.chatButton.classList.add('am-icon-stop');
        this.chatButton.onclick = () => this.sendStop();
    }

    swapToSend() {
        this.isStop = true;
        this.chatButton.classList.add('am-icon-paper-plane');
        this.chatButton.classList.remove('am-icon-stop');
        this.chatButton.onclick = () => this.sendMessage(this.input.value);
        this.input.focus();
    }

    adjustInputHeight() {
        this.input.style.height = this.input.scrollHeight + 'px';
    }

    sendMessage(chatContent, showContent = chatContent) {
        if (!chatContent) return;
        if (chatContent.length > 3000) {
            alert('输入内容过长，请控制在3000字以内');
            this.input.value = '';
            return;
        }

        const question = document.createElement('div');
        question.setAttribute('class', 'message question');
        question.setAttribute('id', 'question-' + this.qaIdx);
        this.patchMd(question, showContent);
        this.msgDiv.appendChild(question);

        const answer = document.createElement('div');
        answer.setAttribute('class', 'message answer');
        answer.setAttribute('id', 'answer-' + this.qaIdx);
        this.patchMd(answer, 'AI思考中……');
        this.msgDiv.appendChild(answer);

        this.answers[this.qaIdx] = document.getElementById('answer-' + this.qaIdx);

        this.input.value = '';
        this.input.disabled = true;
        this.chatButton.disabled = true;
        this.adjustInputHeight();
        this.throttledScrollToBottom(true);

        this.typingTimer = setInterval(() => this.typingWords(), 50);

        this.swapToStop();
        this.getAnswer(chatContent);
    }

    sendStop() {
        this.isStop = true;
        this.swapToSend();
    }

    getAnswer(inputValue) {
        this.isStop = false;
        inputValue = encodeURIComponent(inputValue.replace(/\+/g, '{[$add$]}'));
        const url = "./chat.php?q=" + inputValue;
        this.eventSource = new EventSource(url);

        let connectionTimeout = null;

        // TODO: VLLM
        // TODO: 两张显卡加载同一个大模型，并且负载均衡；如果行不通，就通过设置参数的形式跑两个不同的大模型并手动分配

        // 同一个客户端只能同时建立同一个 EventSource 连接
        this.eventSource.addEventListener("open", (event) => {
            clearTimeout(connectionTimeout);
            this.isStop = false;
            connectionTimeout = setTimeout(() => {
                console.error("连接超时");
                this.isStop = true;
                this.eventSource.close();
            }, 20000);
            // console.log("连接已建立", JSON.stringify(event));
        });

        this.eventSource.addEventListener("message", (event) => {
            try {
                let result = JSON.parse(event.data);
                if (result.time && result.content) {
                    this.answerWords.push(result.content);
                    this.contentIdx += 1;
                } else if (["499", "498", "497"].includes(result.code)) {
                    console.error(result.error);
                    const messages = {
                        "499": '请登录后再使用 AI 功能',
                        "498": 'AI 服务正在维护中ing 请稍后再试',
                        "497": '请不要频繁发起对话'
                    };
                    this.patchMd(this.answers[this.qaIdx], messages[result.code]);
                    this.isStop = true;
                    this.eventSource.close();
                }
            } catch (error) {
                console.log(error);
            }
        });

        this.eventSource.addEventListener("error", (event) => {
            console.error("发生错误：", JSON.stringify(event));
            this.patchMd(this.answers[this.qaIdx], 'AI 服务正在维护中ing 请稍后再试');
            this.isStop = true;
        });

        this.eventSource.addEventListener("close", (event) => {
            // console.log("连接已关闭", JSON.stringify(event.data));
            this.eventSource.close();
            this.contentEnd = true;
            clearTimeout(connectionTimeout);
            connectionTimeout = null;
            // console.log((new Date().getTime()), 'answer end');
        });
    }

    typingWords() {
        if (this.contentEnd && this.contentIdx == this.typingIdx || this.isStop) {
            this.isStop = false;
            clearInterval(this.typingTimer);
            this.answerContent = '';
            this.answerWords = [];
            this.answers = [];
            this.qaIdx += 1;
            this.typingIdx = 0;
            this.contentIdx = 0;
            this.contentEnd = false;
            this.lastWord = '';
            this.lastLastWord = '';
            this.input.disabled = false;
            this.chatButton.disabled = false;
            this.eventSource && this.eventSource.close();
            this.eventSource = null;
            this.codeStart = false;
            this.swapToSend();
            // console.log((new Date().getTime()), 'typing end');
            return;
        }
        if (this.contentIdx <= this.typingIdx || this.typing) {
            return;
        }
        this.typing = true;

        if (!this.answers[this.qaIdx]) {
            this.answers[this.qaIdx] = document.getElementById('answer-' + this.qaIdx);
        }

        const content = this.answerWords[this.typingIdx];
        if (content.indexOf('`') != -1) {
            if (content.indexOf('```') != -1) {
                this.codeStart = !this.codeStart;
            } else if (content.indexOf('``') != -1 && (this.lastWord + content).indexOf('```') != -1) {
                this.codeStart = !this.codeStart;
            } else if (content.indexOf('`') != -1 && (this.lastLastWord + this.lastWord + content).indexOf('```') != -1) {
                this.codeStart = !this.codeStart;
            }
        }

        let isCodeTag = content === '```' || content === '``' || content === '`';

        if (this.codeStart || this.inlineCodeStart || isCodeTag) {
            this.answerContent += content;
        } else {
            let parts = content.split(/(\r?\n)/g);
            parts.forEach((part) => {
                if (part === '\n' || part === '\r\n' || part.indexOf('`') != -1) {
                    this.answerContent += part;
                } else {
                    if (part.trim().length > 0) {
                        this.answerContent += `<k class="fade-in">${part}</k>`;
                    }
                }
            });
        }

        this.patchMd(this.answers[this.qaIdx], this.answerContent + (this.codeStart ? '\n\n```' : ''));

        // TODO:除了代码段和行内代码，还有很多需要包裹的 比如加粗等需要处理
        if (content.indexOf('`') != -1 && (this.lastWord + content).indexOf('``') === -1) {
            this.inlineCodeStart = !this.inlineCodeStart;
        }
        this.lastLastWord = this.lastWord;
        this.lastWord = content;

        let isBottom = ((this.msgDiv.scrollHeight - this.msgDiv.clientHeight) - this.msgDiv.scrollTop) < 60;
        this.throttledScrollToBottom(isBottom);

        this.typingIdx += 1;
        this.typing = false;
    }
}
