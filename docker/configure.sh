#! /bin/bash

WEBBASE=/var/www
APACHEUSER=www-data
UPLOAD=${WEBBASE}/web/OJ/upload
DATA=/var/hznuoj/data

chown -R ${APACHEUSER} ${DATA}
chown -R ${APACHEUSER} ${UPLOAD}

for script in /scripts/bin/*; do
    if [ -f "$script" ]; then
        chmod 755 "$script"
        ln -s "$script" /usr/bin/
    fi
done

# Configure php
php_folder=$(echo "/etc/php/7."?"/")

# shellcheck disable=SC2034
php_version=$(basename "$php_folder")

if [ ! -d "$php_folder" ]; then
    echo "[!!] Could not find php path"
    exit 1
fi

# Set correct settings
sed -ri -e "s/^upload_max_filesize.*/upload_max_filesize = 512M/" \
    -e "s/^post_max_size.*/post_max_size = 256M/" \
    -e "s/^memory_limit.*/memory_limit = 2G/" \
    -e "s/^max_file_uploads.*/max_file_uploads = 200/" \
    -e "s#^;date\.timezone.*#date.timezone = ${CONTAINER_TIMEZONE}#" \
    "$php_folder/apache2/php.ini"
