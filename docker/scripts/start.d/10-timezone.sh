#! /bin/sh -eu

ln -snf /usr/share/zoneinfo/"${CONTAINER_TIMEZONE}" /etc/localtime
echo "${CONTAINER_TIMEZONE}" >/etc/timezone
dpkg-reconfigure -f noninteractive tzdata
echo "[ok] Container timezone set to: ${CONTAINER_TIMEZONE}"

# Configure php
php_folder=$(echo "/etc/php/7."?"/")

cat >"$php_folder/apache2/conf.d/99-timezone.ini" <<-EOF
[Date]
date.timezone = ${CONTAINER_TIMEZONE}
EOF
