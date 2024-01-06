#!/bin/bash

set -e

root=$(dirname "$(wp config path)")

# export db
mkdir -p "${root}/env/_bkp"
wp db export "${root}/env/_bkp/wp-backup-db.sql"

# export uploads
rm -rf "${root}/env/_bkp/uploads/"
mkdir -p "${root}/env/_bkp"
cp -r "${root}/wp-content/uploads/." "${root}/env/_bkp/uploads/"
