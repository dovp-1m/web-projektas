FROM php:8.2-apache

# ── System deps ──────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libzip-dev \
        unzip \
        curl \
    && rm -rf /var/lib/apt/lists/*

# ── PHP extensions ───────────────────────────────────────────
RUN docker-php-ext-install pdo pdo_mysql mysqli gd zip

# ── Apache mod_rewrite (CodeIgniter needs clean URLs) ────────
RUN a2enmod rewrite

# ── Apache vhost config ──────────────────────────────────────
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# ── PHP ini tweaks ───────────────────────────────────────────
RUN echo "upload_max_filesize = 10M\n\
post_max_size = 12M\n\
display_errors = On\n\
error_reporting = E_ALL" >> /usr/local/etc/php/php.ini

WORKDIR /var/www/html
