# Pinned to bookworm (Debian 12) rather than the untagged default
# (which now resolves to trixie/Debian 13): trixie's newer xz-utils
# tries to enable a Landlock sandbox during package installs, which
# fails ("xz: Failed to enable the sandbox") on VPS virtualization
# layers that don't expose that kernel feature to the guest.
FROM php:8.2-fpm-bookworm

RUN apt-get update && apt-get install -y \
        git \
        curl \
        unzip \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
