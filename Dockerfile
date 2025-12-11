# Base PHP CLI image
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install MongoDB PHP extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copy Composer binary from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy only composer files first (for caching)
COPY composer.json composer.lock* ./

# Run composer install (inside /app)
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

# Copy the rest of the application
COPY . .

# Expose port for Render
ENV PORT=10000

# Start built-in PHP server pointing to /app/public
CMD ["sh", "-c", "php -d memory_limit=512M -S 0.0.0.0:${PORT:-10000} -t public"]
