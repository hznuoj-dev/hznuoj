PROJECT_DIR="/Users/daizeyao/project"

docker pull hznuoj/hznuoj:latest
# mac m1 不兼容 mysql:5.7 需要使用 biarms/mysql:5.7.30-linux-arm64v8
docker pull biarms/mysql:5.7.30-linux-arm64v8

# 包含 web/OJ/include/static.php
docker run \
    -d -it \
    --name=hznuoj \
    --restart=always \
    -p 8877:80 \
    -v /var/hznuoj/data:/var/hznuoj/data \
    -v "$PROJECT_DIR/hznuoj/web:/var/www/web" \
    hznuoj/hznuoj:latest

docker run \
    -d \
    --restart=always \
    --name="mysql" \
    --hostname="mysql" \
    -e MYSQL_ROOT_PASSWORD=root \
    -e TZ=Asia/Shanghai \
    -p 3306:3306 \
    -v "$PROJECT_DIR/hznuoj/scripts/db.sql:/db.sql" \
    biarms/mysql:5.7.30-linux-arm64v8 \
    --character-set-server=utf8mb4 \
    --collation-server=utf8mb4_unicode_ci
