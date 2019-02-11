#!/usr/bin/env bash

echo "Install mysql"
/var/www/bin/console doctrine:database:create
/var/www/bin/console doctrine:schema:update --force
/var/www/bin/console fos:user:create admin@enhavo.com admin@enhavo.com admin
/var/www/bin/console fos:user:promote admin@enhavo.com ROLE_SUPER_ADMIN
