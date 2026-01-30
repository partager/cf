#!/bin/sh
mkdir -p /app/lang
chmod -R 777 /app/lang
php-fpm -y /app/php-fpm.conf -D
nginx -c /app/nginx.conf -g 'daemon off;'
