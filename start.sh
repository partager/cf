#!/bin/sh
mkdir -p /app/lang
chmod -R 777 /app/lang
php-fpm -D
nginx -c /app/nginx.conf -g 'daemon off;'
