FROM ubuntu:22.04 AS cli

ENV TERM=linux
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get install -y --no-install-recommends gnupg \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ondrej-php.list \
    && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 4F4EA0AAE5267A6C \
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        ca-certificates \
        curl \
        unzip \
        php7.2-apcu php7.2-cli php7.2-curl php7.2-mbstring php7.2-opcache php7.2-readline php7.2-xml php7.2-zip \
        php7.2-bcmath php7.2-soap php7.2-http php7.2-raphf php7.2-propro  \
        php7.2-interbase php7.2-xdebug php7.2-intl php7.2-pdo php7.2-mysql php7.2-gd  php7.2-mysqlnd php7.2-opcache  \
        php7.2-pdo php7.2-xml php7.2-apcu php7.2-bcmath php7.2-calendar php7.2-ctype php7.2-curl php7.2-dom php7.2-exif  \
        php7.2-json php7.2-fileinfo php7.2-ftp php7.2-gettext php7.2-iconv php7.2-intl php7.2-mbstring php7.2-mysqli  \
        php7.2-phar php7.2-posix php7.2-raphf php7.2-readline php7.2-shmop  \
        php7.2-simplexml php7.2-soap php7.2-sockets php7.2-sysvmsg php7.2-sysvsem php7.2-sysvshm php7.2-tokenizer php7.2-xdebug  \
        php7.2-xmlreader php7.2-xmlwriter php7.2-xsl php7.2-zip php7.2-http php7.2-imagick \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* ~/.composer

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

CMD ["php", "-a"]

FROM cli AS fpm

RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.2-fpm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

STOPSIGNAL SIGQUIT

COPY overrides.conf /etc/php/7.2/fpm/pool.d/z-overrides.conf

CMD ["/usr/sbin/php-fpm7.2", "-O" ]

# Open up fcgi port
EXPOSE 9000

RUN apt-get update \
    && apt-get -y --no-install-recommends install git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


WORKDIR "/application"

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -g www-data www-data &&\
    install -d -m 777 -o www-data -g www-data /application && \
    mkdir "/home/www-data" && chown www-data:www-data "/home/www-data" && \
    chmod 777 "/home/www-data"

RUN git config --global --add safe.directory /application

env NODE_VERSION 18

RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
        && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
        && apt-get update \
        && apt-get install -y nodejs \
        && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null \
        && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
        && apt-get update \
        && apt-get install -y yarn \
        && corepack enable \
        && apt-get clean \
        && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


ENV YARN_VERSION=3.6.1
USER www-data
RUN corepack prepare yarn@$YARN_VERSION --activate && \
    yarn set version $YARN_VERSION &&  yarn config set --home enableTelemetry 0
RUN git config --global --add safe.directory /application
USER root
