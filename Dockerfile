# Start with a base image that has PHP 8.2 and Apache web server
FROM php:8.2-apache

# Set the working directory inside this mini-computer to /var/www/html
# This is where web servers usually look for website files.
WORKDIR /var/www/html

# Copy ALL files from your current project folder (.)
# into the /var/www/html folder inside the mini-computer.
COPY . /var/www/html/

# If your PHP application needs URL rewriting (like if you're using a framework
# that makes nice-looking URLs, e.g., notesync.com/about instead of notesync.com/?page=about),
# uncomment the line below to enable it in Apache.
# RUN a2enmod rewrite

# Tell this mini-computer that it will be serving web traffic on port 80.
# Google Cloud Run will automatically map its external port to this internal port.
EXPOSE 80

# The base image already has a command to start Apache, so we don't need a CMD line here.