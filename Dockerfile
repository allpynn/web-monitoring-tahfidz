# Stage 1: Build assets using Node.js
FROM node:20-alpine AS asset-builder
WORKDIR /app
COPY . .
RUN npm install && npm run build

# Stage 2: Final image using FrankenPHP
FROM dunglas/frankenphp:latest-php8.2

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    curl \
    git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    opcache \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code first
COPY . .

# Copy built assets from Stage 1 (Overwriting any local build directory)
COPY --from=asset-builder /app/public/build /var/www/public/build

# Set permissions for Laravel
RUN mkdir -p storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data /var/www

# Install Laravel dependencies
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# Set environment variables for FrankenPHP
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# Expose ports
EXPOSE 8000
EXPOSE 80
EXPOSE 443

# Start Octane with FrankenPHP
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
