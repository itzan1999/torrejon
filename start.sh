#!/bin/sh

# Ejecutar migraciones
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan db:seed --class=DatosPruebaSeeder --force
php artisan db:seed --class=DatosLeche --force

echo "=== Iniciando php-fpm ==="
php-fpm85 --allow-to-run-as-root -D
sleep 3

echo "=== Procesos corriendo ==="
ps aux

echo "=== Sockets ==="
ls -la /var/run/*.sock 2>/dev/null

echo "=== Config php-fpm ==="
cat /etc/php85/php-fpm.d/www.conf | grep -E "listen|user|group"

echo "=== Iniciando nginx ==="
nginx -t
nginx -g 'daemon off;'


nginx -g 'daemon off;'