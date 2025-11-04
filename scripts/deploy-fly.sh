#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_ROOT"

FLY_TOML="${FLY_TOML:-$PROJECT_ROOT/fly.toml}"
ENV_FILE="${ENV_FILE:-$PROJECT_ROOT/.env.prod}"
VOLUME_NAME="${VOLUME_NAME:-storage}"
VOLUME_SIZE="${VOLUME_SIZE:-1}"

if ! command -v fly >/dev/null 2>&1; then
  echo "fly CLI is not installed or not in PATH. Install Fly.io CLI first." >&2
  exit 1
fi

if [ ! -f "$FLY_TOML" ]; then
  echo "fly.toml not found at $FLY_TOML. Run from the project root or set FLY_TOML." >&2
  exit 1
fi

if [ ! -f "$ENV_FILE" ]; then
  echo "Environment file $ENV_FILE not found. Set ENV_FILE or create .env.prod." >&2
  exit 1
fi

parse_from_toml() {
  local key="$1"
  awk -F'=' -v k="$key" '
    $1 ~ "^[[:space:]]*"k"[[:space:]]*$" {
      gsub(/^[[:space:]]+|[[:space:]]+$/, "", $2)
      gsub(/^"|"$/, "", $2)
      print $2
      exit
    }
  ' "$FLY_TOML"
}

FLY_APP="${FLY_APP:-$(parse_from_toml "app")}"
PRIMARY_REGION="${FLY_REGION:-$(parse_from_toml "primary_region")}"

if [ -z "$FLY_APP" ]; then
  echo "Unable to determine Fly app name. Set FLY_APP or add 'app' to fly.toml." >&2
  exit 1
fi

if [ -z "$PRIMARY_REGION" ]; then
  echo "Unable to determine primary region. Set FLY_REGION or add 'primary_region' to fly.toml." >&2
  exit 1
fi

volume_exists() {
  local tmpfile rc
  tmpfile="$(mktemp)"
  if ! fly volumes list --app "$FLY_APP" --json >"$tmpfile" 2>/dev/null; then
    rm -f "$tmpfile"
    return 1
  fi

  if command -v python3 >/dev/null 2>&1; then
    python3 - "$VOLUME_NAME" <"$tmpfile" <<'PYCODE'
import json
import sys

name = sys.argv[1]

try:
    volumes = json.load(sys.stdin)
except json.JSONDecodeError:
    sys.exit(1)

sys.exit(0 if any(vol.get("Name") == name for vol in volumes) else 1)
PYCODE
    rc=$?
  elif command -v python >/dev/null 2>&1; then
    python - "$VOLUME_NAME" <"$tmpfile" <<'PYCODE'
import json
import sys

name = sys.argv[1]

try:
    volumes = json.load(sys.stdin)
except json.JSONDecodeError:
    sys.exit(1)

sys.exit(0 if any(vol.get("Name") == name for vol in volumes) else 1)
PYCODE
    rc=$?
  else
    if grep -q "\"Name\": \"$VOLUME_NAME\"" "$tmpfile"; then
      rc=0
    else
      rc=1
    fi
  fi

  rm -f "$tmpfile"
  return $rc
}

echo "Ensuring volume '$VOLUME_NAME' exists for app '$FLY_APP' in region '$PRIMARY_REGION'..."
if volume_exists; then
  echo "Volume already exists; skipping creation."
else
  fly volumes create "$VOLUME_NAME" \
    --size "$VOLUME_SIZE" \
    --region "$PRIMARY_REGION" \
    --app "$FLY_APP"
fi

echo "Clearing cached config on Fly..."
fly ssh console --app "$FLY_APP" --command "php artisan optimize:clear" || true

echo "Importing secrets from $ENV_FILE..."
grep -vE '^(#|$)' "$ENV_FILE" | fly secrets import --app "$FLY_APP"

echo "Deploying to Fly.io..."
fly deploy --config "$FLY_TOML"

echo "Deployment complete."
