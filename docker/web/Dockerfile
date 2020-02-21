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

# https://github.com/Kozea/WeasyPrint
RUN apt install -y \
    build-essential \
    python3-dev \
    python3-pip \
    python3-setuptools \
    python3-wheel \
    python3-cffi \
    libcairo2 \
    libpango-1.0-0 \
    libpangocairo-1.0-0 \
    libgdk-pixbuf2.0-0 \
    libffi-dev \
    shared-mime-info \
	python3-venv

RUN python3 -m venv ./venv
RUN . ./venv/bin/activate
RUN pip3 install WeasyPrint

# end of WeasyPrint


