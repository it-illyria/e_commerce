# Use an official Ubuntu as a base image
FROM ubuntu:latest

# Set working directory
WORKDIR /var/www/html

# Update and install necessary packages
RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    php-mysqli \
    php-gd \
    libapache2-mod-php \
    && apt-get clean

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache to allow .htaccess overrides
RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/apache2.conf

# Copy the content of the current directory into the Apache document root
COPY . /var/www/html/

# Ensure proper ownership for Apache
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for the web service
EXPOSE 80

# Start Apache in the foreground
CMD ["apachectl", "-D", "FOREGROUND"]
