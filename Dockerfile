# Usa una imagen base de PHP
FROM php:8.3-apache

# Instalar extensiones de PHP necesarias...
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -------------------------------------------------------------
# CAMBIO CLAVE A PARTIR DE AQUÍ:
# -------------------------------------------------------------

# 1. Configurar el directorio de trabajo
WORKDIR /var/www/html

# 2. COPIAR SOLO LOS ARCHIVOS NECESARIOS PARA COMPOSER
# Esto asegura que composer.json esté presente antes de instalar.
COPY composer.json composer.lock ./

# 3. INSTALAR DEPENDENCIAS
RUN composer install --no-dev --optimize-autoloader

# 4. COPIAR EL RESTO DEL CÓDIGO
# Excluyendo 'vendor' (ya está instalado) y los archivos de Docker
COPY . .

# 5. Configurar permisos de almacenamiento y caché
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Habilitar el módulo de reescritura de Apache y configurar el Virtual Host
RUN a2enmod rewrite
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf