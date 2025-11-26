# ------------------------------------------------------------------
# PARTE 1: Base + dependencias
# ------------------------------------------------------------------
FROM php:8.3-apache

# 1. Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip exif pcntl bcmath xml

# 3. GD CONFIGURACIÓN CORRECTA PARA PHP 8.3 (sin --with-png)
RUN docker-php-ext-configure gd --with-jpeg --with-webp
RUN docker-php-ext-install gd

# 4. Instalar NodeJS (para Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Verificar version
RUN node -v && npm -v

# 5. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# ------------------------------------------------------------------
# PARTE 2: Copiar Proyecto
# ------------------------------------------------------------------
WORKDIR /var/www/html
COPY . .

# ------------------------------------------------------------------
# PARTE 3: Build Frontend con Vite
# ------------------------------------------------------------------
RUN npm install
RUN npm run build

# ------------------------------------------------------------------
# PARTE 4: Composer + Permisos
# ------------------------------------------------------------------
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 storage bootstrap/cache

# ------------------------------------------------------------------
# PARTE 5: Apache
# ------------------------------------------------------------------
RUN a2enmod rewrite
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# ------------------------------------------------------------------
# PARTE 6: Verificar que WebP funciona
# ------------------------------------------------------------------
RUN php -r "echo 'GD WebP Support: ' . (function_exists('imagewebp') ? 'YES' : 'NO') . \"\\n\";" \
    && php -r "echo 'GD JPEG Support: ' . (function_exists('imagejpeg') ? 'YES' : 'NO') . \"\\n\";" \
    && php -r "echo 'GD PNG Support: ' . (function_exists('imagepng') ? 'YES' : 'NO') . \"\\n\";"

EXPOSE 80
CMD ["apache2-foreground"]