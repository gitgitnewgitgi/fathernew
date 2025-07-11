FROM php:8.2-apache

# ✅ Enable both rewrite and headers modules
RUN a2enmod rewrite headers

# Copy all files into the Apache root
COPY . /var/www/html/

# Optional: allow .htaccess overrides (important!)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set ownership
RUN chown -R www-data:www-data /var/www/html
