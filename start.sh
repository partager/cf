#!/bin/sh
# On donne les permissions larges au dossier racine pour que l'installateur puisse créer config.php
chmod 777 /app
chmod 777 /app/lang

# Créer l'utilisateur www-data (standard pour PHP)
if ! id -u www-data > /dev/null 2>&1; then
    addgroup -g 82 -S www-data 2>/dev/null || true
    adduser -u 82 -D -S -G www-data www-data 2>/dev/null || true
fi

# Démarrage standard
echo "Starting PHP-FPM..."
php-fpm -D
echo "Starting Nginx..."
nginx -c /app/nginx.conf -g 'daemon off;'
