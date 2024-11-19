class FloatingChatBox {
    constructor() {
        // 动态引入CSS文件
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '/OJ/plugins/code-helper/index.css';
        document.head.appendChild(link);

        // 创建悬浮球
        this.floatingBall = document.createElement('div');
        this.floatingBall.classList.add('floating-ball');
        this.floatingBall.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.802 17.292s.077 -.055 .2 -.149c1.843 -1.425 3 -3.49 3 -5.789c0 -4.286 -4.03 -7.764 -9 -7.764c-4.97 0 -9 3.478 -9 7.764c0 4.288 4.03 7.646 9 7.646c.424 0 1.12 -.028 2.088 -.084c1.262 .82 3.104 1.493 4.716 1.493c.499 0 .734 -.41 .414 -.828c-.486 -.596 -1.156 -1.551 -1.416 -2.29z" /><path d="M7.5 13.5c2.5 2.5 6.5 2.5 9 0" /></svg>';

        // 创建聊天框
        this.chatBox = document.createElement('div');
        this.chatBox.classList.add('chat-box');
        this.chatBox.innerHTML = `
            <div class="chat-box-header">
                <span class="title"> Code Helper</span>
                <span class="close-btn">x</span>
            </div>
            <div class="chat-box-body" id="app">
                <div class="messages-container" id="chat-messages">
                </div>
                <div class="input-area">
                    <textarea rows="1" placeholder="请输入相关问题..." class="chat-input" id="chat-input"></textarea>
                    <span class="chat-btn" id="chat-btn"></span>
                </div>
            </div>
        `;

        // 为关闭按钮添加事件监听器
        const closeBtn = this.chatBox.querySelector('.close-btn');
        closeBtn.addEventListener('click', () => {
            this.chatBox.classList.remove('zoom-in');
            this.chatBox.classList.add('zoom-out');
            setTimeout(() => {
                this.chatBox.style.display = 'none';
                this.floatingBall.style.display = 'flex';
                this.floatingBall.classList.remove('zoom-out');
                this.floatingBall.classList.add('zoom-in');
            }, 390); // 动画持续时间
        });

        // 悬浮球点击事件
        this.floatingBall.addEventListener('click', () => {
            if (this.chatBox.style.display === 'none' || this.chatBox.style.display === '') {
                this.chatBox.style.display = 'flex';
                this.chatBox.classList.remove('zoom-out');
                this.chatBox.classList.add('zoom-in');
                this.floatingBall.classList.remove('zoom-in');
                this.floatingBall.classList.add('zoom-out');
                setTimeout(() => {
                    this.floatingBall.style.display = 'none';
                }, 390); // 动画持续时间
            }
        });
    }

    // 将组件添加到页面
    add() {
        document.body.appendChild(this.floatingBall);
        document.body.appendChild(this.chatBox);
        let script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = '/OJ/plugins/code-helper/chat.js';
        document.body.appendChild(script);
    }

    // 从页面移除组件
    remove() {
        document.body.removeChild(this.floatingBall);
        document.body.removeChild(this.chatBox);
    }
}
