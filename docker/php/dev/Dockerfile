FROM php:7.4-fpm

ARG USER_ID
EXPOSE $USER_ID
ARG CACHE_DATE=not_a_date

#RUN adduser -u ${USER_ID} --disabled-password --gecos "" appuser
RUN useradd -ms /bin/bash appuser

RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser

RUN apt-get update

RUN apt-get install -y libpq-dev git acl openssl openssh-client wget libssh-dev libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev net-tools iputils-ping\
   && docker-php-ext-configure intl \
   && docker-php-ext-install intl \
   && docker-php-ext-install zip xml pdo_mysql

# Install xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> /usr/local/etc/php/php.ini \
    && echo "error_reporting = E_ALL"       >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_startup_errors = On"   >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_errors = On"           >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_enable=1"        >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#LDAP
RUN set -x \
    && apt-get update \
    && apt-get install -y libldap2-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install ldap \
    && apt-get purge -y --auto-remove libldap2-dev

# Install Redis extension
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

# Set ID
#RUN usermod -u 1000 www-data
# Change memory limit

RUN echo 'memory_limit = 2G ' >> /usr/local/etc/php/php.ini

# Create cache dir for composer
RUN mkdir /var/www/.composer && chown www-data /var/www/.composer

## Frontend tools
#RUN apt-get install -y curl gpgv
#RUN curl -sL https://deb.nodesource.com/setup_10.x | bash
#RUN apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin



RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Madrid /etc/localtime

RUN exit 0 #invalidate cache

USER appuser
COPY . /app
WORKDIR /app
