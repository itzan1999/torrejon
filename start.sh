#!/bin/sh

php-fpm85 --allow-to-run-as-root -D
sleep 3

# Dar permisos al socket para que nginx pueda accederlo
chmod 777 /run/php-fpm.sock

# Ejecutar migraciones
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan db:seed --class=DatosPruebaSeeder --force
php artisan db:seed --class=DatosLeche --force


nginx -g 'daemon off;'