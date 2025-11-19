# -------------------------------
# 1) Composer stage: install PHP dependencies
# -------------------------------
    FROM php:8.2-cli AS vendor
    WORKDIR /app
    # Provide composer in this stage
    COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
    
    # Install PHP system dependencies
    RUN apt-get update && apt-get install -y --no-install-recommends \
          libzip-dev libpng-dev libjpeg-dev libfreetype6-dev zip unzip git curl \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd zip pdo_mysql \
        && docker-php-ext-enable gd \
        && rm -rf /var/lib/apt/lists/*
    
    # Copy composer files and install PHP dependencies (skip scripts since artisan isn't available in this stage)
    COPY composer.json composer.lock* /app/
    RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts
    
    # -------------------------------
    # 2) Node stage: build frontend with Vite
    # -------------------------------
    FROM node:20-alpine AS assets
    WORKDIR /app
    
    # Copy package files
    COPY package.json package-lock.json* pnpm-lock.yaml* yarn.lock* /app/
    
    # Install Node dependencies
    RUN if [ -f package.json ]; then \
          if [ -f yarn.lock ]; then yarn install --frozen-lockfile; \
          elif [ -f pnpm-lock.yaml ]; then npm i -g pnpm && pnpm i --frozen-lockfile; \
          elif [ -f package-lock.json ]; then npm ci; \
          else npm i; fi; \
        fi
    
    # Copy resources and public folder for build
    COPY resources/ /app/resources/
    COPY vite.config.* /app/
    COPY public/ /app/public/
    
    # Build frontend assets
    RUN if [ -f package.json ]; then npm run build; \
        else echo "No frontend build found, skipping"; fi
    
    # -------------------------------
    # 3) App runtime: PHP 8.2 + Apache
    # -------------------------------
    FROM php:8.2-apache AS app
    WORKDIR /var/www/html
    
    # Install PHP extensions
    RUN apt-get update && apt-get install -y --no-install-recommends \
          libzip-dev libpng-dev libjpeg-dev libfreetype6-dev zip unzip git curl \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd zip pdo_mysql \
        && docker-php-ext-enable gd opcache \
        && rm -rf /var/lib/apt/lists/*
    
    # Apache rewrite and DocumentRoot
    RUN a2enmod rewrite \
        && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
        && sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf \
        && sed -ri 's#<Directory /var/www/>#<Directory /var/www/html/public/>#' /etc/apache2/apache2.conf \
        && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
    
    # Copy Laravel app source
    COPY . /var/www/html
    
    # Copy PHP dependencies from composer stage
    COPY --from=vendor /app/vendor /var/www/html/vendor
    
    # Copy built frontend assets from Node stage
    COPY --from=assets /app/public/build /var/www/html/public/build
    
    # Set permissions
    RUN chown -R www-data:www-data /var/www/html \
        && find storage -type d -exec chmod 775 {} \; \
        && find storage -type f -exec chmod 664 {} \; \
        && chmod -R 775 bootstrap/cache
    
    # Optimize Laravel
    RUN php artisan package:discover --ansi || true \
     && php artisan config:clear || true \
     && php artisan route:clear || true \
     && php artisan view:clear || true
    
    # Expose port
    EXPOSE 80
    
    # Healthcheck
    HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
      CMD curl -f http://localhost/ || exit 1
    