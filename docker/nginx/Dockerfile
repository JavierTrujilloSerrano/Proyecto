FROM nginx:1.25.4-alpine
COPY ./docker/nginx/config/nginx.conf /etc/nginx/nginx.conf
RUN adduser -u 1000 -D -S -G www-data www-data \
    && mkdir -p /var/www/html/public \
    && chown -R www-data:www-data /var/www \
