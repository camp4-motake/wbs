#!/bin/bash

# wp-cli - wp i18n
# https://developer.wordpress.org/cli/commands/i18n/

set -e

if [ $# -eq 0 ]; then
    echo "No options"
    exit 1
fi

if [ $1 = "--make-pot" ]; then

  wp i18n make-pot wp-content/themes/$WP_THEME_NAME wp-content/themes/$WP_THEME_NAME/lang/$WP_THEME_NAME.pot --skip-theme-json

elif [ $1 = "--make-mo" ]; then

  wp i18n make-mo wp-content/themes/$WP_THEME_NAME/lang wp-content/themes/$WP_THEME_NAME/lang

elif [ $1 = "--update-po" ]; then

  wp i18n update-po wp-content/themes/$WP_THEME_NAME/lang/$WP_THEME_NAME.pot wp-content/themes/$WP_THEME_NAME/lang

else
    echo "Options do not match"
    exit 1
fi

