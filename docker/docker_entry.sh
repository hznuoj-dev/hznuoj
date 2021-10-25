#!/bin/bash

set -e -x

WEBBASE=/var/www
APACHEUSER=www-data
UPLOAD=${WEBBASE}/web/OJ/upload
DATA=/var/hznuoj/data

chown -R ${APACHEUSER} ${DATA}
chown -R ${APACHEUSER} ${UPLOAD}

service apache2 restart

if [ -z "$*" ]; then
    /bin/bash
else
    exec "$@"
fi
