#!/usr/bin/env bash
unzip -qo /var/app/plugins.zip -d "/var/app/current/web/app/plugins" -x "__MACOSX/*"
chmod -R 777 /var/app/current/web/app/uploads
rm -rf /var/app/current/web/wp/wp-content/themes/twenty*
cp /var/app/.env /var/app/current/.env