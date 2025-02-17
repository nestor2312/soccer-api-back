FROM php:8.2-fpm-alpine

RUN apk add --no-cache libzip-dev zip unzip libpng-dev && \
    docker-php-ext-install pdo_mysql zip gd 

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear directorio para la caché de Composer
VOLUME /var/cache/composer

# Copiar composer.json y composer.lock
COPY composer.json composer.lock ./

# Instalar dependencias
RUN composer install --no-interaction --no-dev && \
    composer clearcache


# Copiar el resto del proyecto
COPY . .

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]