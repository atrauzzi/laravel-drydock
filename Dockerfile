FROM ubuntu:xenial
MAINTAINER "Alexander Trauzzi" <atrauzzi@gmail.com>

WORKDIR /var/www

RUN apt-get update -y
RUN apt-get install -y \
	curl \
	git \
	mercurial \
	python \
	python-setuptools \
	php7.0-pgsql \
	php7.0-sqlite \
	php-redis \
	php7.0-json \
	php7.0-mcrypt \
	php7.0-zip \
	php7.0-curl \
	php7.0-gd \
	php7.0-fpm \
	php7.0-dom \
	php7.0-bcmath \
	php7.0-mbstring \
	php7.0-cli

RUN easy_install pip

RUN phpenmod mcrypt

RUN curl -sL https://deb.nodesource.com/setup_5.x | bash -
RUN apt-get install -y nodejs

RUN npm install -g jspm@beta
RUN npm install -g gulp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pip install hg+https://bitbucket.org/dbenamy/devcron#egg=devcron

RUN touch /var/log/cron.log

COPY /resources/artisan /usr/local/bin/artisan
RUN chmod +x /usr/local/bin/artisan

RUN chmod 777 /run
RUN chmod 770 /home

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 9000

ENTRYPOINT ["/usr/sbin/php-fpm7.0"]
CMD ["-F", "-R"]
