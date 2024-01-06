#!/bin/bash

set -e

root=$(dirname "$(wp config path)")

# import db
wp db reset
wp db import env/_bkp/wp-backup-db.sql

# import uploads
rm -rf "${root}/wp-content/uploads"
cp -r "${root}/env/_bkp/uploads/" "${root}/wp-content/uploads/"
