FROM php:8.4.17-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli
WORKDIR /var/www/html/
COPY web/ ./
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www
EXPOSE 80

