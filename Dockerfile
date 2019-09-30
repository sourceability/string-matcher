FROM php:7.3-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer global require hirak/prestissimo

RUN apk add make git
