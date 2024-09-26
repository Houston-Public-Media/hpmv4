#!/bin/sh
unzip -qo /var/app/plugins.zip -d "/var/app/current/web/app/plugins" -x "__MACOSX/*"
chown -R webapp:webapp /var/app/current/web/app/plugins/
chmod -R 777 /var/app/current/web/app/uploads
rm -rf /var/app/current/web/wp/wp-content/themes/twenty*
cp /var/app/.env /var/app/current/.env
sed -i 's/if ( empty( \$author_text ) ) {/if ( empty( $author_text ) \&\& $i->current_author !== null \&\& !is_bool( $i->current_author ) ) {/' /var/app/current/web/app/plugins/co-authors-plus/template-tags.php