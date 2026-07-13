# syntax=docker/dockerfile:1.7

# --- Etapa 1: Composer (instala dependencias PHP sin dev) ---
FROM composer:2.7 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --ignore-platform-req=ext-intl \
    --ignore-platform-req=ext-mysqli \
    --ignore-platform-req=ext-gd \
    --ignore-platform-req=ext-pdo_mysql

# --- Etapa 2: Builder de extensiones PHP ---
FROM php:8.4-fpm-alpine AS builder

RUN apk upgrade --no-cache && \
    apk add --no-cache \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql gd mysqli intl

# --- Etapa 3: Imagen final de producción ---
FROM php:8.4-fpm-alpine

# Parcheamos el SO base
RUN apk upgrade --no-cache && \
    apk add --no-cache nginx icu-libs libpng libjpeg-turbo freetype curl

# Copiamos extensiones PHP compiladas en el builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /var/www/html

# Copiamos el código de la app (respetando .dockerignore)
COPY . .

# Copiamos vendor/ desde la etapa de Composer
COPY --from=vendor /app/vendor ./vendor

# Regeneramos el autoloader ahora que tenemos todo el código
RUN apk add --no-cache --virtual .build-deps composer && \
    composer dump-autoload --no-dev --optimize --classmap-authoritative && \
    apk del .build-deps && \
    rm -rf /root/.composer

# Config de Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Usuario no-root
RUN addgroup -g 1001 appgroup && \
    adduser -u 1001 -G appgroup -D appuser

# Directorios que Nginx y CI4 necesitan escribir
RUN mkdir -p /var/lib/nginx/tmp /var/log/nginx /run/nginx \
             /var/www/html/writable/cache \
             /var/www/html/writable/logs \
             /var/www/html/writable/session \
    && chown -R appuser:appgroup /var/www/html/writable \
                                  /var/lib/nginx \
                                  /var/log/nginx \
                                  /run/nginx \
    && chmod -R 775 /var/www/html/writable

USER appuser

# 8080 en vez de 80: los puertos <1024 requieren root
EXPOSE 8080

# Healthcheck: si esto falla, ECS/Docker reinicia el contenedor
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD curl -fsS http://localhost:8080/ || exit 1

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]