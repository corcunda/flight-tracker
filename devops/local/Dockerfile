# Start from the official PHP 8.2 FPM image
FROM php:8.2-fpm

RUN pecl install xdebug && docker-php-ext-enable xdebug

# Remake configs
COPY ./devops/local/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd xml pdo_pgsql pgsql bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (if needed for frontend)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set the working directory to /var/www
WORKDIR /var/www

# Copy the application files into the container
#COPY . /var/www

# Copy the entrypoint script to the container
COPY ./devops/local/entrypoint.sh /entrypoint.sh

# Make the script executable
RUN chmod +x /entrypoint.sh

# Set the entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Install PHP dependencies with Composer
#RUN composer install --no-dev --optimize-autoloader

# Expose port 9000 and start the PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]


