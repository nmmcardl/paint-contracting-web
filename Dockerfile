# Use a lightweight official PHP CLI image (good for php -S built-in server)
FROM php:8.2-cli

# Install system deps required for pecl mongodb and composer
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install the MongoDB PHP extension via pecl
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copy composer from official composer image (fast & reliable)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer manifest and install PHP deps (cache benefit)
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader || true

# Copy the rest of the app
COPY . .

# Ensure the public dir exists and is the web root
WORKDIR /app

# Expose no specific port (Render provides $PORT). For docs we note 10000 default.
ENV PORT=10000

# Use the PHP built-in server, binding to Render's $PORT. Use -d to increase upload size if needed.
CMD ["sh", "-c", "php -d memory_limit=512M -S 0.0.0.0:${PORT:-10000} -t public"]
