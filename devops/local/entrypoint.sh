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

# Check if the 'jobs' table exists in the database
# TABLE_EXISTS=$(php artisan tinker --execute="if (Schema::hasTable('jobs')) { echo 'exists'; } else { echo 'not exists'; }")

# if [ "$TABLE_EXISTS" == "not exists" ]; then
#     # Create the jobs table migration if it doesn't exist
#     echo "Jobs table does not exist. Creating migrations for jobs table..."
#     php artisan queue:table
#     php artisan migrate
# else
#     echo "Jobs table already exists. Skipping creation."
# fi

# Run the rest of your migrations (including other necessary migrations)
php artisan migrate --seed || {
    echo "Database migration failed. Exiting..."
    exit 1
}

# Gracefully restart queue workers to stop any running jobs before starting new ones
echo "Stopping all running queue workers..."
pkill -f "php artisan queue:work" || echo "No queue workers running."

# Manually truncate the jobs table to clear all jobs
echo "Clearing all jobs from the jobs table..."
php artisan tinker --execute="DB::table('jobs')->truncate();"


# Optional: Uncomment if you plan to use broadcasting installation
# echo "Installing broadcasting setup..."
# php artisan install:broadcasting

# Start the queue worker in daemon mode (background)
echo "Starting the queue worker in daemon mode..."
php artisan queue:work --daemon &
# php artisan queue:work

# Log a message to indicate the script is ready to start the application
echo "Entrypoint script completed. Starting application..."

# Execute the command passed to the container (e.g., php-fpm)
exec "$@"
