FROM php:7.4-fpm

ARG USER_ID
EXPOSE $USER_ID
ARG CACHE_DATE=not_a_date

#RUN adduser -u ${USER_ID} --disabled-password --gecos "" appuser
RUN useradd -ms /bin/bash appuser

RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser

RUN apt-get update

RUN apt-get install -y libpq-dev git acl openssl openssh-client wget libssh-dev libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
   && docker-php-ext-configure intl \
   && docker-php-ext-install intl \
   && docker-php-ext-install zip xml pdo_mysql

#LDAP
RUN set -x \
    && apt-get update \
    && apt-get install -y libldap2-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install ldap \
    && apt-get purge -y --auto-remove libldap2-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin

RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Madrid /etc/localtime

RUN exit 0 #invalidate cache

USER appuser
COPY . /app
WORKDIR /app
