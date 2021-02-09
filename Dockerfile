FROM php:8.0-alpine3.13

COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache curl gettext make git
