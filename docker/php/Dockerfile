FROM php:8.3.6-fpm-alpine3.19

ARG UID="1000"
ARG GID="1000"

COPY --from=composer/composer:2.7.2-bin /composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN addgroup --gid "${GID}" "app" && \
    adduser -H \
        --disabled-password \
        --gecos "" \
        --ingroup "app" \
        --uid "${UID}" \
        -s "/bin/sh" \
        "app"; \
    apk add --update --no-cache git postgresql-client postgresql-dev; \
    install-php-extensions zip dom libxml pdo_pgsql

COPY --chown=app:app . /app

WORKDIR /app

USER app

RUN composer install --no-interaction --optimize-autoloader