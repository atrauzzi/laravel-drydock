FROM ubuntu
MAINTAINER "Alexander Trauzzi" <atrauzzi@gmail.com>

WORKDIR /tmp

RUN apt-get update -y
RUN apt-get install -y nginx

ADD ./laravel/public /var/www/public

ADD /resources/nginx.conf /etc/nginx/nginx.conf
ADD /resources/nginx.default.conf /etc/nginx/sites-available/default

EXPOSE 8080

ENTRYPOINT ["/usr/sbin/nginx"]