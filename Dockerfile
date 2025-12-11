# Use PHP CLI image
FROM php:8.2-cli

# Install system deps required for pecl mongodb and composer
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install MongoDB PHP extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copy composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer manifest first (for caching)
COPY composer.json composer.lock* ./

# Install PHP dependencies inside the container
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

# Copy the rest of your application
COPY . .

# Expose port for Render
ENV PORT=10000

# Start PHP built-in server pointing to public folder
CMD ["sh", "-c", "php -d memory_limit=512M -S 0.0.0.0:${PORT:-10000} -t public"]
