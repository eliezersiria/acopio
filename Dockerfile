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

# 2. Extensiones de PHP - CORREGIDO (AGREGAR WEBP)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip exif pcntl \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-png \
    && docker-php-ext-install gd \
    && docker-php-ext-install bcmath xml

# 3. Instalar NodeJS (para Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Verificar version
RUN node -v && npm -v

# 4. Instalar Composer
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

RUN chmod -R 777 /var/www/html

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
# PARTE 6: Verificar GD (OPCIONAL PERO ÚTIL)
# ------------------------------------------------------------------
RUN php -r "var_dump(gd_info());"