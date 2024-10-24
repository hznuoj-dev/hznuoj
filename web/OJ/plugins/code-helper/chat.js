const msgDiv = document.getElementById('chat-messages');
const input = document.getElementById('chat-input');
const chatButton = document.getElementById('chat-btn');
let eventSource = null;
let qaIdx = 0, answers = {}, answerContent = '', answerWords = [];
let codeStart = false, inlineCodeStart = false, lastWord = '', lastLastWord = '';
let typingTimer = null, typing = false, typingIdx = 0, contentIdx = 0, contentEnd = false;
let isStop = false;

const md = markdownit({
    html: true, // 不仅会解析普通文本，同样解析 HTML 标签
    highlight: function (str, lang) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return '<pre class="hljs"><code>' +
                    hljs.highlight(lang, str, true).value +
                    '</code></pre>';
            } catch (__) { console.error(__); }
        }
        return '<pre class="hljs"><code>' + md.utils.escapeHtml(str) + '</code></pre>';
    }
}).use(markdownitIncrementalDOM);

// 节流函数
function throttle(func, delay) {
    let lastCall = new Date().getTime();
    return function (...args) {
        const now = new Date().getTime();
        if (now - lastCall < delay) {
            return;
        }
        lastCall = now;
        return func.apply(this, args);
    };
}

// 设置节流延迟（例如200毫秒）
const throttledScrollToBottom = throttle((isBottom) => {
    if (isBottom) {
        msgDiv.scrollTop = msgDiv.scrollHeight;
    }
}, 49);

function patchMd(node, content) {
    IncrementalDOM.patch(
        node,
        md.renderToIncrementalDOM(content)
    );
    // console.log(content);
    // Token 流
    // console.log(md.parse(content));
}

swapToSend();

function swapToStop() {
    // TODO：切换时的动画、闲置时动画
    isStop = false;
    chatButton.classList.remove('am-icon-paper-plane');
    chatButton.classList.add('am-icon-stop');
    chatButton.onclick = sendStop;
}

function swapToSend() {
    isStop = true;
    chatButton.classList.add('am-icon-paper-plane');
    chatButton.classList.remove('am-icon-stop');
    chatButton.onclick = sendMessage;
}

//在输入时和获取焦点后自动调整输入框高度
input.addEventListener('input', adjustInputHeight);
input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        sendMessage();
    }
})

// 自动调整输入框高度
function adjustInputHeight() {
    input.style.height = 'auto'; // 将高度重置为 auto
    input.style.height = (input.scrollHeight) + 'px';
}

function sendMessage() {
    const inputValue = input.value;
    if (!inputValue) {
        return;
    }
    if (inputValue.length > 1000) {
        alert('输入内容过长，请控制在1000字以内');
        input.value = '';
        return;
    }

    const question = document.createElement('div');
    question.setAttribute('class', 'message question');
    question.setAttribute('id', 'question-' + qaIdx);
    patchMd(question, inputValue);
    msgDiv.appendChild(question);

    const answer = document.createElement('div');
    answer.setAttribute('class', 'message answer');
    answer.setAttribute('id', 'answer-' + qaIdx);
    patchMd(answer, 'AI思考中……');
    msgDiv.appendChild(answer);

    answers[qaIdx] = document.getElementById('answer-' + qaIdx);

    input.value = '';
    input.disabled = true;
    chatButton.disabled = true;
    adjustInputHeight();
    throttledScrollToBottom(true);

    // 每 50ms 轮询一次，打印答案数组
    typingTimer = setInterval(typingWords, 50);

    swapToStop();
    getAnswer(inputValue);
}

function sendStop() {
    isStop = true;
    swapToSend();
}

function getAnswer(inputValue) {
    isStop = false;
    inputValue = encodeURIComponent(inputValue.replace(/\+/g, '{[$add$]}'));
    const url = "./chat.php?q=" + inputValue;
    eventSource = new EventSource(url);

    let connectionTimeout = null;

    eventSource.addEventListener("open", (event) => {
        clearTimeout(connectionTimeout);
        isStop = false;
        connectionTimeout = setTimeout(() => {
            console.error("连接超时");
            isStop = true;
            eventSource.close();
        }, 20000);
        console.log("连接已建立", JSON.stringify(event));
    });

    eventSource.addEventListener("message", (event) => {
        try {
            // 获取服务端推送的数据，并放到答案数组中（待打印）
            let result = JSON.parse(event.data);
            if (result.time && result.content) {
                answerWords.push(result.content);
                contentIdx += 1;
            }
        } catch (error) {
            console.log(error);
        }
    });

    eventSource.addEventListener("error", (event) => {
        console.error("发生错误：", JSON.stringify(event));
        isStop = true;
    });

    eventSource.addEventListener("close", (event) => {
        console.log("连接已关闭", JSON.stringify(event.data));
        eventSource.close();
        contentEnd = true;
        clearTimeout(connectionTimeout);
        connectionTimeout = null;
        console.log((new Date().getTime()), 'answer end');
    });
}

function typingWords() {
    if (contentEnd && contentIdx == typingIdx || isStop) {
        isStop = false;
        clearInterval(typingTimer);
        answerContent = '';
        answerWords = [];
        answers = [];
        qaIdx += 1;
        typingIdx = 0;
        contentIdx = 0;
        contentEnd = false;
        lastWord = '';
        lastLastWord = '';
        input.disabled = false;
        chatButton.disabled = false;
        eventSource && eventSource.close();
        eventSource = null;
        codeStart = false;
        swapToSend();
        console.log((new Date().getTime()), 'typing end');
        return;
    }
    // 如果输出完成或者正在输出，typing->锁
    if (contentIdx <= typingIdx || typing) {
        return;
    }
    typing = true;

    if (!answers[qaIdx]) {
        answers[qaIdx] = document.getElementById('answer-' + qaIdx);
    }

    // 输出代码块的时候，在未输出完时需要判断是否需要补齐，需要补上末尾的代码块结束符
    const content = answerWords[typingIdx];
    if (content.indexOf('`') != -1) {
        if (content.indexOf('```') != -1) {
            codeStart = !codeStart;
        } else if (content.indexOf('``') != -1 && (lastWord + content).indexOf('```') != -1) {
            codeStart = !codeStart;
        } else if (content.indexOf('`') != -1 && (lastLastWord + lastWord + content).indexOf('```') != -1) {
            codeStart = !codeStart;
        }
    }

    let isCodeTag = content === '```' || content === '``' || content === '`';

    if (codeStart || inlineCodeStart || isCodeTag) {
        // console.log(codeStart, inlineCodeStart, isCodeTag);
        answerContent += content;
    } else {
        let parts = content.split(/(\r?\n)/g);
        // 遍历分割后的数组，处理每个部分
        parts.forEach((part, index) => {
            if (part === '\n' || part === '\r\n' || part.indexOf('`') != -1) {
                // 如果是换行符，直接添加到answerContent中
                answerContent += part;
            } else {
                // 如果不是换行符，用span包裹后添加到answerContent中
                if (part.trim().length > 0) {
                    answerContent += `<k class="fade-in">${part}</k>`;
                }
            }
        });
    }

    patchMd(answers[qaIdx], answerContent + (codeStart ? '\n\n```' : ''));

    if (content.indexOf('`') != -1 && (lastWord + content).indexOf('``') === -1) {
        inlineCodeStart = !inlineCodeStart;
    }
    lastLastWord = lastWord;
    lastWord = content;
    // TODO：代码段中字符出现的淡入动画

    let isBottom = ((msgDiv.scrollHeight - msgDiv.clientHeight) - msgDiv.scrollTop) < 50;
    // console.log((msgDiv.scrollHeight - msgDiv.clientHeight) - msgDiv.scrollTop);
    throttledScrollToBottom(isBottom);

    typingIdx += 1;
    typing = false;
}
