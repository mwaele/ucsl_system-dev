FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libpq-dev \
    libmagickwand-dev \
    wkhtmltopdf \
    libxrender1 \
    libfontconfig1 \
    libxext6 \
    libfreetype6-dev \
    libssl-dev \
    supervisor

# PHP extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd sockets

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js + NPM
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

WORKDIR /var/www

COPY . .

RUN composer install && npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
