# ---------- base PHP 8.4 ----------
    FROM php:8.4-fpm-alpine AS app

    # ✱ paquetes de sistema + build deps
    RUN apk add --no-cache \
            git curl zip unzip rsync bash \
            icu-data-full icu-dev oniguruma-dev \
            libpng-dev libjpeg-turbo-dev freetype-dev \
            postgresql-dev libzip-dev zlib-dev \
            $PHPIZE_DEPS                       \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) \
             pdo_pgsql intl mbstring gd zip bcmath opcache pcntl \
        # ── extensión Redis (PECL) ──
        && pecl install redis && docker-php-ext-enable redis \
        && apk del --no-cache $PHPIZE_DEPS

    # ✱ composer
    COPY --from=composer:2.8 /usr/bin/composer /usr/local/bin/composer
    
    # ✱ node + pnpm (para Vite/Laravel Mix si lo necesitas)
    RUN apk add --no-cache nodejs npm && npm install -g pnpm
    
    # ---------- usuario host (evita problemas de permisos) ----------
    ARG HOST_UID=1000
    ARG HOST_GID=1000
    RUN addgroup -g ${HOST_GID} www \
     && adduser  -u ${HOST_UID} -G www -s /bin/sh -D www
     
    # ✱ docker-cli y configuración de permisos
    RUN apk add --no-cache docker-cli \
        && addgroup -g 998 docker \
        && addgroup www docker
    
    WORKDIR /var/www/html
    
    # ---------- dependencias PHP (cache optimizado) ----------
    COPY composer.* ./
    RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts
    
    # ---------- código fuente (owner = www) ----------
    COPY --chown=www:www . .
    
    USER www
    
    # ---------- build frontend opcional ----------
    RUN [ -f package.json ] && pnpm install && pnpm run build || true
    
    EXPOSE 9000
    CMD ["php-fpm"]
    