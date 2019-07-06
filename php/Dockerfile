FROM php:7.2.12-fpm
RUN apt-get update && apt-get install -y \
   vim \
   zip \
   unzip \
   iputils-ping \
   git
RUN docker-php-ext-install -j$(nproc) \
    mbstring \
    pdo \
    pdo_mysql \
    mysqli \
&& yes "" | pecl install xdebug \
&& docker-php-ext-enable xdebug