# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system and PHP dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache mod_rewrite and adjust DocumentRoot for Laravel public folder
RUN a2enmod rewrite \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Set the ServerName globally to suppress Apache warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files to the container
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN cp .env.example .env

RUN php artisan key:generate --no-interaction

# Set permissions for the entire application and public directory
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN chown -R www-data:www-data /var/www/html/public \
    && chmod -R 755 /var/www/html/public

# Ensure DirectoryIndex is set to index.php
RUN echo 'DirectoryIndex index.php' >> /etc/apache2/apache2.conf

# Allow Apache to use .htaccess and override settings in public directory
RUN echo '<Directory /var/www/html/public>' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

# Set dynamic port for Railway and expose the correct port
ENV PORT=8080
EXPOSE 8080

# Start Apache in the foreground
CMD ["apache2-foreground"]
