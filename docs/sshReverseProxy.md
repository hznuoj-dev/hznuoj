## 使用方法

**client为客户端(受控端)**

**server为服务端(控制端)**

client $\rightarrow$ server反代

```bash
ssh -fCNR 7280:localhost:22 root@110.42.187.143
```

查看是否启动

```bash
ps aux |grep ssh
```

server $\rightarrow$ client正代

```bash
ssh -fCNL *:1234:localhost:7280 localhost
```

完成后只需要访问server转发端口1234即可连接client

```bash
ssh -p1234 root@110.42.187.143 
```

为了实现稳定自动重连

需要在client执行：

```bash
ssh-copy-id root@110.42.187.143
apt install autossh
autossh -M 7281 -fCNR 7280:localhost:22 root@110.42.187.143
```

在client配置开机自启：

编辑```vim /etc/profile.d/startProxy.sh```

添加内容

```bash
#! /bin/bash

service ssh start
autossh -M 7281 -fCNR 7280:localhost:22 root@110.42.187.143
```

```chomod +x /etc/profile.d/startProxy.sh```

设置自启 ```vim /etc/rc.local```

添加内容 ```sudo /etc/profile.d/startProxy.sh```

```sudo chmod +x /etc/rc.local```

## 相关说明

```bash
-f 后台执行ssh指令
-C 允许压缩数据
-N 不执行远程指令
-R 将远程主机(服务器)的某个端口转发到本地端指定机器的指定端口
-L 将本地机(客户机)的某个端口转发到远端指定机器的指定端口
-p 指定远程主机的端口
```

```bash
反向代理：ssh -fCNR [serverIP或省略]:[server端口]:[client的IP]:[client端口] [登陆server的用户名@serverIP]
```

```bash
正向代理：ssh -fCNL [clientIP或省略]:[client端口]:[server的IP]:[server端口] [登陆server的用户名@serverIP]
```

其中110.42.187.143为server公网IP

autossh中的-M参数是用户监视该ssh会话的端口