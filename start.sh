#!/bin/sh

# Créer le dossier lang avec permissions
mkdir -p /app/lang
chmod -R 777 /app/lang

# Créer un utilisateur www-data s'il n'existe pas
if ! id -u www-data > /dev/null 2>&1; then
    addgroup -g 82 -S www-data 2>/dev/null || true
    adduser -u 82 -D -S -G www-data www-data 2>/dev/null || true
fi

# Créer une config PHP-FPM
cat > /tmp/php-fpm.conf <<EOF
[global]
error_log = /dev/stderr
daemonize = no

[www]
user = www-data
group = www-data
listen = 127.0.0.1:9000
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
catch_workers_output = yes
access.log = /dev/stdout
EOF

echo "Starting PHP-FPM..."
php-fpm -y /tmp/php-fpm.conf &

sleep 3

echo "Starting Nginx..."
nginx -c /app/nginx.conf -g 'daemon off;'
