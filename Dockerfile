FROM php:7.4.6-apache

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql pgsql && apt-get purge -y --auto-remove

WORKDIR /usr/src/hello_world

COPY index.php .

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80"]
