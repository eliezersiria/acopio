# ------------------------------------------------------------------
# PARTE 1: Configuración base y Composer
# ------------------------------------------------------------------
FROM php:8.3-apache

# ... (Instalación de extensiones y Composer - Tu código está correcto aquí) ...

# ------------------------------------------------------------------
# PARTE 2: Instalación de Dependencias (El Fix)
# ------------------------------------------------------------------
WORKDIR /var/www/html

# 1. Copiar ÚNICAMENTE los archivos que Composer necesita.
# (Asegúrate de que ambos, composer.json y composer.lock, existan en la raíz de tu Git)
COPY composer.json composer.lock ./

# 2. **FIX DE PERMISOS TEMPORALES:** Temporalmente, hacemos que el directorio sea escribible por el usuario www-data, que es el que ejecuta Composer.
RUN chmod -R 777 /var/www/html

# 3. Ejecutar Composer
RUN composer install --no-dev --optimize-autoloader

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