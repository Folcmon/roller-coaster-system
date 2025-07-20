FROM php:8.4-fpm as base
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libssl-dev \
    git \
    unzip \
    wget \
    curl \
    vim \
    nano
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install intl
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install opcache
RUN docker-php-ext-install dom
RUN docker-php-ext-install xml
RUN docker-php-ext-enable pdo_mysql
RUN docker-php-ext-enable intl
RUN docker-php-ext-enable mbstring
RUN docker-php-ext-enable bcmath
RUN docker-php-ext-enable opcache
RUN docker-php-ext-enable dom
RUN docker-php-ext-enable xml
RUN pecl install redis && docker-php-ext-enable redis

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer


FROM base as development

RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./xdebug.ini $PHP_INI_DIR/conf.d/xdebug.ini

RUN pecl channel-update pecl.php.net && \
    pecl install xdebug-3.4.0 && \
    docker-php-ext-enable xdebug

FROM base as app

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY ./app /var/www/html
EXPOSE 9000
CMD ["php-fpm"]