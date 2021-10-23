#!/bin/bash

set -e -x
service apache2 restart

if [ -z "$*" ]; then
	/bin/bash
else
	exec "$@"
fi
