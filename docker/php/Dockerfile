FROM php:7.2-apache

COPY . /srv/app

COPY ./docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install pdo_mysql \
&& a2enmod rewrite negotiation


WORKDIR /srv/app


#CMD ["php" , "artisan" , "migrate"]
