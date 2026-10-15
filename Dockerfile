FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html/event_ticketing
COPY event_ticketing/ .

RUN echo '<?php header("Location: /event_ticketing/public/index.php?controller=event&action=index");' > /var/www/html/index.php

RUN mkdir -p public/uploads && chown -R www-data:www-data public/uploads

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
  CMD curl -fs http://localhost/event_ticketing/public/index.php || exit 1