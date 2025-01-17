# Step 1: Start with a base image (Ubuntu in your case)
FROM ubuntu:latest

# Step 2: Set the authors label
LABEL authors="it-illyria"

# Step 3: Install dependencies for PHP and Apache
RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    php-mysqli \
    php-gd \
    libapache2-mod-php \
    && apt-get clean

# Step 4: Enable Apache rewrite module (if you're using pretty URLs)
RUN a2enmod rewrite

# Step 5: Set the working directory inside the container (where your app will live)
WORKDIR /var/www/html

# Step 6: Copy your project files into the container
COPY . /var/www/html/

# Step 7: Set the correct permissions for the Apache server to access the files
RUN chown -R www-data:www-data /var/www/html

# Step 8: Expose port 80 to make your app accessible
EXPOSE 80

# Step 9: Set the entry point to start Apache in the foreground
ENTRYPOINT ["apache2ctl", "-D", "FOREGROUND"]
