# ------------------------------------------------------------------
# PARTE 1: Configuración base y Composer
# ------------------------------------------------------------------
FROM php:8.3-apache

# ... (Instalación de extensiones, etc. - Tu código original aquí) ...

# Instalar Composer (NUEVA FORMA MÁS FIABLE)
# 1. Copiar Composer a la ubicación estándar de binarios
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# 2. Asegurar que tiene permisos de ejecución
RUN chmod +x /usr/local/bin/composer

# ------------------------------------------------------------------
# PARTE 2: Instalación de Dependencias (El Fix)
# ------------------------------------------------------------------
WORKDIR /var/www/html

# 1. Copiar ÚNICAMENTE los archivos que Composer necesita.
COPY composer.json composer.lock ./

# 2. FIX DE PERMISOS TEMPORALES (Mantén esto)
RUN chmod -R 777 /var/www/html

# 3. Ejecutar Composer (Ahora debería encontrar el binario)
RUN composer install --no-dev --optimize-autoloader

# ... (El resto del código) ...

# ------------------------------------------------------------------
# PARTE 3: Copiar el resto del código y permisos
# ------------------------------------------------------------------

# 4. Copiar el resto del código de la aplicación.
COPY . .

# 5. **RESTAURAR PERMISOS** (Esencial por seguridad y para el runtime)
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 storage bootstrap/cache

# Habilitar Apache y copiar la configuración del Virtual Host
RUN a2enmod rewrite
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf