FROM php:8.3-apache

# --- Install system packages ---
RUN apt-get update && apt-get install -y \
    zip unzip curl git cron libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libbz2-dev libicu-dev libxslt1-dev libreadline-dev nano g++ \
    nodejs npm gnupg

# --- Optional: Install latest Node.js (v18) ---
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# --- Install PHP Extensions ---
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql zip gd bcmath intl opcache bz2 exif fileinfo mbstring xml xsl soap

# --- Apache Config ---
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite headers

# --- PHP Config ---
ENV COMPOSER_MEMORY_LIMIT=-1
RUN echo 'upload_max_filesize = 10M' >> /usr/local/etc/php/conf.d/max.ini

# --- Install Composer ---
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- Copy Project Files ---
COPY . /var/www/html
WORKDIR /var/www/html

# --- Composer Dependencies ---
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# --- NPM Setup (install and build only) ---
RUN npm install && npm run build

# --- Permissions ---
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R ug+rwX storage bootstrap/cache

# --- Copy Entrypoint Script ---
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
