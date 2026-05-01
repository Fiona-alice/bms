FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Fix permissions (IMPORTANT)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Clear and cache config (IMPORTANT)
RUN php artisan config:clear \
    && php artisan cache:clear \
    && php artisan view:clear

# Expose port
EXPOSE 10000

# Start server using public folder (BETTER)
CMD php -S 0.0.0.0:10000 -t public
