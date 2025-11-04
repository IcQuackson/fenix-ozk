#!/bin/bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/html}"
STORAGE_DIR="$APP_DIR/storage"
BOOTSTRAP_CACHE="$APP_DIR/bootstrap/cache"
APP_USER="${APP_USER:-www-data}"
APP_GROUP="${APP_GROUP:-$APP_USER}"

mkdir -p \
  "$STORAGE_DIR/framework/cache/data" \
  "$STORAGE_DIR/framework/sessions" \
  "$STORAGE_DIR/framework/testing" \
  "$STORAGE_DIR/framework/views" \
  "$STORAGE_DIR/logs" \
  "$BOOTSTRAP_CACHE"

chown -R "$APP_USER:$APP_GROUP" "$STORAGE_DIR" "$BOOTSTRAP_CACHE"
chmod -R ug+rwX "$STORAGE_DIR" "$BOOTSTRAP_CACHE"

if [ ! -L "$APP_DIR/public/storage" ]; then
  php artisan storage:link --force || true
fi
