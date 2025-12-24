# --- Etapa 1: Compilación de Assets (Node.js) ---
FROM node:20-alpine AS assets-builder
WORKDIR /app
# Copiamos solo los archivos de configuración de node para aprovechar la caché
COPY package*.json ./
RUN npm install
# Copiamos el resto del código y compilamos
COPY . .
RUN npm run build

# --- Etapa 2: Servidor de Producción (FrankenPHP) ---
FROM dunglas/frankenphp:1-php8.3-alpine

# Dependencias del sistema
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev

# Configurar e instalar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    intl \
    gd \
    bcmath \
    pcntl \
    opcache

WORKDIR /app

# Copiar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar el código de la aplicación
COPY . .

# COPIAR LOS ASSETS COMPILADOS DESDE LA ETAPA 1
# Esto trae la carpeta public/build (con el manifest.json) que generó Vite
COPY --from=assets-builder /app/public/build ./public/build

# Instalar dependencias de PHP
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Permisos correctos para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Variables de entorno
ENV APP_ENV=production
ENV OCTANE_SERVER=frankenphp
ENV APP_RUNNING_IN_CONSOLE=false

EXPOSE 8080

# Comando de inicio
CMD php artisan octane:start \
    --server=frankenphp \
    --host=0.0.0.0 \
    --port=${PORT:-8080} \
    --workers=4