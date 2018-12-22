FROM php:7.3-apache
COPY /file-management-site/ /var/www/html/file-management-site
COPY /php/ /var/www/html/php
COPY /portfolio-site /var/www/html/portfolio-site
COPY /download_password.php /var/www/html/download_password.php
COPY /uploads/.htaccess /var/www/html/uploads/.htaccess
COPY /download.php /var/www/html/download.php
COPY /header.php /var/www/html/header.php
COPY /index.php /var/www/html/index.php
COPY /view.php /var/www/html/view.php
EXPOSE 80
EXPOSE 443