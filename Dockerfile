FROM php:7.4-fpm-alpine3.11

WORKDIR /usr/src/hello_world

COPY index.php .

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80"]
