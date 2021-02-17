FROM php:8.0-alpine3.13

COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache curl gettext make git

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
        autoconf \
        gcc \
        g++ \
        pkgconf; \
        pecl -v install xdebug; \
		pecl clear-cache; \
		apk del .build-deps

COPY var var/
COPY docker/php/99-xdebug.ini $PHP_INI_DIR/conf.d/
