#! /bin/bash

if [ -e .env ]; then
    source .env
fi

case "$1" in
  "production")
    if [[ -n "$RSYNC_PRODUCTION_REMOTE_PATH" ]];
      then
        rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/"  -e ssh ./source/wp-content/themes/${WP_THEME_NAME}/ ${RSYNC_PRODUCTION_REMOTE_PATH}
      else
        echo 'Error: No remote path' && exit 0
    fi
    ;;
  "test")
    if [[ -n "$RSYNC_TEST_REMOTE_PATH" ]];
      then
        rsync -av --delete --exclude ".DS_Store" --exclude ".gitkeep" --exclude="node_modules/" --exclude="vendor/" -e ssh ./source/wp-content/themes/${WP_THEME_NAME}/ ${RSYNC_TEST_REMOTE_PATH}
      else
        echo 'Error: No remote path' && exit 0
    fi
    ;;
  "zip")
    mkdir -p .zip && (cd ./source/wp-content/themes/${WP_THEME_NAME}/ && zip -r -x "*.DS_Store" "*__MACOSX*" "*node_modules*" - .) > .zip/${WP_THEME_NAME}.zip
    ;;
  *)
    echo "undefined"
    ;;
esac
