# ------------------------------------------------------------------
# PARTE 1: Configuración base y Composer
# ------------------------------------------------------------------
FROM php:8.3-apache

# Instalar extensiones y dependencias del sistema... (Tu código original)
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

# ------------------------------------------------------------------
# PARTE 2: Instalación de Dependencias (El Fix)
# ------------------------------------------------------------------
WORKDIR /var/www/html

# 1. Copiar ÚNICAMENTE los archivos que Composer necesita.
# Esto garantiza que composer.json exista en el WORKDIR.
COPY composer.json composer.lock ./

# 2. Ejecutar Composer. (Ahora tiene éxito porque los archivos están ahí)
RUN composer install --no-dev --optimize-autoloader

# ------------------------------------------------------------------
# PARTE 3: Copiar el resto del código y permisos
# ------------------------------------------------------------------

# 3. Copiar el resto del código de la aplicación.
COPY . .

# 4. Configurar permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Habilitar Apache y copiar la configuración del Virtual Host
RUN a2enmod rewrite
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf