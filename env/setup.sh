#!/bin/bash

WP_THEME_NAME=mysite

# theme activate
wp theme activate $WP_THEME_NAME

wp rewrite structure /%post_id%/
wp rewrite flush --hard

# remove sample post
wp post delete $(wp post list --name=hello-world --post_type=post --format=ids)
wp post delete $(wp post list --name=sample-page --post_type=page --format=ids)


# update option
wp option update blogname ''
wp option update blogdescription ''
wp option update timezone_string $(wp eval "echo _x( '0', 'default GMT offset or timezone string' );")
wp option update date_format $(wp eval "echo __( 'Y-m-d' );")
wp option update time_format $(wp eval "echo __( 'H:i' );")

# remove unused plugin
# wp plugin delete hello.php akismet

# plugin activate
# wp plugin activate --all

# theme activate
# wp theme activate $WP_THEME_NAME

echo 'WP Setup Complete'

exit 0
