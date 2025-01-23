FROM nginx:stable-alpine

LABEL description="web-server"

COPY ./default.conf /etc/nginx/conf.d/default.conf

VOLUME [ "/var/www/auto_vendas" ]

EXPOSE 80