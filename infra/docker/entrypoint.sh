#!/bin/bash
set -e

chown -R www-data:www-data /var/app/data

if [ -d /mnt/applications ]
then
    chown -R www-data:www-data /mnt/applications
fi

exec "$@"
