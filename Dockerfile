FROM php:8.1-alpine
COPY . /
CMD php -S 0.0.0.0:80 -t /app/public
WORKDIR /app