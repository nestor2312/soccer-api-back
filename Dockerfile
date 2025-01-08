FROM php:7.4.8-fpm

# Instalar Composer
RUN apt-get update && apt-get install -y curl && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar composer.json y composer.lock
COPY composer.json composer.lock ./

# Instalar dependencias
RUN composer install --no-interaction

# Copiar el resto del proyecto (opcional para depuración)
# COPY . . 

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]