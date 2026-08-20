#!/bin/sh
# Seed the storage volume on first start.
set -e
if [ ! -d /var/www/html/storage/framework ]; then
    cp -a /var/storage-skel/. /var/www/html/storage/
    chown -R www-data:www-data /var/www/html/storage
fi
exec "$@"
