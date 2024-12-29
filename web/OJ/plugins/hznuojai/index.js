
// TODO: shift+enter 换行后无法复原
// TODO: 移动端兼容
// TODO: 仅AI reload功能

class FloatingChatBox {
    constructor() {
        this.importFile('/OJ/plugins/hznuojai/index.css', 'link');
        this.chatCore = null;

        // 创建悬浮球
        this.floatingBall = document.createElement('div');
        this.floatingBall.classList.add('floating-ball');
        this.floatingBall.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.802 17.292s.077 -.055 .2 -.149c1.843 -1.425 3 -3.49 3 -5.789c0 -4.286 -4.03 -7.764 -9 -7.764c-4.97 0 -9 3.478 -9 7.764c0 4.288 4.03 7.646 9 7.646c.424 0 1.12 -.028 2.088 -.084c1.262 .82 3.104 1.493 4.716 1.493c.499 0 .734 -.41 .414 -.828c-.486 -.596 -1.156 -1.551 -1.416 -2.29z" /><path d="M7.5 13.5c2.5 2.5 6.5 2.5 9 0" /></svg>';

        // 创建聊天框
        this.chatBox = document.createElement('div');
        this.chatBox.classList.add('chat-box');
        this.chatBox.innerHTML = `
            <div class="chat-box-header">
                <span class="title"> Hznuoj AI</span>
                <span class="close-btn">x</span>
            </div>
            <div class="chat-box-body" id="app">
                <div class="messages-container" id="chat-messages">
                </div>
                <div class="useai-tips">内容由 AI 大模型生成，请仔细甄别。</div>
                <div class="useai-tips">目前仅支持单次提问，有问题/BUG答疑群反馈</div>
                <div class="input-area">
                    <textarea rows="1" placeholder="请输入相关问题..." class="chat-input" id="chat-input"></textarea>
                    <span class="chat-btn" id="chat-btn"></span>
                </div>
            </div>
            <div class="resizer resizer-left"></div>
            <div class="resizer resizer-top"></div>
        `;
    }

    importFile(url, type) {
        return new Promise((resolve, reject) => {
            let element;
            if (type === 'script') {
                element = document.createElement('script');
                element.type = 'text/javascript';
                element.src = url;
                element.onload = resolve;
                element.onerror = reject;
            } else if (type === 'link') {
                element = document.createElement('link');
                element.rel = 'stylesheet';
                element.href = url;
                element.onload = resolve;
                element.onerror = reject;
            } else {
                reject(new Error('Unsupported element type'));
                return;
            }
            document.head.appendChild(element);
        });
    }

    addListener() {
        // 控制对话框调整大小
        const resizers = this.chatBox.querySelectorAll('.resizer');
        resizers.forEach((resizer) => {
            resizer.addEventListener('mousedown', (e) => {
                e.preventDefault();
                const resize = (e) => {
                    if (resizer.classList.contains('resizer-left')) {
                        const width = this.chatBox.getBoundingClientRect().right - e.clientX;
                        if (width > 200 && width < 800) {
                            this.chatBox.style.width = width + 'px';
                        }
                    } else if (resizer.classList.contains('resizer-top')) {
                        const height = this.chatBox.getBoundingClientRect().bottom - e.clientY;
                        if (height > 50) {
                            this.chatBox.style.height = height + 'px';
                        }
                    }
                };

                const stopResize = () => {
                    window.removeEventListener('mousemove', resize);
                    window.removeEventListener('mouseup', stopResize);
                };
                window.addEventListener('mousemove', resize);
                window.addEventListener('mouseup', stopResize);
            });
        });


        // 为关闭按钮添加事件监听器
        const closeBtn = this.chatBox.querySelector('.close-btn');
        closeBtn.addEventListener('click', this.closeChatBox);

        // 悬浮球点击事件
        this.floatingBall.addEventListener('click', this.openChatBox);
    }

    closeChatBox = () => {
        this.chatBox.classList.remove('zoom-in');
        this.chatBox.classList.add('zoom-out');
        setTimeout(() => {
            this.chatBox.style.display = 'none';
            this.floatingBall.style.display = 'flex';
            this.floatingBall.classList.remove('zoom-out');
            this.floatingBall.classList.add('zoom-in');
        }, 380); // 动画持续时间
    };

    openChatBox = () => {
        if (this.chatBox.style.display === 'none' || this.chatBox.style.display === '') {
            this.chatBox.style.display = 'flex';
            this.chatBox.classList.remove('zoom-out');
            this.chatBox.classList.add('zoom-in');
            this.floatingBall.classList.remove('zoom-in');
            this.floatingBall.classList.add('zoom-out');
            setTimeout(() => {
                this.floatingBall.style.display = 'none';
            }, 380); // 动画持续时间
        }
    }

    openAndChat(chatContent, showContent = chatContent) {
        this.openChatBox();
        this.chatCore.sendMessage(chatContent, showContent);
    }

    // 将组件添加到页面
    async add() {
        await this.importFile('/OJ/plugins/hznuojai/dependencies/incremental-dom-min.js', 'script');
        await this.importFile('/OJ/plugins/hznuojai/dependencies/markdown-it.min.js', 'script');
        await this.importFile('/OJ/plugins/hznuojai/dependencies/markdown-it-incremental-dom.min.js', 'script');
        await this.importFile('/OJ/plugins/highlight/highlight.pack.js', 'script');
        await this.importFile('/OJ/plugins/hznuojai/dependencies/atom-one-dark.min.css', 'link');
        await this.importFile('/OJ/plugins/hznuojai/chat.js', 'script');

        document.body.appendChild(this.floatingBall);
        document.body.appendChild(this.chatBox);
        this.addListener();
        this.chatCore = new ChatCore();
    }

    // 从页面移除组件
    remove() {
        document.body.removeChild(this.floatingBall);
        document.body.removeChild(this.chatBox);
        this.chatCore = null;
    }
}

window.ChatBox = new FloatingChatBox();
