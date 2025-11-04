#!/bin/sh
set -e

if [ -d "/docker-entrypoint.d" ]; then
    for script in /docker-entrypoint.d/*.sh; do
        if [ -f "$script" ] && [ -x "$script" ]; then
            echo "Running ${script##*/}"
            "$script"
        fi
    done
fi

exec docker-php-entrypoint "$@"
