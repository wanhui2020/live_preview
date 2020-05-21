FROM registry.cn-hangzhou.aliyuncs.com/qskane/php72:latest

WORKDIR /var/www/v1
COPY --chown=www:www ./ /var/www/v1

USER www

EXPOSE 9000

CMD ["php-fpm"]
