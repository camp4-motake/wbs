#! /bin/bash

WP_SITE_PREFIX=wbs

if [ -e .env ]; then
    source .env
fi

npm run build

case "$1" in
  "production")
    if [[ -n "$PRODUCTION_WP_ROOT_PATH" ]];
      then
        rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" --exclude="*.tsbuildinfo"  -e ssh ./themes/${WP_SITE_PREFIX}/ ${PRODUCTION_WP_ROOT_PATH}/wp-content/themes/${WP_SITE_PREFIX}
        # rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" --exclude="*.tsbuildinfo"  -e ssh ./plugins/${WP_SITE_PREFIX}-plugin/ ${PRODUCTION_WP_ROOT_PATH}/wp-content/plugins/${WP_SITE_PREFIX}-plugin
      else
        echo 'Error: No remote path' && exit 0
    fi
    ;;
  "test")
    if [[ -n "$TEST_WP_ROOT_PATH" ]];
      then
        rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" --exclude="*.tsbuildinfo" -e ssh ./themes/${WP_SITE_PREFIX}/ ${TEST_WP_ROOT_PATH}/wp-content/themes/${WP_SITE_PREFIX}
        # rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" --exclude="*.tsbuildinfo" -e ssh ./plugins/${WP_SITE_PREFIX}-plugin/ ${TEST_WP_ROOT_PATH}/wp-content/plugins/${WP_SITE_PREFIX}-plugin
      else
        echo 'Error: No remote path' && exit 0
    fi
    ;;
  "zip")
		rm -rf .zip/
    mkdir -p .zip/themes && (cd ./themes/${WP_SITE_PREFIX}/ && zip -r -x "*.DS_Store" "*__MACOSX*" "*node_modules*" "*vendor*" "*.tsbuildinfo"  - .) > .zip/themes/${WP_SITE_PREFIX}.zip
    # mkdir -p .zip/plugins && (cd ./plugins/${WP_SITE_PREFIX}-plugin/ && zip -r -x "*.DS_Store" "*__MACOSX*" "*node_modules*" "*vendor*" "*.tsbuildinfo"  - .) > .zip/plugins/${WP_SITE_PREFIX}-plugin.zip
    ;;
  *)
    echo "undefined"
    ;;
esac
