#! /bin/bash

if [ ! -e auth.json ]; then
  echo '{
  "http-basic": {
    "connect.advancedcustomfields.com": {
      "username": "",
      "password": "https://camp4.jp/"
    }
  }
}' > auth.json
fi

if [ ! -e .wp-env.override.json ]; then
  echo '{}' > .wp-env.override.json
fi
