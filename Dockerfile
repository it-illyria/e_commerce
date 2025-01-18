# Use the official PHP image with Apache
FROM php:7.4-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Copy the local .htaccess file into the Apache web directory
COPY ./.htaccess /var/www/html/.htaccess

# Set the working directory to the root of your project
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
