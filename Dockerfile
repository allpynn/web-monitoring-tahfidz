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
    gnupg \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js (directly in the main image for simpler building)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

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

# Copy application code
COPY . .

# Set environment variables
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST
ARG VITE_REVERB_PORT
ARG VITE_REVERB_SCHEME

ENV VITE_REVERB_APP_KEY=$VITE_REVERB_APP_KEY
ENV VITE_REVERB_HOST=$VITE_REVERB_HOST
ENV VITE_REVERB_PORT=$VITE_REVERB_PORT
ENV VITE_REVERB_SCHEME=$VITE_REVERB_SCHEME

# Install dependencies and build assets
RUN composer install --no-dev --optimize-autoloader --no-interaction


# Build assets
RUN npm install && npm run build

# Set permissions
RUN mkdir -p storage bootstrap/cache public/build && \
    chmod -R 775 storage bootstrap/cache public/build && \
    chown -R www-data:www-data /var/www

# Set environment variables for FrankenPHP
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# Expose ports
EXPOSE 8000
EXPOSE 80
EXPOSE 443

# Start Octane with FrankenPHP
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
