FROM php:7.4-fpm-alpine

RUN apk add --update \
    make \
    curl \
    bash \
    git \
    wget \
    nodejs \
    yarn

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    ln -s $(composer config --global home) /root/composer
ENV PATH=$PATH:/root/composer/vendor/bin COMPOSER_ALLOW_SUPERUSER=1

RUN  wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

ADD php.ini /etc/php/conf.d/
ADD php.ini /etc/php/cli/conf.d/
ADD php.ini /usr/local/etc/php
ADD php-fpm.conf /etc/php/php-fpm.d/

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql


WORKDIR /var/www/symfony

EXPOSE 9000

CMD ["php-fpm", "-F"]