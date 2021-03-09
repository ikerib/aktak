FROM php:7.4-fpm

ARG USER_ID
EXPOSE $USER_ID
ARG CACHE_DATE=not_a_date

ARG USER_NAME="appuser"
ARG USER_PASSWORD="appuser"

ENV USER_NAME $USER_NAME
ENV USER_PASSWORD $USER_PASSWORD

#RUN adduser -u ${USER_ID} --disabled-password --gecos "" appuser
##RUN useradd -ms /bin/bash appuser

#RUN mkdir /home/appuser/.ssh
#RUN chown -R appuser:appuser /home/appuser

RUN apt-get update
RUN apt-get install -y libpq-dev git acl openssl openssh-client wget libssh-dev libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev net-tools iputils-ping zsh gnupg2 gnupg gnupg1 locales vim fonts-powerline sudo \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip xml pdo_mysql \
    # set up locale
    && locale-gen es_ES.UTF-8 \
    && echo "LC_ALL=es_ES.UTF-8" >> /etc/environment \
    && echo "LANG=es_ES.UTF-8" >> /etc/environment \
    && echo "LC_ALL=es_ES.UTF-8" >> /etc/default/locale \
    && echo "LANG=es_ES.UTF-8" >> /etc/default/locale \
     # add a user (--disabled-password: the user won't be able to use the account until the password is set)
     && adduser --quiet -u ${USER_ID} --disabled-password --shell /bin/zsh --home /home/$USER_NAME --gecos "User" $USER_NAME \
     # update the password
     && echo "${USER_NAME}:${USER_PASSWORD}" | chpasswd && usermod -aG sudo $USER_NAME \
     && adduser ${USER_NAME} sudo \
     && echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

# OPCache
RUN docker-php-ext-configure opcache --enable-opcache \
        && docker-php-ext-install opcache
COPY ./docker/php/dev/config/opcache.ini /usr/local/etc/php/conf.d/

# Install APCu and APC backward compatibility
RUN pecl install apcu \
    && pecl install apcu_bc-1.0.3 \
    && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini

# Install xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> /usr/local/etc/php/php.ini \
    && echo "error_reporting = E_ALL"           >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_startup_errors = On"       >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_errors = On"               >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug"                 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes"     >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=1"     >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

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
RUN sed -ie 's/memory_limit\ =\ 128M/memory_limit\ =\ 2G/g' /usr/local/etc/php/php.ini
RUN sed -ie 's/\;date\.timezone\ =/date\.timezone\ =\ Europe\/Madrid/g' /usr/local/etc/php/php.ini
RUN sed -ie 's/upload_max_filesize\ =\ 2M/upload_max_filesize\ =\ 200M/g' /usr/local/etc/php/php.ini
RUN sed -ie 's/post_max_size\ =\ 8M/post_max_size\ =\ 200M/g' /usr/local/etc/php/php.ini

# Create cache dir for composer
RUN mkdir /var/www/.composer && chown www-data /var/www/.composer

# Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install -y yarn

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin

RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Madrid /etc/localtime

RUN exit 0 #invalidate cache

#USER appuser
USER $USER_NAME
ENV ZSH_THEME agnoster

# run the installation script
RUN wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | zsh || true
COPY ./docker/php/dev/.zshrc /home/appuser/.zshrc

COPY . /app
WORKDIR /app
