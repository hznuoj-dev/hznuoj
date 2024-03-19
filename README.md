# HZNUOJ

[![GitHub release][gh-release-badge]][gh-release]

**HZNUOJ 是基于 [HUSTOJ](https://github.com/zhblue/hustoj) 改造而来的，遵循 GPL 协议开源**

## 部署指南

### 构建镜像

在仓库根目录下：

```bash
docker build -t hznuoj:latest -f docker/Dockerfile ./
```

等待 build 完成即可。

完成后 `docker image ls`，若有看到 hznuoj 的镜像即为成功。

如果不想 build，也可以直接拉取已经编译好的镜像

```bash
docker pull hznuoj/hznuoj:latest
```

其中，`latest` 表示 tag，可以指定 tag，比如 `0.0.3`

### 启动容器

#### DB

首先需要启动一个 DB，用 MySQL 或者 MariaDB 都可以，这里以 MySQL 5.7 为例：

```bash
docker run \
    -d \
    --restart=always \
    --name="mysql" \
    --hostname="mysql" \
    -e MYSQL_ROOT_PASSWORD=root \
    -e TZ=Asia/Shanghai \
    -p 3306:3306 \
    -v /var/docker-data/mysql-5.7/data:/var/lib/mysql \
    mysql:5.7 \
    --character-set-server=utf8mb4 \
    --collation-server=utf8mb4_unicode_ci
```

然后可以使用本 repo 里面的 [SQL](./scripts/db.sql) 文件来创建库和表。

#### Web

```bash
docker run \
    -d -it \
    --name=hznuoj \
    --restart=always \
    -p 80:80 \
    -v /var/hznuoj/static.php:/var/www/web/OJ/include/static.php \
    -v /var/hznuoj/upload:/var/www/web/OJ/upload \
    -v /var/hznuoj/data:/var/hznuoj/data \
    hznuoj/hznuoj:latest
```

- `-p 80:80` 表示把容器的 80 端口映射到宿主机的 80 端口，可自行修改
- `--name=hznuoj` 表示指定容器的名字为 `hznuoj`
- 路径挂载：
  - 因为有些文件或者目录是在容器运行过程中可能会有变动，所以我们需要把它们放在外部，然后 mount 到容器里面，不然的话，容器一重启，容器里面的文件都会恢复成初始状态
  - `-v /var/hznuoj/static.php:/var/www/web/OJ/include/static.php` 表示将宿主机上的 `/var/hznuoj/static.php` 文件挂载到容器内的 `/var/www/web/OJ/include/static.php`
    - 本 repo 下有一个 [`static.example.php`](./web/OJ/include/static.example.php)，应该只需要改一下 DB 相关的变量，然后把文件 mount 到容器中，就可以用了
    - 需要注意的是，宿主机的部分是可以改动的
      - 比如，如果把 `static.php` 放在 `/opt` 路径下，那么可以写成 `-v /opt/static.php:/var/www/web/OJ/include/static.php`
    - 容器内的路径不要变动，而且也没有变动的必要
  - `upload` 目录是用户上传的文件内容，比如题面里面的图片
  - `data` 目录是题目数据的目录
  - 如果是想开发的话，可以把 repo clone 下来之后，把 web 目录 mount 进去，容器里的路径应该是 `/var/www/web`，然后就可以在容器外部修改 web 目录下的文件，你的改动就可以在容器中的实例实时生效了

然后访问 `localhost:80` 即可。

### 进入容器

```bash
docker exec -it hznuoj bash
```

## 使用教程

默认管理员账号为 admin/@Hznu666。

出题手册见 https://www.yuque.com/weilixinlianxin/zcf10d/yfk05w

## 优势

* 更华丽的界面
* 更灵活的权限管理
* 支持多组样例
* 有封装好的 Docker 镜像，一键部署

## 界面截图

### 首页

支持提交量和访问量的统计

![index](images/index.jpg)

### 榜单

重写过的的榜单

![board](images/board.jpg)

能点开查看每题的提交状况

![board2](images/board2.jpg)

### 题目编辑界面

![problem-edit](images/problem-edit.jpg)

多样例支持

![problem-edit](images/problem-edit2.jpg)

### 权限管理界面

细分的权限分配

![privilege](images/privilege.jpg)

[gh-release-badge]: https://img.shields.io/github/release/hznuoj-dev/hznuoj.svg
[gh-release]: https://GitHub.com/hznuoj-dev/hznuoj/releases/
