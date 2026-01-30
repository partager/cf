#!/bin/sh

# 1. Permissions pour l'installateur (CRUCIAL)
chmod 777 /app
mkdir -p /app/lang
chmod -R 777 /app/lang

# 2. Création de l'utilisateur www-data
if ! id -u www-data > /dev/null 2>&1; then
    addgroup -g 82 -S www-data 2>/dev/null || true
    adduser -u 82 -D -S -G www-data www-data 2>/dev/null || true
fi

# 3. Démarrage avec votre fichier de config corrigé
echo "Starting PHP-FPM..."
php-fpm -y /app/php-fpm.conf &

# Pause de sécurité
sleep 2

echo "Starting Nginx..."
nginx -c /app/nginx.conf -g 'daemon off;'
