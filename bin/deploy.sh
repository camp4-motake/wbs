#! /bin/bash

WP_SITE_SLUG=mysite

if [ -e .env ]; then
    source .env
fi

case "$1" in
  "production")
    if [[ -n "$RSYNC_PRODUCTION_REMOTE_WP_PATH" ]];
      then
        rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" --exclude="*.tsbuildinfo"  -e ssh ./source/wp-content/themes/${WP_SITE_SLUG}/ ${RSYNC_PRODUCTION_REMOTE_WP_PATH}/wp-content/themes/${WP_SITE_SLUG}
        rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" --exclude="*.tsbuildinfo"  -e ssh ./source/wp-content/plugins/${WP_SITE_SLUG}/ ${RSYNC_PRODUCTION_REMOTE_WP_PATH}/wp-content/plugins/${WP_SITE_SLUG}
      else
        echo 'Error: No remote path' && exit 0
    fi
    ;;
  "test")
    if [[ -n "$RSYNC_TEST_REMOTE_WP_PATH" ]];
      then
        rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" --exclude="*.tsbuildinfo" -e ssh ./source/wp-content/themes/${WP_SITE_SLUG}/ ${RSYNC_TEST_REMOTE_WP_PATH}/wp-content/themes/${WP_SITE_SLUG}
        rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" --exclude="*.tsbuildinfo" -e ssh ./source/wp-content/plugins/${WP_SITE_SLUG}/ ${RSYNC_TEST_REMOTE_WP_PATH}/wp-content/plugins/${WP_SITE_SLUG}
      else
        echo 'Error: No remote path' && exit 0
    fi
    ;;
  "zip")
	  npm run build
    mkdir -p .zip/themes && (cd ./source/wp-content/themes/${WP_SITE_SLUG}/ && zip -r -x "*.DS_Store" "*__MACOSX*" "*node_modules*" "*vendor*" "*.tsbuildinfo"  - .) > .zip/themes/${WP_SITE_SLUG}.zip
    mkdir -p .zip/plugins && (cd ./source/wp-content/plugins/${WP_SITE_SLUG}/ && zip -r -x "*.DS_Store" "*__MACOSX*" "*node_modules*" "*vendor*" "*.tsbuildinfo"  - .) > .zip/plugins/${WP_SITE_SLUG}.zip
    ;;
  *)
    echo "undefined"
    ;;
esac
