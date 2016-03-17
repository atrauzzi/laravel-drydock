FROM nginx:alpine
MAINTAINER "Alexander Trauzzi" <atrauzzi@gmail.com>

WORKDIR /tmp

ADD ./laravel/public /var/www/public

ADD /resources/nginx.conf /etc/nginx/nginx.conf
ADD /resources/nginx.default.conf /etc/nginx/conf.d/default.conf
