#!/bin/sh

# Print commands and their arguments as they are executed
set -xe

.circleci/tools/php-cs-fixer.sh
./vendor/bin/phpmd src text .circleci/tools/phpmd.xml
./vendor/bin/phpstan analyse --level 2 -c .circleci/tools/phpstan.neon src
