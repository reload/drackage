#!/bin/sh

cd /var/www/html
# Wait for DB server.
sleep 3
# This seems to trigger Twig to regenerate it's cached templates
# (which doesn't exists on first boot), which rebuild doesn't.
./core/scripts/drupal.sh http://drackage.dev/
