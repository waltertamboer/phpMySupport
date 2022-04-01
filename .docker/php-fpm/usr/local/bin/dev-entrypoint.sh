#!/usr/bin/env bash

# Stop on errors
set -e

_VHOST_DIR=/opt/phpmysupport

# Set the apache user and group to match the host user.
OWNER=$(stat -c '%u' "$_VHOST_DIR")
GROUP=$(stat -c '%g' "$_VHOST_DIR")

if [[ "$OWNER" != "0" ]]; then
	sed -i "s/:82:82:/:${OWNER}:${GROUP}:/" /etc/passwd 
	sed -i "s/:82:/:${GROUP}:/" /etc/group
fi

exec "$@"
