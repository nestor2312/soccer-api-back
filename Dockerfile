# Utiliza una imagen base de PHP con las extensiones necesarias
FROM php:8.2-fpm

# Instala extensiones PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Copia los archivos de tu proyecto
COPY composer.json composer.lock ./
RUN composer install --no-interaction
COPY . .

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Expone el puerto
EXPOSE 9000

# Comando para iniciar el servidor PHP-FPM
CMD ["php-fpm"]