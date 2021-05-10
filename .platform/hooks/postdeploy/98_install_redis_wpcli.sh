#!/bin/sh
FILE=/usr/local/bin/wp
if test -f "$FILE"; then
    echo ""
else
    cd /home/ec2-user/
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x /home/ec2-user/wp-cli.phar
    mv /home/ec2-user/wp-cli.phar /usr/local/bin/wp
fi