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

# Configurar GD con WebP
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

# Instalar extensiones PHP (🔥 incluye pcntl)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    intl \
    gd \
    bcmath \
    pcntl \
    opcache

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# 5. --- NUEVO: Instalar dependencias de Node y compilar Assets (Vite) ---
RUN npm install && npm run build

RUN chown -R www-data:www-data storage bootstrap/cache

ENV APP_ENV=production
ENV OCTANE_SERVER=frankenphp

EXPOSE 8080

CMD php artisan octane:start \
    --server=frankenphp \
    --host=0.0.0.0 \
    --port=${PORT:-8080} \
    --workers=4
