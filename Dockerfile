FROM trafex/php-nginx:latest

USER root
RUN apk add --no-cache \
    php83-pdo \
    php83-pdo_mysql \
    php83-tokenizer \
    php83-dom \
    php83-xml \
    php83-xmlwriter \
    php83-fileinfo \
    php83-ctype \
    php85-iconv \
    php85-mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER nobody
COPY --chown=nobody:nobody . /var/www/html
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader