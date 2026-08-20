# PHP 7.2 — Laravel 5.5's official ceiling; vendor/ is the frozen 2018 tree
# (committed, no composer install: several of its packages predate composer 2
# and PHP 7.3+ deprecations).
FROM php:7.2-apache

ENV TZ=Europe/Warsaw
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN docker-php-ext-install pdo_mysql

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
        /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
    && a2enmod rewrite

COPY --chown=www-data:www-data . /var/www/html/
WORKDIR /var/www/html

RUN cp -a storage /var/storage-skel

COPY docker/entrypoint.sh /usr/local/bin/dice-entrypoint
RUN chmod +x /usr/local/bin/dice-entrypoint

ENTRYPOINT ["dice-entrypoint"]
CMD ["apache2-foreground"]
