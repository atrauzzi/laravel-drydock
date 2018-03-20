FROM ubuntu:artful
MAINTAINER "Alexander Trauzzi" <atrauzzi@gmail.com>

WORKDIR /var/www

RUN apt-get update -y
RUN apt-get install -y \
	curl \
	git \
	mercurial \
	python \
	python-setuptools \
	graphicsmagick \
	libgraphicsmagick++1-dev \
	libgraphicsmagick1-dev \
	graphicsmagick-imagemagick-compat \
	php7.1-dev \
	php7.1-pgsql \
	php7.1-sqlite \
	php-redis \
	php7.1-json \
	php7.1-mcrypt \
	php7.1-zip \
	php7.1-curl \
	php7.1-gd \
	php7.1-fpm \
	php7.1-dom \
	php7.1-bcmath \
	php7.1-mbstring \
	php7.1-cli \
	php7.1-mysql \
	php7.1-memcached \
	php7.1-imagick \
	php7.1-gmagick

RUN apt-get install -y 

RUN pecl install --force gmagick

RUN easy_install pip

RUN phpenmod mcrypt

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer global require "laravel/installer"

RUN pip install hg+https://bitbucket.org/dbenamy/devcron#egg=devcron

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install -y nodejs

RUN npm install -g yarn
RUN npm install -g typescript
RUN npm install -g webpack
RUN npm install -g ts-node

RUN touch /var/log/cron.log

RUN chmod 777 /run
RUN chmod 770 /home

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENTRYPOINT ["/usr/sbin/php-fpm7.1"]
CMD ["-F", "-R"]
