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
# PARTE 2: Instalación de Dependencias de la Aplicación
# ------------------------------------------------------------------
WORKDIR /var/www/html

# 4. Copiar ÚNICAMENTE los archivos que Composer necesita (Solución al exit code 2)
# Asegúrate de que composer.json y composer.lock estén en tu repositorio de Git
COPY composer.json composer.lock ./

# 5. FIX DE PERMISOS TEMPORALES y Ejecutar Composer
# Se asegura de que el usuario www-data pueda escribir en el directorio
RUN chmod -R 777 /var/www/html
RUN composer install --no-dev --optimize-autoloader

# ------------------------------------------------------------------
# PARTE 3: Copiar Código, Permisos y Configuración de Apache
# ------------------------------------------------------------------

# 6. Copiar el resto del código de la aplicación.
COPY . .

# 7. Restaurar Permisos de Laravel (Esencial para seguridad y tiempo de ejecución)
# El usuario 'www-data' es el usuario de Apache dentro del contenedor
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 storage bootstrap/cache

# 8. Configuración del Servidor Web (Apache)
RUN a2enmod rewrite
# Copiar el Virtual Host modificado para que apunte a la carpeta 'public'
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf