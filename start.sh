#!/bin/sh

# Ejecutar migraciones
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan db:seed --class=DatosPruebaSeeder --force
php artisan db:seed --class=DatosLecheSeeder --force


# Arrancar php-fpm85 en segundo plano
php-fpm85 --allow-to-run-as-root -D
sleep 3
echo "=== Sockets disponibles ==="
ls /var/run/*.sock 2>/dev/null || echo "No hay sockets en /var/run/"
ls /tmp/*.sock 2>/dev/null || echo "No hay sockets en /tmp/"

nginx -g 'daemon off;'