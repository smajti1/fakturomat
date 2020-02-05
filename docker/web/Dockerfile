FROM php:7.4-fpm

ARG DOCKER_WORK_DIR

RUN usermod -u 1000 www-data --home="${DOCKER_WORK_DIR}/storage/docker/home/www-data"

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -

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
    # wkhtmltopdf dependencies
    fontconfig \
    libjpeg62-turbo \
    libx11-6 \
    libxcb1 \
    libxext6 \
    libxrender1 \
    xfonts-75dpi \
    xfonts-base \
    # end wkhtmltopdf dependencies
    # libonig-dev = php-mbstring
    libonig-dev \
    nodejs \
    && apt remove --purge --auto-remove -y

RUN npm update && npm install -g npm@6.*

RUN docker-php-ext-install pgsql gd soap pdo_pgsql zip intl
RUN curl --insecure https://getcomposer.org/composer-stable.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

RUN locale-gen en_US.utf8 pl_PL.utf8

RUN echo Europe/Warsaw >/etc/timezone && \
    ln -sf /usr/share/zoneinfo/Europe/Warsaw /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata

RUN mkdir -p public/uploads

# tip check linux codename by running command "lsb_release -a" for exmple debian 9.9 has codename: stretch
RUN curl -sOSL https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.5/wkhtmltox_0.12.5-1.stretch_amd64.deb && \
    dpkg -i wkhtmltox_0.12.5-1.stretch_amd64.deb
