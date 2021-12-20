#! /bin/bash -eu

if [ -z "$*" ]; then
    for i in /scripts/start.d/*; do
        if [ -x "$i" ]; then
            echo "[..] Running start script" "$(basename "$i")"
            if ! "$i"; then
                echo "[!!] Start script" "$(basename "$i")" "failed"
                exit 1
            fi
        fi
    done
else
    exec "$@"
fi
