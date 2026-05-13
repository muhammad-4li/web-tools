#!/bin/bash

# Run migrations on startup (safe — only applies pending ones)
# php /var/www/html/artisan migrate --force

# Start PHP-FPM in the background
php-fpm -D

# Start Nginx in the foreground
nginx -g 'daemon off;'
