FROM ubuntu:18.04

RUN export DEBIAN_FRONTEND=noninteractive &&\
    apt-get update &&\
    apt-get install\
    -y php7.2 composer\
    php-mysql\
    php-mbstring\
    apache2\
    php7.2-xml

COPY . /var/www/html
WORKDIR /var/www/html
COPY config/php.ini /etc/php/7.2/apache2/
COPY config/000-default.conf /etc/apache2/sites-available/

RUN composer install &&\
    a2enmod rewrite &&\
    touch log &&\
    chmod 777 log

EXPOSE 80

ENTRYPOINT service apache2 start &&\
           /bin/bash