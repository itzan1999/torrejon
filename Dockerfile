FROM trafex/php-nginx:latest

USER root

# Instalar extensiones necesarias (php85 porque la imagen usa PHP 8.5)
RUN apk add --no-cache \
    php85-pdo \
    php85-pdo_mysql \
    php85-tokenizer \
    php85-dom \
    php85-xml \
    php85-xmlwriter \
    php85-fileinfo \
    php85-ctype \
    php85-iconv \
    php85-mbstring \
    php85-session \
    php85-openssl

# Copiar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER nobody

COPY --chown=nobody:nobody . /var/www/html
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs