#!/bin/bash
# Ensure correct permissions for storage and bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install PHP dependencies with Composer
php composer install --no-dev --optimize-autoloader

# Run 'php artisan storage:link' to create the symbolic link
php artisan storage:link

# 
#php artisan install:broadcasting

# Run database migrations and seed
php artisan migrate --seed

# Execute the command passed to the container (php-fpm)
exec "$@"
