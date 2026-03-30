#!/bin/sh
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php-fpm83 -D
nginx -g 'daemon off;'