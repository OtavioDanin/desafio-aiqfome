# Default Dockerfile
#
# @link     https://www.hyperf.io
# @document https://hyperf.wiki
# @contact  group@hyperf.io
# @license  https://github.com/hyperf/hyperf/blob/master/LICENSE

FROM hyperf/hyperf:8.3-alpine-v3.22-swoole
LABEL maintainer="Hyperf Developers <group@hyperf.io>" version="1.0" license="MIT" app.name="Hyperf"

##
# ---------- env settings ----------
##
# --build-arg timezone=America/SaoPaulo
ARG timezone
ARG opcache_enable=1
ARG opcache_memory_consumption=256
ARG opcache_interned_strings_buffer=16
ARG opcache_max_accelerated_files=20000

ENV TIMEZONE=${timezone:-"America/Sao_Paulo"} \
    APP_ENV=prod \
    SCAN_CACHEABLE=(true) \
    PHP_OPCACHE_ENABLE=${opcache_enable} \
    PHP_OPCACHE_MEMORY_CONSUMPTION=${opcache_memory_consumption} \
    PHP_OPCACHE_INTERNED_STRINGS_BUFFER=${opcache_interned_strings_buffer} \
    PHP_OPCACHE_MAX_ACCELERATED_FILES=${opcache_max_accelerated_files}

# update
RUN set -ex \
    # show php version and extensions
    && php -v \
    && php -m \
    && php --ri swoole \
    # Configurações do OPcache
    && { \
        echo "opcache.enable=${PHP_OPCACHE_ENABLE}"; \
        echo "opcache.enable_cli=1"; \
        echo "opcache.memory_consumption=${PHP_OPCACHE_MEMORY_CONSUMPTION}"; \
        echo "opcache.interned_strings_buffer=${PHP_OPCACHE_INTERNED_STRINGS_BUFFER}"; \
        echo "opcache.max_accelerated_files=${PHP_OPCACHE_MAX_ACCELERATED_FILES}"; \
        echo "opcache.validate_timestamps=0"; \
        echo "opcache.jit_buffer_size=64M"; \
        echo "opcache.jit=1235"; \
        # echo "opcache.opcache.use_cwd=0"; \
    } > /etc/php83/conf.d/00_opcache.ini \
    #  ---------- some config ----------
    && cd /etc/php* \
    # - config PHP
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"
RUN set -ex \
    && apk --no-cache add php-pdo_pgsql php-pdo
    
WORKDIR /opt/www

# Composer Cache
# COPY ./composer.* /opt/www/
# RUN composer install --no-dev --no-scripts

COPY . /opt/www
RUN composer install --no-dev -o && php bin/hyperf.php

EXPOSE 9501

ENTRYPOINT ["php", "/opt/www/bin/hyperf.php", "start"]
