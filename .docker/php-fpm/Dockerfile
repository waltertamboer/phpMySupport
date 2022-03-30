FROM php:8.1-fpm-alpine3.15 AS basebuild

SHELL ["/bin/ash", "-xeo", "pipefail", "-c"]

WORKDIR /opt/phpsupport

RUN addgroup -Sg 101 nginx; \
    adduser -H -h /dev/null -u 101 -G nginx -S -s /sbin/nologin -g nginx nginx; \
    adduser www-data nginx; \
    apk add --no-cache \
        bash \
        graphviz \
        ttf-freefont \
        icu \
        libpq \
        libzip \
        postgresql-client \
        tzdata; \
    apk --update add --no-cache --virtual .build-deps \
        autoconf \
        build-base \
        gnupg \
        icu-dev \
        libzip-dev \
        postgresql-dev; \
    docker-php-ext-install intl opcache pdo_pgsql zip;

COPY --chown=root:www-data .docker/php-fpm/usr/local/etc/php/conf.d/php.ini /usr/local/etc/php/conf.d/phpsupport.ini
COPY --chown=root:www-data .docker/php-fpm/usr/local/etc/php-fpm.d/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-phpsupport.conf

VOLUME ["/tmp", "/var/run/php"]

FROM basebuild AS development

COPY --chown=root:www-data .docker/php-fpm/usr/local/etc/php/conf.d/php-dev.ini /usr/local/etc/php/conf.d/zzz-phpsupport-dev.ini
COPY --chown=root:root .docker/php-fpm/usr/local/bin/dev-entrypoint.sh /usr/local/bin/dev-entrypoint.sh

RUN pecl install xdebug; \
    docker-php-ext-enable xdebug;

ENTRYPOINT ["/usr/local/bin/dev-entrypoint.sh"]
CMD ["php-fpm"]

FROM basebuild AS ci

COPY --chown=root:www-data .docker/php-fpm/usr/local/etc/php/conf.d/php-ci.ini /usr/local/etc/php/conf.d/phpsupport.ini
COPY --chown=root:root .docker/php-fpm/usr/local/bin/dev-entrypoint.sh /usr/local/bin/dev-entrypoint.sh
COPY --chown=root:root .docker/php-fpm/usr/local/bin/install-composer.sh /usr/local/bin/install-composer.sh

RUN apk add --no-cache git npm; \
    pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    /usr/local/bin/install-composer.sh; \
    rm /usr/local/bin/install-composer.sh; \
    mv composer.phar /usr/local/bin/composer; \
    composer self-update; \
    wget -q https://phpdoc.org/phpDocumentor.phar; \
    chmod +x phpDocumentor.phar; \
    mv phpDocumentor.phar /usr/local/bin/phpDocumentor; \
    npm install -g newman;

ENTRYPOINT ["/usr/local/bin/dev-entrypoint.sh"]
CMD ["php-fpm"]

FROM basebuild AS production

RUN apk del .build-deps; \
    docker-php-source delete; \
    rm -r /usr/local/bin/docker-php-source /usr/local/bin/docker-php-ext-* /usr/local/bin/php-config /usr/local/bin/pecl /usr/local/bin/pear /usr/local/bin/peardev /usr/local/bin/phpize /usr/local/lib/php/*.php /usr/local/lib/php/Archive /usr/local/lib/php/Console /usr/local/lib/php/OS /usr/local/lib/php/PEAR /usr/local/lib/php/Structures /usr/local/lib/php/XML /usr/local/lib/php/build /usr/local/lib/php/data /usr/local/lib/php/doc /usr/local/lib/php/test;

COPY --chown=root:www-data bin /opt/phpsupport/bin
COPY --chown=root:www-data config /opt/phpsupport/config
COPY --chown=root:www-data data /opt/phpsupport/data
COPY --chown=root:www-data public /opt/phpsupport/public
COPY --chown=root:www-data src /opt/phpsupport/src
COPY --chown=root:www-data vendor /opt/phpsupport/vendor

RUN chmod -R g+rw /opt/phpsupport/data/tmp /opt/phpsupport/data/logs /opt/phpsupport/data/cache; \
    chgrp www-data /opt/phpsupport;
