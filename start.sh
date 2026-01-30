#!/bin/sh

# Créer le dossier lang avec permissions
mkdir -p /app/lang
chmod -R 777 /app/lang

# Détecter l'utilisateur courant
CURRENT_USER=$(whoami)
echo "Current user: $CURRENT_USER"

# Créer une config PHP-FPM avec l'utilisateur courant
cat > /tmp/php-fpm.conf <<EOF
[global]
error_log = /dev/stderr
daemonize = no

[www]
user = $CURRENT_USER
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
