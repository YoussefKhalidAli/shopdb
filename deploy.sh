#!/bin/bash

# Make sure this file has executable permissions, run `chmod +x deploy.sh` to ensure it does

# Variable name to check maintenance mode
ENV_VAR_NAME="MAINTENANCE_MODE"

# Check if the environment variable is set to "true"
if [[ "${!ENV_VAR_NAME}" = "true" ]]; then
  echo "Entering maintenance mode..."
  php artisan down
fi

# Build assets using NPM
npm run build

# Clear cache
php artisan optimize:clear

# Cache the various components of the Laravel application
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Run any database migrations
php artisan migrate --force

# Check if the environment variable is set to "false" or not set at all
if [[ "${!ENV_VAR_NAME}" = "false" ]] || [[ -z "${!ENV_VAR_NAME}" ]]; then
  echo "Exiting maintenance mode..."
  php artisan up
fi


# APP_NAME="Laravel"
# APP_ENV="production"
# APP_KEY="base64:tbGen2urVA7PtgLYBg3RGu3gARXrCkBpsn6SmdkAMTo="
# APP_DEBUG="false"
# APP_URL="https://shopdb-production.up.railway.app"
# DB_CONNECTION="mysql"
# DB_HOST="switchback.proxy.rlwy.net"
# DB_PORT="36309"
# DB_DATABASE="railway"
# DB_USERNAME="root"
# DB_PASSWORD="mAmKPPAyiouJwbocmdKagVEiVnSyndYM"
# CACHE_DRIVER="file"
# QUEUE_CONNECTION="sync"
# SESSION_DRIVER="file"
# SESSION_LIFETIME="120"
# LOG_CHANNEL="stack"
# LOG_LEVEL="debug"
# BROADCAST_DRIVER="log"
# FILESYSTEM_DRIVER="local"
# MYSQL_DATABASE="${{MySQL.MYSQL_DATABASE}}"
# PHP_VERSION="8.2"
# PORT="8080"
# ###