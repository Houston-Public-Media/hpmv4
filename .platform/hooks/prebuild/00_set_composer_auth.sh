#!/bin/sh
/opt/elasticbeanstalk/bin/get-config environment -k COMPOSER_AUTH >> /var/app/staging/auth.json
env COMPOSER_ALLOW_SUPERUSER=1