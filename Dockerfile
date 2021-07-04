FROM alpine:3.13

RUN apk update \
    && apk upgrade \
    && apk add \
        php8 \
        php8-bcmath \
        php8-cli \
        php8-ctype \
        php8-curl \
        php8-dom \
        php8-fileinfo \
        php8-gmp \
        php8-iconv \
        php8-intl \
        php8-json \
        php8-mbstring \
        php8-openssl \
        php8-pdo \
        php8-pdo_mysql \
        php8-phar \
        php8-tokenizer \
        php8-simplexml \
        php8-xml \
        php8-xmlwriter \
        php8-zlib \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN cp /usr/bin/php8 /usr/bin/php
ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /app
