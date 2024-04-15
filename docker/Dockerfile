# Use a imagem base do PHP 8.0.1 FPM
FROM php:8.0.1-fpm

RUN apt-get clean
RUN apt-get update && apt-get install -y \
        zlib1g-dev libicu-dev g++ \
        libjpeg62-turbo-dev \
        libzip-dev \
        libpng-dev \
        libwebp-dev \
        libfreetype6-dev \
    	libxml2-dev \
    	git \
    	zip \
    	unzip \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-configure gd --with-webp=/usr/include/webp --with-jpeg=/usr/include --with-freetype=/usr/include/freetype2/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install mysqli 

# Atualize o sistema e instale as dependências necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev zip \
    && docker-php-ext-install pdo pdo_mysql

# Instale o Composer 2.4.2
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && HASH=`curl -sS https://composer.github.io/installer.sig` \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.4.2 \
    && rm composer-setup.php

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Comandos a serem executados ao criar o contêiner
CMD cd /var/www/html/api \
     && COMPOSER_MEMORY_LIMIT=-1 composer install \
     && ./vendor/bin/openapi --output swagger.json src \
     && ./vendor/bin/phpunit tests \
     && cd public/ && php -S 0.0.0.0:8080

EXPOSE 8080
