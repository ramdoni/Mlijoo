FROM php:8.1-apache

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && echo "memory_limit=1024M" > "$PHP_INI_DIR/conf.d/memory-limit.ini" \
    && apt update \
    && apt install -y curl libcurl4-openssl-dev libonig-dev libpng-dev libxml2-dev libzip-dev nano pkg-config unzip zip \
    && apt-get clean \
    && docker-php-ext-configure zip \
    && docker-php-ext-install bcmath curl exif fileinfo gd mbstring opcache pdo_mysql pcntl zip \
    && docker-php-ext-enable pdo_mysql \
    && a2enmod rewrite \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm -fr composer-setup.php \
    && chown -R www-data:www-data /var/www \
    && chmod -R 0777 /var/www

WORKDIR /var/www