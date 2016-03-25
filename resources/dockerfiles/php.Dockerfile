FROM ubuntu:wily
MAINTAINER "Alexander Trauzzi" <atrauzzi@gmail.com>

WORKDIR /tmp

RUN apt-get update -y
RUN apt-get install -y \
    php5-pgsql \
    php5-redis \
    php5-json \
    php5-mcrypt \
    php5-curl \
    php5-gd \
    php5-fpm \
    php5-cli

RUN php5enmod mcrypt

ADD ./laravel /var/www
ADD ./resources/php-fpm.conf /etc/php5/fpm/php-fpm.conf
ADD ./resources/php-fpm.www.conf /etc/php5/fpm/pool.d/www.conf
ADD ./resources/artisan /usr/local/bin/artisan
RUN chmod +x /usr/local/bin/artisan

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 9000

ENTRYPOINT ["/usr/sbin/php5-fpm"]
CMD ["-F", "-R"]