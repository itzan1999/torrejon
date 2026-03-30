#!/bin/sh

php-fpm85 --allow-to-run-as-root -D

# Esperar hasta que el socket esté listo
echo "Esperando socket php-fpm..."
for i in $(seq 1 30); do
    if [ -S /var/run/php-fpm.sock ]; then
        echo "Socket listo!"
        break
    fi
    sleep 1
done

# Ejecutar migraciones
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan db:seed --class=DatosPruebaSeeder --force
php artisan db:seed --class=DatosLeche --force


nginx -g 'daemon off;'