#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Log a message to indicate the script is starting
echo "Starting entrypoint script..."

# Install jq if not already installed
# if ! command -v jq &> /dev/null; then
#     echo "'jq' is not installed. Installing..."
#     apt-get update && apt-get install -y jq && rm -rf /var/lib/apt/lists/*
# else
#     echo "'jq' is already installed. Skipping..."
# fi

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

# Check if PASSPORT_PERSONAL_ACCESS_CLIENT_ID and PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET exist in .env and are not empty
# if ! grep -q "^PASSPORT_PERSONAL_ACCESS_CLIENT_ID=" .env || ! grep -q "^PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=" .env || \
#    [ -z "$(grep "^PASSPORT_PERSONAL_ACCESS_CLIENT_ID=" .env | cut -d '=' -f 2)" ] || \
#    [ -z "$(grep "^PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=" .env | cut -d '=' -f 2)" ]; then

#     echo "Laravel Passport not set up or missing in .env. Proceeding with installation..."

#     # Install Passport
#     php artisan passport:install || {
#         echo "Failed to install Passport. Exiting..."
#         exit 1
#     }

#     # Retrieve the Client ID and Secret
#     echo "Retrieving Passport Client IDs and Secrets..."
#     PERSONAL_ACCESS_CLIENT=$(php artisan tinker --execute="echo json_encode(DB::table('oauth_clients')->where('personal_access_client', 1)->first());")

#     if [ -z "$PERSONAL_ACCESS_CLIENT" ]; then
#         echo "Failed to retrieve Passport client data. Exiting..."
#         exit 1
#     fi

#     CLIENT_ID=$(echo $PERSONAL_ACCESS_CLIENT | jq -r '.id')
#     CLIENT_SECRET=$(echo $PERSONAL_ACCESS_CLIENT | jq -r '.secret')

#     if [ -z "$CLIENT_ID" ] || [ -z "$CLIENT_SECRET" ]; then
#         echo "Client ID or Secret is missing. Exiting..."
#         exit 1
#     fi

#     # Add or update the environment variables directly in .env
#     sed -i "/^PASSPORT_PERSONAL_ACCESS_CLIENT_ID=/d" .env
#     sed -i "/^PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=/d" .env
#     echo -e "\nPASSPORT_PERSONAL_ACCESS_CLIENT_ID=$CLIENT_ID" >> .env
#     echo -e "\nPASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=$CLIENT_SECRET" >> .env
#     echo "Updated .env with Passport credentials."

#     # Clear and cache configurations
#     echo "Clearing and caching configurations..."
#     php artisan config:clear
#     php artisan cache:clear
#     php artisan config:cache

#     echo "Laravel Passport installation completed!"

# else
#     echo "Passport is already set up in .env. Skipping installation..."
# fi



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
