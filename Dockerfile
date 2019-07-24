FROM php:5.6

RUN pecl install xdebug-2.5.5
RUN docker-php-ext-enable xdebug

RUN curl -sfLo /usr/local/bin/composer https://getcomposer.org/composer.phar; chmod a+x /usr/local/bin/composer

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
  && rm -rf /var/lib/apt/lists/*

ADD . /app
WORKDIR /app
RUN rm -rf vendor composer.lock

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN php -n /usr/local/bin/composer install --prefer-dist --no-interaction
