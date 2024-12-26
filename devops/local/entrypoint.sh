#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Log a message to indicate the script is starting
echo "Starting entrypoint script..."

# Ensure correct permissions for storage and bootstrap/cache directories
echo "Setting permissions for storage and bootstrap/cache..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Check if Composer dependencies are already installed
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
else
    echo "Composer dependencies already installed. Skipping..."
fi

# Create symbolic link for storage if it doesn't already exist
if [ ! -L "public/storage" ]; then
    echo "Creating storage symlink..."
    php artisan storage:link
else
    echo "Storage symlink already exists. Skipping..."
fi

# Run database migrations and seeders
echo "Running database migrations and seeders..."
php artisan migrate --seed || {
    echo "Database migration failed. Exiting..."
    exit 1
}

# Optional: Uncomment if you plan to use broadcasting installation
# echo "Installing broadcasting setup..."
# php artisan install:broadcasting

# Log a message to indicate the script is ready to start the application
echo "Entrypoint script completed. Starting application..."

# Execute the command passed to the container (e.g., php-fpm)
exec "$@"
