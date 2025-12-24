# ------------------------------------------------------------------
# PARTE 1: Base FrankenPHP + Extensiones
# ------------------------------------------------------------------
FROM dunglas/frankenphp:1.2-php8.3

# Instalador de extensiones oficial
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Instalamos PCNTL (para Octane) y GD con soporte WebP/JPEG
RUN apt-get update && apt-get install -y git unzip curl \
    && install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    zip \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    opcache

# ------------------------------------------------------------------
# PARTE 2: Node, Composer y Proyecto
# ------------------------------------------------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
COPY . .

# ------------------------------------------------------------------
# PARTE 3: Build y Permisos
# ------------------------------------------------------------------
RUN npm install && npm run build
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /app \
    && chmod -R 775 storage bootstrap/cache

# ------------------------------------------------------------------
# PARTE 4: Verificación de soporte de Imagenes (WebP, JPEG, PNG)
# ------------------------------------------------------------------
RUN php -r "echo 'GD WebP Support: ' . (function_exists('imagewebp') ? 'YES' : 'NO') . \"\\n\";" \
    && php -r "echo 'GD JPEG Support: ' . (function_exists('imagejpeg') ? 'YES' : 'NO') . \"\\n\";" \
    && php -r "echo 'GD PNG Support: ' . (function_exists('imagepng') ? 'YES' : 'NO') . \"\\n\";"

EXPOSE 8000

# IMPORTANTE: Usamos el comando de Octane
CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]