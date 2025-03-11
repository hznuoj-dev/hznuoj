class ChatCore {
    constructor() {
        this.msgDiv = document.getElementById('chat-messages');
        this.input = document.getElementById('chat-input');
        this.chatButton = document.getElementById('chat-btn');
        this.resetButton = document.getElementById('reset-btn');
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
                const codeIndex = parseInt(Date.now()) + Math.floor(Math.random() * 10000000);
                let html = `<button class="copy-btn am-icon-copy" type="button" data-clipboard-action="copy" data-clipboard-target="#copy${codeIndex}" data-am-popover="{content: '复制代码', trigger: 'hover focus'}"></button>`;
                const linesLength = str.split(/\n/).length - 1;
                // 生成行号
                let linesNum = '<span aria-hidden="true" class="line-numbers-rows">';
                for (let index = 0; index < linesLength; index++) {
                    linesNum = linesNum + `<span></span>`;
                }
                linesNum += '</span>';
                const copyDiv = `<div style="position:absolute;top:-9999px;left:-9999px;z-index:-9999;white-space:pre-wrap;" id="copy${codeIndex}">${this.md.utils.escapeHtml(str).replace(/<\/textarea>/g, '&lt;/textarea>')}</div>`;
                if (lang && hljs.getLanguage(lang)) {
                    try {
                        const preCode = hljs.highlight(lang, str, true).value;
                        html = html + preCode;
                        if (linesLength) {
                            html += '<b class="name">' + lang + '</b>';
                        }
                        return `<pre class="hljs"><code>${html}</code>${linesNum}</pre>${copyDiv}`;
                    } catch (__) {
                        console.error(__);
                    }
                }
                const content = this.md.utils.escapeHtml(str);
                html = html + content;
                return `<pre class="hljs"><code>${html}</code></pre>${copyDiv}`;
            }
        }).use(markdownitIncrementalDOM);

        this.clipboard = new ClipboardJS('.copy-btn');

        this.swapToSend();
        this.addEventListeners();
    }

    resetStatus() {
        this.isStop = false;
        this.typingTimer && clearInterval(this.typingTimer);
        this.answerContent = '';
        this.answerWords = [];
        this.answers = {};
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
    }

    addEventListeners() {
        this.input.addEventListener('input', (e) => {
                this.adjustInputHeight();
        });
        this.input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                this.sendMessage(this.input.value);
            }
        });
        this.resetButton.classList.add('am-icon-rotate-right');
        this.resetButton.onclick = () => {
            this.qaIdx = 0;
            this.msgDiv.innerHTML = '';
            this.resetStatus();
            this.swapToSend();
            this.adjustInputHeight();
        }
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
        this.input.style.height = 'auto';
        if (this.input.value) {
            this.input.style.height = this.input.scrollHeight + 'px';
        } else {
            this.input.style.height = 'auto';
        }
    }

    sendMessage(chatContent, showContent = chatContent, systemInstruction = '') {
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
        this.getAnswer(systemInstruction, chatContent);
    }

    sendStop() {
        this.isStop = true;
        this.swapToSend();
    }

    getAnswer(systemInstruction, inputValue) {
        this.isStop = false;
        inputValue = encodeURIComponent(inputValue.replace(/\+/g, '{[$add$]}'));
        let url = `/OJ/api/chat.php?q=${inputValue}${systemInstruction && `&systemInstruction=${encodeURIComponent(systemInstruction)}`}`;
        this.eventSource = new EventSource(url);

        let connectionTimeout = null;

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
            this.qaIdx += 1;
            this.isStop = false;
            clearInterval(this.typingTimer);
            this.answerContent = '';
            this.answerWords = [];
            this.answers = [];
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
                    if (part.length > 0) {
                        this.answerContent += `${part}`;
                        // this.answerContent += `<k class="fade-in">${part}</k>`;
                    }
                }
            });
        }

        this.patchMd(this.answers[this.qaIdx], this.answerContent + (this.codeStart ? '\n\n```' : ''));

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
