# ------------------------------------------------------------------
# PARTE 1: Configuración base, Extensiones y Composer
# ------------------------------------------------------------------
FROM php:8.3-apache

# 1. Instalar dependencias del sistema y librerías necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    # Limpieza de caché de APT
    && rm -rf /var/lib/apt/lists/*

# 2. Habilitar extensiones de PHP
# Se eliminan: tokenizer, mbstring (generalmente core o instaladas por libonig-dev)
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd \
    \
    # Se instalan solo las que suelen necesitar compilación externa:
    && docker-php-ext-install bcmath xml

# 3. Instalar Composer... (El resto de la Parte 1)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# ... (El resto del Dockerfile, que ya es correcto) ...

# ------------------------------------------------------------------
# PARTE 2: Copiar el Código
# ------------------------------------------------------------------
WORKDIR /var/www/html

# 4. Copiar todo el código de la aplicación (incluyendo artisan, composer.json, etc.).
# Asegúrate de que el archivo .docker/000-default.conf también esté en su lugar.
COPY . .

# ------------------------------------------------------------------
# PARTE 3: Instalación de Dependencias y Permisos (El Fix)
# ------------------------------------------------------------------

# 5. FIX DE PERMISOS TEMPORALES y Ejecutar Composer
# Este paso asegura que el usuario www-data pueda escribir en 'vendor' y 'storage'.
RUN chmod -R 777 /var/www/html

# 6. Ejecutar Composer (Ahora que 'artisan' está copiado)
RUN composer install --no-dev --optimize-autoloader

# 7. Restaurar Permisos de Laravel (Esencial para seguridad y runtime)
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 storage bootstrap/cache

# 8. Configuración del Servidor Web (Apache)
RUN a2enmod rewrite
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf