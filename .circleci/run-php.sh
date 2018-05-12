#!/bin/sh

# Print commands and their arguments as they are executed
set -xe

php bin/console doctrine:query:sql 'CREATE EXTENSION IF NOT EXISTS postgis;'
php bin/console doctrine:query:sql 'CREATE EXTENSION IF NOT EXISTS postgis_topology;'
php bin/console doctrine:migration:migrate --no-interaction
