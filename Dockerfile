# --- Etapa 1: Construcción y Dependencias ---
FROM php:8.2-fpm-alpine AS builder

# BIEN DE DEVSECOPS: Actualizamos el SO base para parchear vulnerabilidades (ej. libxml2)
# AGREGAMOS 'icu-dev' A LAS DEPENDENCIAS E 'intl' A LAS EXTENSIONES DE PHP
RUN apk upgrade --no-cache && \
    apk add --no-cache \
    bash \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd mysqli intl

WORKDIR /var/www/html

# Copiar el código del e-commerce al contenedor
COPY . .

# --- Etapa 2: Imagen Final de Producción (Segura) ---
FROM php:8.2-fpm-alpine

# BIEN DE DEVSECOPS: Actualizamos el SO base en la imagen final
# AGREGAMOS 'icu-libs' PARA QUE LA EXTENSIÓN FUNCIONE EN PRODUCCIÓN
RUN apk upgrade --no-cache && \
    apk add --no-cache nginx icu-libs

# Copiar las extensiones de PHP que compilamos en la etapa anterior
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /var/www/html

# Copiar el código limpio desde la etapa de construcción
COPY --from=builder /var/www/html .
COPY nginx.conf /etc/nginx/nginx.conf

# BIEN DE DEVSECOPS: Crear un usuario no-root para correr la app
RUN addgroup -g 1001 appgroup && \
    adduser -u 1001 -G appgroup -D appuser

# Crear los directorios temporales de Nginx y forzar la creación de la caché de CodeIgniter
RUN mkdir -p /var/lib/nginx/tmp /var/log/nginx /run/nginx \
    /var/www/html/writable/cache /var/www/html/writable/logs /var/www/html/writable/session

# Dar permisos de escritura a appuser SOLO donde es estrictamente necesario
RUN chown -R appuser:appgroup /var/www/html/writable /var/lib/nginx /var/log/nginx /run/nginx && \
    chmod -R 775 /var/www/html/writable

# Cambiar al usuario seguro
USER appuser

EXPOSE 80

# Comando para iniciar tanto PHP-FPM como Nginx en el mismo contenedor
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]