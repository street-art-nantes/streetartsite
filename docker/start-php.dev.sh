#!/bin/sh

# Print commands and their arguments as they are executed
set -xe

# Clean var folder and create
rm -rf var || true
mkdir -p var/cache var/log

# Composer install
composer install --prefer-dist --no-scripts --no-progress --no-suggest --optimize-autoloader --classmap-authoritative

# Make rights on var folder
chmod -R 777 var/cache var/log

# Install assets
php bin/console asset:install

# Sync database
php bin/console doctrine:schema:update --force --dump-sql

# Start php-fpm
php-fpm
