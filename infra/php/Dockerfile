FROM php:fpm-alpine3.12
RUN apk --update --no-cache add git && apk --update --no-cache add bash
RUN docker-php-ext-install pdo_mysql
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
CMD composer install ;  php-fpm
EXPOSE 9000
