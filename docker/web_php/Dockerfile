FROM php:8.3-fpm

ARG UID=1000
ENV NODE_MAJOR=20


RUN usermod -u ${UID} www-data --home="/var/www/storage/docker/home/www-data"

RUN apt update -yqq  && apt install -y ca-certificates gnupg
RUN mkdir -p /etc/apt/keyrings && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list

RUN apt update -yqq && apt install -y \
    git \
    libmcrypt-dev \
    libpq-dev \
    locales \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libxml2-dev \
    zip \
    # install postgresql-client only for pg_dump to make automatic database dump
    postgresql-client \
    # libonig-dev = php-mbstring
    libonig-dev \
    nodejs \
    chromium \
    && apt remove --purge --auto-remove -y

RUN npm update && npm install -g npm@10.*

RUN docker-php-ext-install pgsql gd soap pdo_pgsql zip intl sockets
## xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
## end xdebug
RUN curl --insecure https://getcomposer.org/composer-stable.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

RUN locale-gen en_US.utf8 pl_PL.utf8

RUN echo Europe/Warsaw >/etc/timezone && \
    ln -sf /usr/share/zoneinfo/Europe/Warsaw /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata

RUN mkdir -p public/uploads

USER www-data