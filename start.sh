#!/bin/sh

# Ejecutar migraciones
php artisan config:cache
php artisan route:cache
php artisan migrate --force

# Arrancar php-fpm85 en segundo plano
php-fpm85 -D

# Esperar a que el socket esté listo
sleep 2

# Arrancar nginx en primer plano
nginx -g 'daemon off;'