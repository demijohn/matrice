FROM php:7.4.2-apache

RUN apt-get update && apt-get install -y \
  g++ \
  git \
  unzip \
  libzip-dev \
  libicu-dev

# Install extensions
RUN docker-php-ext-configure zip \
    && docker-php-ext-install opcache pcntl pdo_mysql zip intl

# Install XDebug
RUN pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > $PHP_INI_DIR/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> $PHP_INI_DIR/conf.d/xdebug.ini

# Use the default development configuration
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="1"
COPY docker/php/conf.d/opcache.ini $PHP_INI_DIR/conf.d/

# Enable apache mod rewrite
RUN a2enmod rewrite

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
RUN chown -R www-data.www-data .
