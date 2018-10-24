FROM ubuntu:18.04

RUN export DEBIAN_FRONTEND=noninteractive &&\
    apt-get update &&\
    apt-get install\
    -y php7.2 composer\
    php-mysql\
    php-mbstring\
    mysql-server-5.7\
    apache2\
    php7.2-xml

COPY . /var/www/html
WORKDIR /var/www/html
COPY config/php.ini /etc/php/7.2/apache2/
COPY config/000-default.conf /etc/apache2/sites-available/

RUN service mysql start &&\
    chmod 777 log &&\
    composer install &&\
    mysql -u root < tables.sql &&\
    a2enmod rewrite &&\
    printf '2' | php ./main.php

EXPOSE 80

ENTRYPOINT /bin/bash service apache2 start &&\
           /bin/bash service mysql start &&\
           /bin/bash