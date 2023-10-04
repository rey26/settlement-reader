FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip libpq-dev wget vim libfreetype6-dev libjpeg62-turbo-dev libpng-dev libxslt1-dev \
    && docker-php-ext-install intl opcache pdo pdo_pgsql pgsql \
    && pecl install apcu xdebug\
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install xsl \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

WORKDIR /var/www/settlement-reader

RUN echo "short_open_tag = Off" > /usr/local/etc/php/conf.d/short-tag.ini
RUN echo "memory_limit = 4096M" > /usr/local/etc/php/conf.d/docker-php-memlimit.ini
RUN echo 'date.timezone = "Europe/Bratislava"' > /usr/local/etc/php/conf.d/docker-php-timezone.ini
RUN echo 'upload_max_filesize = 50M' > /usr/local/etc/php/conf.d/docker-upload-max-filesize.ini
RUN echo 'post_maxsize = 50M' > /usr/local/etc/php/conf.d/docker-post-max-filesize.ini
RUN echo 'max_input_vars = 3000' > /usr/local/etc/php/conf.d/docker-max-input-vars.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
