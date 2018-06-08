FROM php:7.0.4-fpm

MAINTAINER Johan Poirier, johan.poirier@gmail.com

# PHP
COPY ./config/php.ini /usr/local/etc/php/
RUN apt-get update
RUN docker-php-ext-install pdo pdo_mysql

# Supervisor
RUN apt-get update && apt-get install -y supervisor && mkdir -p /var/log/supervisor
COPY ./config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Nginx
RUN apt-key adv --keyserver hkp://pgp.mit.edu:80 --recv-keys 573BFD6B3D8FBC641079A6ABABF5BD827BD9BF62 \
	&& echo "deb http://nginx.org/packages/mainline/debian/ jessie nginx" >> /etc/apt/sources.list \
	&& apt-get update \
	&& apt-get install -y ca-certificates nginx gettext-base \
	&& rm -rf /var/lib/apt/lists/*
RUN rm /etc/nginx/conf.d/default.conf
COPY ./config/nginx.conf /etc/nginx/nginx.conf
COPY ./config/bet4soccer.conf /etc/nginx/conf.d/bet4soccer.conf
RUN mkdir /etc/nginx/ssl/ && openssl req -new -nodes -x509 -subj "/C=FR/ST=France/L=Lyon/O=FR/CN=bet4soccer.lxc" -days 3650 -keyout /etc/nginx/ssl/bet4soccer.lxc.key -out /etc/nginx/ssl/bet4soccer.lxc.crt -extensions v3_ca

# Vim
RUN apt-get update && apt-get install -y vim

# MySQL
RUN apt-get update \
&& apt-get install -y debconf-utils \
&& echo mysql-server-5.5 mysql-server/root_password password root | debconf-set-selections \
&& echo mysql-server-5.5 mysql-server/root_password_again password root | debconf-set-selections \
&& apt-get install -y mysql-server-5.5 -o pkg::Options::="--force-confdef" -o pkg::Options::="--force-confold" --fix-missing
COPY ./mysql /tmp/mysql
RUN /tmp/mysql/init.sh

# Run
CMD ["/usr/bin/supervisord", "-n"]
