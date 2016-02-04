FROM ubuntu:wily
MAINTAINER "Alexander Trauzzi" <atrauzzi@gmail.com>

WORKDIR /var/www

RUN apt-get update -y
RUN apt-get install -y \
    curl \
    php5-pgsql \
    php5-redis \
    php5-json \
    php5-mcrypt \
    php5-curl \
    php5-gd \
    php5-fpm \
    php5-cli

RUN php5enmod mcrypt

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD ./laravel /var/www
ADD /resources/php-fpm.conf /etc/php5/fpm/php-fpm.conf
ADD /resources/php-fpm.www.conf /etc/php5/fpm/pool.d/www.conf

ADD /resources/artisan /usr/local/bin/artisan
RUN chmod +x /usr/local/bin/artisan

RUN chmod 777 /run

EXPOSE 9000

ENTRYPOINT ["/usr/sbin/php5-fpm"]
CMD ["-F", "-R"]