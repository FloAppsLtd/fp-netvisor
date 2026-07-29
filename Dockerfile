FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libxml2-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-install dom \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-interaction --no-progress --prefer-dist

COPY . .

CMD ["vendor/bin/phpunit", "-c", "tests", "--coverage-text"]



