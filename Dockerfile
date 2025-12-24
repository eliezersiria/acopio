# --- ETAPA 1: Compilar Assets (Node.js) ---
FROM node:20-alpine AS assets-builder
WORKDIR /app
# Copiamos archivos de dependencias de node
COPY package*.json ./
RUN npm install
# Copiamos el código y compilamos JS/CSS
COPY . .
RUN npm run build

# --- ETAPA 2: Servidor de Producción (FrankenPHP) ---
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

# Copiar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar todo el código del proyecto
COPY . .

# TRAER ASSETS COMPILADOS (Esto soluciona el error de Vite)
COPY --from=assets-builder /app/public/build ./public/build

# Instalar dependencias de PHP
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Permisos para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

ENV APP_ENV=production
ENV OCTANE_SERVER=frankenphp

EXPOSE 8080

CMD php artisan octane:start \
    --server=frankenphp \
    --host=0.0.0.0 \
    --port=${PORT:-8080} \
    --workers=4