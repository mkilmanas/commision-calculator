#!/usr/bin/env bash

DEFAULT_APP_DIR='/var/www'

APP_DIR=${1:-$DEFAULT_APP_DIR}

apt-add-repository ppa:ondrej/php -y
apt-get update
apt-get install -y \
    php7.2-cli \
    php7.2-ctype \
    php7.2-iconv \
    php7.2-xml \
    php7.2-mbstring

EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    >&2 echo 'ERROR: Invalid installer signature'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

cd $APP_DIR
cp .env.dist .env
cp phpspec.yml.dist phpspec.yml
cp behat.yml.dist behat.yml
composer install

mkdir -p /dev/shm/symfony/cache/
chmod 777 /dev/shm/symfony/cache/
