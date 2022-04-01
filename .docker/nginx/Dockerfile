FROM nginx:1.21-alpine

SHELL ["/bin/ash", "-xeo", "pipefail", "-c"]

COPY --chown=root:nginx public /opt/phpmysupport/public
COPY --chown=root:nginx .docker/nginx/etc/nginx/conf.d/application.conf /etc/nginx/conf.d/application.conf

RUN rm /etc/nginx/conf.d/default.conf; \
    chgrp nginx /opt/phpmysupport; \
    sed -i 's#/var/run/nginx\.pid#/tmp/nginx.pid#g' /etc/nginx/nginx.conf; \
    chown nginx:nginx /var/cache/nginx;

VOLUME ["/var/run/php"]
EXPOSE 8000
