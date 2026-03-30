#!/bin/sh

# Ejecutar migraciones
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan db:seed --class=DatosPruebaSeeder --force
php artisan db:seed --class=DatosLecheSeeder --force


# Arrancar php-fpm85 en segundo plano
php-fpm85 --allow-to-run-as-root -D

sleep 2

nginx -g 'daemon off;'